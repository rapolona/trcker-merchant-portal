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
        
        $api_endpoint = "/merchant/campaign/all"; 
        /*
        $profile = Http::get($api_endpoint,  [
        ]);
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
        return view('campaign.create', []);
    }
}
