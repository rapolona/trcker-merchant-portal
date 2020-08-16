<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $this->view();
    }

    public function view()
    {
        //payload
        $merchant_id = "";
        $user_id = "";
        
        $api_endpoint = "/merchant/tasks"; 
        /*
        $profile = Http::get($api_endpoint,  [
        ]);
        */
        //mock data
        $tasks = array(
            array(
                "no" => 1,
                'task_action_name' => 'Campaign 1',
                "description" => "Php 20000",
                "subject_level" => "07/02/2020 to 08/02/2020",
                "action" => 1
            ),
            array(
                "no" => 1,
                'task_action_name' => 'Campaign 2',
                "description" => "Php 10000",
                "subject_level" => "07/02/2020 to 08/02/2020",
                "action" => 1
            ),
            array(
                "no" => 1,
                'task_action_name' => 'Campaign 3',
                "description" => "Php 5000",
                "subject_level" => "07/02/2020 to 08/02/2020",
                "action" => 1
            )            
        );

        return view('task.task', ['tasks' => $tasks]);
    }

    public function create()
    {
        return view('task.create', []);
    }

    //AJAX for Save Details task.task.blade.php
    public function create_ticket()
    {
        return view('task.create', []);
    }
}
