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
        $arr = [
            [
              'ID' => 1
            ]
          ];

          echo array_search(1, array_column($arr, 'ID')); // prints 0 (!== false)

        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";
        var_dump($token);
    }

    public function view_profile(Request $request)
    {
        //api call for merchant information - no api endpoint yet
        //http://localhost:6001/merchant/profile

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/profile";

        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $response = Http::withToken($token)->get($api_endpoint, []);

        if ($response->status() !== 200)
        {
            if ($response->status() === 403) {
                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");

                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
            }

            if ($response->status() === 500) {
                $handler = json_decode($response->body());

                if ($handler->message->name == "JsonWebTokenError")

                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");

                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
            }

            //general handling
            return redirect('/dashboard');
        }

        $profile = json_decode($response);

        return view('concrete.merchant.profile', ['profile' => $profile]);
    }

    public function modify_profile(Request $request)
    {
        //TODO: Field validation, will throw error on hit as response
        $data = (array) $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:64',
            'address' => 'required|max:64',
            'trade_name' => 'required|max:64',
            'authorized_representative' => 'required|max:64',
            'position' => 'required|max:64',
            'contact_number' => 'required|numeric|digits_between:1,64',
            'email_address' => 'required|email|max:64'
        ]);

        if ($validator->fails())
        {
            $error_string = "<b>Fields with Errors</b><br/>";
            foreach ($validator->errors()->messages() as $k => $v)
            {
                $error_string .= "{$k}: <br/>";
                foreach ($v as $l)
                    $error_string .= "{$l}<br/>";
            }

            return Response()->json([
                "success" => false,
                "message" => $error_string,
                "file" => $data,
            ], 422);
        }

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/profile";

        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $response = Http::withToken($token)->put($api_endpoint, $data);

        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile modification
            return Response()->json([
                "success" => false,
                "message" => "Failed updating Merchant Information.", // with error:" . $response->body(),
                "file" => $data,
            ], 422);
        }

        return Response()->json([
            "success" => true,
            "message" => "Update Merchant Information success!",// . $response->body(),
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
                "no" => 2,
                "campaign_name" => "Campaign 2",
                "budget" => "Php 100,000",
                "duration" => "07/05/2020 to 08/13/2020",
                "status" => "Completed",
            ),
            array(
                "no" => 3,
                "campaign_name" => "Campaign 3",
                "budget" => "Php 100,000",
                "duration" => "07/13/2020 to 08/25/2020",
                "status" => "Completed",
            ),

        );
        return view('concrete.merchant.rewards', ['rewards' => $rewards, 'remaining_budget' => $remaining_budget]);
    }
}
