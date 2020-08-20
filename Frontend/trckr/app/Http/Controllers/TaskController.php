<?php

namespace App\Http\Controllers;

Use App\User;
use App\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;   
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Validator,Redirect,File;
use Config, Session;

class TaskController extends Controller
{
    public function index()
    {
        $this->view();
    }

    public function view(Request $request)
    {
        $api_endpoint = Config::get('trckr.backend_url') . "merchant/task";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;

        $response = Http::withToken($token)->get($api_endpoint, []);

        if ($response->status() !== 200)
        {
            //provide handling for failed campaign type retrieval
            return redirect('/dashboard');
        }

        $tasks = json_decode($response->body());

        return view('task.task', ['tasks' => $tasks]);
    }

    public function create(Request $request)
    {
        return view('task.create', []);
    }

    //AJAX for Save Details task.task.blade.php
    public function create_task(Request $request)
    {
        $data = $request->all();

        $custom_request = [
            "task_action_name" => $data['task_action_name'],
            "task_action_description" => $data['task_action_description'],
            "subject_level" => $data['subject_level'],
            "data_type" => $data['data_type'],
            "data_source" => $data['data_source']
        ];

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/task";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;

        $response = Http::withToken($token)->post($api_endpoint, $data);

        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile modification
            return Response()->json([
                "success" => false,
                "message" => "Failed to Create New Task with error:" . $response->body(),
                "file" => $data,
            ], 422);
        }

        return Response()->json([
            "success" => true,
            "message" => "Custom Task creation successful!" . $response->body(),
            "file" => $data
        ]);
    }
}
