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
use DateTime;

class TicketController extends Controller
{
    public function index()
    {
        $this->view();
    }

    //Display method for ticket.blade.php
    public function view(Request $request)
    {
        $api_endpoint = Config::get('trckr.capability_url') . "capability/campaign";

        $session = $request->session()->get('session_merchant');
        
        if ( ! $session) return redirect('/');
        $token = $session->token;

        $response = Http::withToken($token)->get($api_endpoint, []);
        
        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile retrieval
            return redirect('/dashboard');
        }
        
        $campaign = json_decode($response);

        $tickets = array();

        foreach($campaign as $k)
        {
            //skip completed campaigns
            if ( ! $k->campaign_id) continue;

            $api_endpoint = Config::get('trckr.capability_url') . "capability/tasktickets";

            $session = $request->session()->get('session_merchant');
            
            if ( ! $session) return redirect('/');
            $token = $session->token;

            $data = array('campaign_id' => $k->campaign_id);
            /*
            $response = Http::withToken($token)->withBody(json_encode($data), 'application/json')->get($api_endpoint);
            */

            //switched to native curl because laravel HTTP library cannot attach JSON on get request
            //no error handling yet

            $headers = array(
                'Content-Type:application/json',
                'Authorization:Bearer ' . $token
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_endpoint);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = json_decode(curl_exec($ch));
            curl_close($ch);

            foreach ($response as $j) {
                $j->campaign_name = $k->campaign_name;
                $j->updatedAt = new DateTime($j->updatedAt);
                $j->updatedAt = $j->updatedAt->format("Y-m-d H:i:s");
                $tickets[] = $j;
            }
        }

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
        $tickets = array();
        foreach($data['task_ticket_id'] as $t)
        {
            $request = ["task_ticket_id" => $t]; 
            $response = Http::withToken($token)->put($api_endpoint, $request);
            $tickets[] = $response->body();

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
            "message" => "Approved Ticket(s) Successfully " . $response->body(),
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
        $tickets = array();
        foreach($data['task_ticket_id'] as $t)
        {
            $request = ["task_ticket_id" => $t]; 
            $response = Http::withToken($token)->put($api_endpoint, $request);
            $tickets[] = $response;

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
