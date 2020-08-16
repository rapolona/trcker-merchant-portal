<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CampaignController extends Controller
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
        
        //api call for campaigns
        //http://localhost:6001/merchant/campaign
        /*
        $api_endpoint = "http://localhost:6001/merchant/campaign";
        $campaigns = Http::withToken($this->$_backend_token)->get($api_endpoint,  []);
        $campaigns = json_decode($campaigns);
        */
        //mock data
        $campaigns = array(
            array(
                "no" => 1,
                'campaign_name' => 'Campaign 1',
                "budget" => "Php 20000",
                "duration" => "07/02/2020 to 08/02/2020",
                "status" => "Completed",
                "action" => 1,
            ),
            array(
                "no" => 1,
                'campaign_name' => 'Campaign 2',
                "budget" => "Php 10000",
                "duration" => "07/02/2020 to 08/02/2020",
                "status" => "Completed",
                "action" => 1,
            ),
            array(
                "no" => 1,
                'campaign_name' => 'Campaign 3',
                "budget" => "Php 5000",
                "duration" => "07/02/2020 to 08/02/2020",
                "status" => "Completed",
                "action" => 1,
            )            
        );

        return view('campaign.campaign', ['campaigns' => $campaigns]);
    }

    public function create()
    {
        //api call for tasks
        //http://localhost:6001/merchant/task
        /*
        $api_endpoint = "http://localhost:6001/merchant/task";
        $tasks = Http::withToken($this->$_backend_token)->get($api_endpoint,  []);
        $tasks = json_decode($tasks);
        */
        $tasks = array(
            array(
                "task_name" => "Sample Task 1",
                "description" => "Sample Description"
            ),
            array(
                "task_name" => "Sample Task 2",
                "description" => "Sample Description"
            ),
            array(
                "task_name" => "Sample Task 3",
                "description" => "Sample Description"
            ),
        );

        return view('campaign.create', ['tasks' => $tasks]);
    }

    public function create_campaign(Request $request)
    {
        /*
        request()->validate([
            'file'  => 'required|mimes:csv,txt|max:2048',
        ]);
        */

        $data = $request->all();

        request()->validate([
            'campaign_name' => 'required|max:50',
            'campaign_type' => 'required',
            'description' => '',
            'reward' => '',
            'duration_from' => '',
            'duration_to' => ''
        ]);

        return Response()->json([
            "success" => true,
            "messaGE" => "Campaign creation successful!",
            "file" => $data
        ]);
    }
}
