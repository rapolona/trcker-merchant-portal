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

class CampaignController extends Controller
{
    public function index()
    {
        $this->view();
    }

    public function view(Request $request)
    {        
        //api call for campaigns
        //http://localhost:6001/merchant/campaign
        /*
        $api_endpoint = "http://localhost:6001/merchant/campaign";
        $campaigns = Http::withToken($this->$_backend_token)->get($api_endpoint,  []);
        $campaigns = json_decode($campaigns);
        */

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/campaign";

        $session = $request->session()->get('session_merchant');
        
        if ( ! $session) return redirect('/');
        $token = $session->token;

        $response = Http::withToken($token)->get($api_endpoint, []);
        
        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile retrieval
            return redirect('/dashboard');
        }

        $response = json_decode($response);

        $campaigns = array();
        $count = 0;
        foreach ($response as $k)
        {
            $campaigns[] = (object) [
                'no' => $count+1,
                'campaign_id' => $k->campaign_id,
                'campaign_name' => ( ! empty($k->campaign_name)) ? $k->campaign_name : "{$k->campaign_id}-No Campaign Name",
                'budget' => ( ! empty($k->budget)) ? $k->budget : "{$k->campaign_id}-No Budget",
                'duration' => "{$k->start_date} to {$k->end_date}",
                'status' => ($k->status) ? "Ongoing" : "Completed"
            ];
            $count+=1;
        }

        return view('campaign.campaign', ['campaigns' => (object) $campaigns]);
    }

    public function create(Request $request)
    {
        //api for getting campaign_type
        $api_endpoint = Config::get('trckr.backend_url') . "api/task_action_classification";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;
        var_dump($token);
        $response = Http::withToken($token)->get($api_endpoint, []);

        if ($response->status() !== 200)
        {
            //provide handling for failed campaign type retrieval
            return redirect('/dashboard');
        }

        echo "<pre>";
        var_dump($response->body());
        echo "</pre>";

        $campaign_type = json_decode($response->body());

        //api for getting merchant tasks
        $api_endpoint = Config::get('trckr.backend_url') . "merchant/task";

        $response = Http::withToken($token)->get($api_endpoint, []);

        if ($response->status() !== 200)
        {
            //provide handling for tasks retrieval
            return redirect('/dashboard');
        }

        $tasks = json_decode($response->body());

        #get task_classification_id of custom task
        $custom_task_action_classification_id = "";
        foreach($campaign_type as $k) {
            var_dump($k);
            if ($k->task_type == "Custom") {
                //var_dump($k);exit;
                $custom_task_action_classification_id = $k->task_action_classification_id;
                break;   
            }
        }

        $custom_tasks = array();
        foreach($tasks as $k)
            if ($k->task_action_classification_id == $custom_task_action_classification_id)
                $custom_tasks[] = $k;


        //api for getting branches
        $api_endpoint = Config::get('trckr.backend_url') . "merchant/branch";

        $response = Http::withToken($token)->get($api_endpoint, []);

        if ($response->status() !== 200)
        {
            //provide handling for failed branch retrieval
            return redirect('/dashboard');
        }

        $branches = json_decode($response->body());

        return view('campaign.create', ['campaign_type' => $campaign_type, 'branches' => $branches, 'tasks' => $custom_tasks]);
    }

    public function create_campaign(Request $request)
    {
        request()->validate([
            "start_date" => "",
            "end_date" => "",
            "budget" => "",
            'campaign_name' => 'required|max:50',
            'campaign_type' => 'required',
            "reward" => "",
            "status" => "",
            "task_type" => "",
        ]);

        $data = $request->all();

        //set campaign status to ongoing
        $data['status'] = 1;

        //check audience data
        //check tasks data
        $campaign_task_actions = array();
        for ($i = 0; $i < count($data['task_action_id']); $i++)
        {
            $campaign_task_actions[] = array(
                'task_action_id' => $data['task_action_id'][$i],
                'title' => $data['task_action_name'][$i],
                'description' => $data['task_action_description'][$i]
            );
        }
        $data['campaign_task_actions'] = $campaign_task_actions;
        unset($data['task_action_id']);
        unset($data['task_action_name']);
        unset($data['task_action_description']);
        //check branches data

        $branches = array();
        $branches = array('branch_id' => $branches);
/*        
        for($i = 0; $i < count($data['branches']); $i++) {
            $branches[] = array(
                "branch_id" => $data['branches'][$i]
            );
        }
*/
        $data['branches'] = $branches;

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/campaign/create";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;

        $response = Http::withToken($token)->post($api_endpoint, $data);

        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile modification
            return Response()->json([
                "success" => false,
                "message" => "Failed to Add Branch with error:" . $response->body(),
                "file" => $data,
            ], 422);
        }

        return Response()->json([
            "success" => true,
            "message" => "Campaign creation successful!" . $response->body(),
            "file" => $data
        ]);
    }

    public function campaign_type(Request $request)
    {
        $task_id = $request->query("task_id");

        //api for getting campaign_type
        $api_endpoint = Config::get('trckr.backend_url') . "merchant/task";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;
        
        $response = Http::withToken($token)->get($api_endpoint, []);

        if ($response->status() !== 200)
        {
            //provide handling for failed campaign type retrieval
            return redirect('/dashboard');
        }

        $task_actions = json_decode($response->body());

        $filtered_task_action = array();
        foreach($task_actions as $k)
            if( $k->task_action_classification_id == $task_id)
                $filtered_task_action[] = $k;

        return Response()->json([
            "success" => true,
            "message" => "Campaign Type Tasks retrieval Succesfull",
            "file" => $filtered_task_action
        ]);
    }
}
