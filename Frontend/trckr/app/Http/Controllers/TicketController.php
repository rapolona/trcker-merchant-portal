<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator,Redirect,File;
use Config, Session;
use DateTime;
use App\Services\CapabilityService;

class TicketController extends Controller
{

    private $capabilityService;

    public function __construct(CapabilityService $capabilityService)
    {
        $this->capabilityService = $capabilityService;
    }
    public function index()
    {
        $this->view();
    }

    /**
     * List instance
     *
     * @return View
     */
    public function view(Request $request)
    {
        $campaigns = $this->capabilityService->getCampaigns();
        $tickets = array();

        foreach($campaigns as $k)
        {
            //skip completed campaigns
            if ( ! $k->campaign_id) continue;

            $data = ['campaign_id' => $k->campaign_id];
            $response = $this->capabilityService->getTicket($data);
            foreach ($response as $j) {
                $j->campaign_name = ($k->campaign_name) ? $k->campaign_name : 'NO Campaign Name';
                $j->updatedAt = new DateTime($j->updatedAt);
                $j->updatedAt = $j->updatedAt->format("F d, Y");
                $j->createdAt = new DateTime($j->createdAt);
                $j->createdAt = $j->createdAt->format("F d, Y");
                $tickets[] = $j;
            }
        }

      //  print_r($tickets); exit();

        return view('concrete.ticket.ticket', ['tickets' => $tickets]);
    }

    /**
     * Ticket instance
     *
     * @return View
     */
    public function view_ticket($campaignId, $ticketId)
    {
        $ticket = $this->capabilityService->getTicket([
            'task_ticket_id' => $ticketId,
            'campaign_id' => $campaignId,
        ]);
        //print_r($ticket); exit();
        return view('concrete.ticket.view', ['tickets' => $ticket[0]]);
    }

    /**
     * approve instance
     *
     * @return redirect
     */
    public function approve_ticket($campaignId, $ticketId)
    {
        $data = ['task_ticket_id' => $ticketId];
        $this->capabilityService->approveTicket($data);

        $msg = [
            "type" => "success",
            "message" => "Reject Ticket(s) Success!",
        ];

        return redirect('/ticket/view/' . "{$campaignId}/$ticketId")
            ->with("formMessage", $msg);
    }

    /**
     * reject instance
     *
     * @return redirect
     */
    public function reject_ticket($campaignId, $ticketId)
    {
        $data = ['task_ticket_id' => $ticketId];
        $this->capabilityService->rejectTicket($data);

        $msg = [
            "type" => "success",
            "message" => "Reject Ticket(s) Success!",
        ];

        return redirect('/ticket/view/' . "{$campaignId}/$ticketId")
            ->with("formMessage", $msg);
    }

    /**
     * reject instance
     *
     * @return redirect
     */
    public function bulk_action(Request $request)
    {
        $data = (array)$request->all();

        if(!isset($data['tickets'])){
            $msg = [
                "type" => "warning",
                "message" => "Pls check an item you want to " . $data['status'],
            ];

            return redirect('/ticket/view/')
                ->with("formMessage", $msg);
        }

        foreach($data['tickets'] as $id){
            $newData = ['task_ticket_id' => $id];
            if($data['status']=='reject'){
                $this->capabilityService->rejectTicket($newData);
            }else{
                $this->capabilityService->approveTicket($newData);
            }
        }

        $msg = [
            "type" => "success",
            "message" =>  ucfirst($data['status']) . " Ticket(s) Success!",
        ];

        return redirect('/ticket/view/')
            ->with("formMessage", $msg);
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
        $csv_data[0] = [
            'Ticket ID',
            'Full Name',
            'Account Level',
            'Email',
            'Device ID',
            'Approval Status',
            'Campaign ID',
            'Campaign Name',
            'Ticket Submitted',
            'Mobile Number',
            'Location',
            'Ticket Status',
            'Branch',
            'Question',
            'Answer'
        ];
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

       // fputcsv($df, array_keys(reset($array), ";"));

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
