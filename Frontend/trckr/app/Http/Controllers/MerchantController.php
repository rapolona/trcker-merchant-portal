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


class MerchantController extends Controller
{
    public function index()
    {
        $this->view_profile();
    }

    public function debug(Request $request)
    {
        $session = $request->session()->get('session_merchant');
        $token = $session->token;
        var_dump($token);
    }

    public function view_profile(Request $request)
    {
        //payload
        $merchant_id = "";
        $user_id = "";
        
        //api call for merchant information - no api endpoint yet
        //http://localhost:6001/merchant/profile

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/profile";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;

        $response = Http::withToken($token)->get($api_endpoint, []);
        
        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile retrieval
            return redirect('/dashboard');
        }

        $profile = json_decode($response);

        return view('merchant.merchant', ['profile' => $profile]);
    }

    public function modify_profile(Request $request)
    {
        //TODO: Field validation, will throw error on hit as response
        $data = (array) $request->all();

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/profile";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;

        $response = Http::withToken($token)->put($api_endpoint, $data);
        
        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile modification
            return Response()->json([
                "success" => false,
                "message" => "Failed updating Merchant Information with error:" . $response->body(),
                "file" => $data,
            ], 422);
        }

        return Response()->json([
            "success" => true,
            "message" => "Merchant Details are successfully saved!" . $response->body(),
            "file" => $data,
        ]);
    }

    public function rewards()
    {
        //api call for rewards - no api endpoint yet
        //http://localhost:6001/?
        /*
        $api_endpoint = "http://localhost:6001/?";
        $rewards = Http::withToken($this->$_backend_token)->get($api_endpoint,  []);
        $rewards = json_decode($rewards);
        */
        $remaining_budget = "Php 100,000";
        $rewards = array(
            array(
                "no" => 1,
                "campaign_name" => "Campaign 1",
                "budget" => "Php 100,000",
                "duration" => "07/02/2020 to 08/02/2020",
                "status" => "Completed",
            ),
            array(
                "no" => 1,
                "campaign_name" => "Campaign 2",
                "budget" => "Php 100,000",
                "duration" => "07/05/2020 to 08/13/2020",
                "status" => "Completed",
            ),
            array(
                "no" => 1,
                "campaign_name" => "Campaign 3",
                "budget" => "Php 100,000",
                "duration" => "07/13/2020 to 08/25/2020",
                "status" => "Completed",
            ),
            
        );
        return view('merchant.rewards', ['rewards' => $rewards, 'remaining_budget' => $remaining_budget]);
    }
}
