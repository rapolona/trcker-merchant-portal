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

class TicketController extends Controller
{
    public function index()
    {
        $this->view();
    }

    //Display method for ticket.blade.php
    public function view()
    {
        //payload
        $merchant_id = "";
        $user_id = "";
        
        $api_endpoint = "/merchant/ticket/all"; 
        /*
        $profile = Http::get($api_endpoint,  [
        ]);
        */
        //mock data
        $tickets = array(
            array(
                "trckr_username" => "test",
                "email" => "a@a.com",
                "mobile_number" => "09171234567",
                'campaign_name' => 'Campaign 1',
                "tasks" => "blah",
                "date_submitted" => "06/15/2020",
                "device_id" => "123ABC",
                "location" => "Quezon City",
                "status" => "No rewards yet",
                "task_ticket_id" => 1,
            ),
            array(
                "trckr_username" => "test",
                "email" => "a@a.com",
                "mobile_number" => "09171234567",
                'campaign_name' => 'Campaign 1',
                "tasks" => "blah",
                "date_submitted" => "06/15/2020",
                "device_id" => "123ABC",
                "location" => "Quezon City",
                "status" => "No rewards yet",
                "task_ticket_id" => 2,
            ),
            array(
                "trckr_username" => "test",
                "email" => "a@a.com",
                "mobile_number" => "09171234567",
                'campaign_name' => 'Campaign 1',
                "tasks" => "blah",
                "date_submitted" => "06/15/2020",
                "device_id" => "123ABC",
                "location" => "Quezon City",
                "status" => "No rewards yet",
                "task_ticket_id" => 3,
            ) 
        );

        $tickets = json_encode($tickets);
        $tickets =json_decode($tickets);

        return view('ticket.ticket', ['tickets' => $tickets]);
    }

    public function single_view()
    {
        //payload
        $merchant_id = "";
        $user_id = "";
        
        $api_endpoint = "/merchant/ticket/all"; 
        /*
        $profile = Http::get($api_endpoint,  [
        ]);
        */
        //mock data
        $tickets_data = array(
        );

        return view('ticket.ticket', ['tickets' => $tickets]);
    }

    //AJAX for Accept Ticket ticket.ticket.blade.php
    public function approve_ticket(Request $request)
    {
        $data = $request->all();

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/approve";
        $session = $request->session()->get('session_merchant');
        $token = $session->token;
        
        $count = 1;
        $debug = array();
        foreach($data['task_ticket_id'] as $t)
        {
            $request = ["task_ticket_id" => $t]; 
            $response = Http::withToken($token)->post($api_endpoint, $request);
            $debug[] = $response;

            if ($response->status() !== 200)
            {
                //provide handling for failed ticket approval
                return Response()->json([
                    "success" => false,
                    "message" => "Failed Ticket Approval {$count} with error: [{$response->status()}] {$response->body()}",
                    "file" => json_encode($response),
                    "data" => json_encode($t)
                ], 422);
            }
            $count+=1;
        }

        return Response()->json([
            "success" => true,
            "message" => "Uploaded file successfully" . $response->body(),
            "file" => $tickets
        ]);
    }

    //AJAX for Reject Ticket ticket.ticket.blade.php
    public function reject_ticket(Request $request)
    {
        $data = $request->all();

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/reject";
        $session = $request->session()->get('session_merchant');
        $token = $session->token;
        
        $count = 1;
        $debug = array();
        foreach($data['task_ticket_id'] as $t)
        {
            $request = ["task_ticket_id" => $t]; 
            $response = Http::withToken($token)->post($api_endpoint, $request);
            $debug[] = $response;

            if ($response->status() !== 200)
            {
                //provide handling for failed ticket approval
                return Response()->json([
                    "success" => false,
                    "message" => "Failed Ticket Approval {$count} with error: [{$response->status()}] {$response->body()}",
                    "file" => json_encode($response),
                    "data" => json_encode($t)
                ], 422);
            }
            $count+=1;
        }

        return Response()->json([
            "success" => true,
            "message" => "Uploaded file successfully" . $response->body(),
            "file" => $tickets
        ]);
    }
}
