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
        
        $campaign = json_decode($response);

        $tickets = array();

        foreach($campaign as $k)
        {
            //skip completed campaigns
            if ( ! $k->campaign_id) continue;

            $api_endpoint = Config::get('trckr.capability_url') . "capability/tasktickets";

            $session = $request->session()->get('session_merchant');
            
            if ( ! $session) return redirect('/');
            $token = ( ! empty($session->token)) ? $session->token : "";

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

    public function view_ticket(Request $request)
    {
        $ticket_id = $request->query('ticket_id');

        $api_endpoint = Config::get('trckr.capability_url') . "capability/campaign";

        $session = $request->session()->get('session_merchant');
        
        if ( ! $session) return redirect('/');
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
        
        $campaign = json_decode($response);

        $tickets = array();

        foreach($campaign as $k)
        {
            //skip completed campaigns
            if ( ! $k->campaign_id) continue;

            $api_endpoint = Config::get('trckr.capability_url') . "capability/tasktickets";

            $session = $request->session()->get('session_merchant');
            
            if ( ! $session) return redirect('/');
            $token = ( ! empty($session->token)) ? $session->token : "";

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
                $j->campaign = $k;
                $j->campaign_name = $k->campaign_name;
                $j->updatedAt = new DateTime($j->updatedAt);
                $j->updatedAt = $j->updatedAt->format("Y-m-d H:i:s");
                if ($ticket_id == $j->task_ticket_id)
                    $tickets[] = $j;
            }
        }

        return view('ticket.view', ['tickets' => $tickets[0]]);
    }

    //AJAX for Accept Ticket ticket.ticket.blade.php
    public function approve_ticket(Request $request)
    {
        $data = $request->all();

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/approve";
        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";
        
        $count = 1;
        $tickets = array();
        $data['task_ticket_id'] = explode(",", $data['task_ticket_id']);
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
            "message" => "Approved Ticket(s) Success!", // . $response->body(),
            "file" => $tickets
        ]);
    }

    //AJAX for Reject Ticket ticket.ticket.blade.php
    public function reject_ticket(Request $request)
    {
        $data = $request->all();

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/reject";
        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";
        
        $count = 1;
        $tickets = array();
        $data['task_ticket_id'] = explode(",", $data['task_ticket_id']);
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
            "message" => "Reject Ticket(s) Success!", // . $response->body(),
            "file" => $tickets
        ]);
    }

    public function export_csv(Request $request)
    {
        $api_endpoint = Config::get('trckr.capability_url') . "capability/campaign";

        $session = $request->session()->get('session_merchant');
        
        if ( ! $session) return redirect('/');
        $token = ( ! empty($session->token)) ? $session->token : "";

        //pull all campaign info
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
        
        $campaign = json_decode($response);

        $tickets = array();

        //pull tickets
        foreach($campaign as $k)
        {
            //skip completed campaigns
            if ( ! $k->campaign_id) continue;

            $api_endpoint = Config::get('trckr.capability_url') . "capability/tasktickets";

            $session = $request->session()->get('session_merchant');
            
            if ( ! $session) return redirect('/');
            $token = ( ! empty($session->token)) ? $session->token : "";

            $data = array('campaign_id' => $k->campaign_id);

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
                $j->campaign = $k;
                $j->campaign_name = $k->campaign_name;
                $j->createdAt = new DateTime($j->createdAt);
                $j->createdAt = $j->createdAt->format("Y-m-d H:i:s");
                $tickets[] = $j;
            }
        }

        //creating the csv data
        $csv_data = array();
        foreach ($tickets as $k)
        {
            $k->createdAt = new DateTime($k->createdAt);
            $base_data = array(
                'Ticket ID' => $k->task_ticket_id,
                'Full Name' => "{$k->user_detail->first_name} {$k->user_detail->last_name}",
                'Account Level' => $k->user_detail->account_level,
                'Email' => $k->user_detail->email,
                'Device ID' => $k->device_id,
                'Approval Status' => $k->approval_status,
                'Campaign ID' => $k->campaign_id,
                'Campaign Name' => $k->campaign_name,
                'Ticket Submitted' => $k->createdAt->format("Y-m-d H:i:s"),
                'Mobile Number' => "No info available yet",
                'Location' => "No info available yet",
                'Ticket Status' => $k->approval_status,
            );

            //branch name
            foreach ($k->campaign->branches as $branches)
            {
                if ($k->branch_id == $branches->branch_id) {
                    $base_data['Branch Name'] = $branches->name;
                    break;
                }
                continue;
            }

            //creating rows per number of questions per task
            foreach($k->task_details as $individual_task)
            {
                //skipping images for now
                if (substr($individual_task->response, 0, 11) == "data:image/")
                    continue;
                $row_data = $base_data;
                $row_data['Task Question'] = $individual_task->task_question->question;
                $row_data['Answer'] = $individual_task->response;

                $csv_data[] = $row_data;
            }
        }

        //echo $this->array2csv($csv_data);exit;
        $this->download_send_headers("export.csv");
        echo $this->array2csv($csv_data);
        die();
    }

    function array2csv(array &$array)
    {
        if (count($array) == 0) return null;

        ob_start();
        $df = fopen("php://output", 'w');

        fputcsv($df, array_keys(reset($array), ";"));

        foreach ($array as $row) fputcsv($df, $row, ";");

        fclose($df);
        return ob_get_clean();
    }

    function download_send_headers($filename = "export.csv") {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
    
        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
    
        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }
}
