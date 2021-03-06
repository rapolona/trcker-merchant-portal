<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator,Redirect,File;
use Config, Session;
use DateTime;
use App\Services\CapabilityService;
use App\Services\MerchantService;

class TicketController extends Controller
{

    private $capabilityService;

    private $merchantService;

    private $defaultPerPage;

    public function __construct(CapabilityService $capabilityService, MerchantService $merchantService)
    {
        $this->capabilityService = $capabilityService;
        $this->merchantService = $merchantService;
        $this->defaultPerPage = Config::get('trckr.table_pagination');
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
        $filter = [
            'name' => $request->name,
            'campaignname' => $request->campaignname,
            'status' => $request->status,
            'daterange' => $request->daterange
        ];

        $dateStart = "";
        $dateEnd = "";

        if(isset($request->daterange) && !empty($request->daterange)){
            $date_range = explode(" - ", $request->daterange);
            $dateStart = DateTime::createFromFormat("m/d/Y" , $date_range[0]);
            $dateStart  = $dateStart ->format('Y-m-d');
            $dateEnd= DateTime::createFromFormat("m/d/Y" , $date_range[1]);
            $dateEnd = $dateEnd->format('Y-m-d');          
        }

        $data = [
            'count_per_page' => isset($request->per_page)? $request->per_page : $this->defaultPerPage,
            'page' => isset($request->page)? $request->page : 1,
            'respondent' => $request->name,
            'campaign_name' => $request->campaignname,
            'status' => $request->status,
            'submission_date_start' => $dateStart,
            'submission_date_end' => $dateEnd,
            //'campaign_id' => 'f959000f-d2d5-40da-a0c6-bd073586e1c7'
        ];

        
        $list = $this->merchantService->getAllTickets($data); 
        //print_r($list); exit();
        $rows = [];
        $current_page = 1;
        $total_pages = 1;
        if($list){
            $rows = $list->rows;
            $current_page = $list->current_page;
            $total_pages = $list->total_pages;
        }

        $pagination = [
            'per_page' => $data['count_per_page'],
            'current_page' => $current_page,
            'total_pages' => $total_pages
        ];

        return view('concrete.ticket.ticket', ['tickets' => $rows, 'pagination' => $pagination, 'filter' => $filter ]);
    }

    /**
     * Ticket instance
     *
     * @return View
     */
    public function view_ticket($campaignId, $ticketId, Request $request)
    {
        $ticket = $this->capabilityService->getTicket([
            'task_ticket_id' => $ticketId,
            'campaign_id' => $campaignId,
        ]);

        $filter = [
            'name' => $request->name,
            'campaignname' => $request->campaignname,
            'status' => $request->status,
            'daterange' => $request->daterange
        ];
     
       /* $pagination = $this->merchantService->nextPrevTicket([
            'task_ticket_id' => $ticketId,
            'page' => isset($request->page)? $request->page : 1,
            'count_per_page' => 25,
           // 'filter' => $filter,
            'name' => $request->name,
            'campaignname' => $request->campaignname,
            'status' => $request->status,
            'daterange' => $request->daterange
        ]);*/

        //print_r($pagination); exit();

        //if(strtotime(date('2021-02-09')) > strtotime($ticket[0]->createdAt)){
            $task_details = $ticket[0]->task_details;
            foreach ($task_details as $key => $value) {
                unset($task_details[$key]->task_detail_id);
            }

            //$task_details = array_map("unserialize", array_unique(array_map("serialize", $task_details)));
            $newDetails = [];
            foreach ($task_details as $key => $value) {
                if(!isset($newDetails[$value->task_question->question . '-' . $value->response])){
                    $newDetails[$value->task_question->question . '-' . $value->response] = $value;
                }
            }

            $ticket[0]->task_details = $newDetails;
       // }

        return view('concrete.ticket.view', ['tickets' => $ticket[0]]); //, 'pagination' => $pagination]);
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
            "message" => "Approve Ticket(s) Success!",
        ];

        return redirect()->back()
            ->with("formMessage", $msg);
    }

    /**
     * reject instance
     *
     * @return redirect
     */
    public function reject_ticket($campaignId, $ticketId, Request $request)
    {
        $data = [
            'task_ticket_id' => $ticketId,
            'rejection_reason' => $request->rejection_reason
        ];
        $this->capabilityService->rejectTicket($data);

        $msg = [
            "type" => "success",
            "message" => "Reject Ticket(s) Success!",
        ];

        return redirect()->back()
            ->with("formMessage", $msg);
    }

    /**
     * award instance
     *
     * @return redirect
     */
    public function award_ticket($campaignId, $ticketId, Request $request)
    {
        $data = [
            'task_ticket_id' => $ticketId
        ];
        $this->merchantService->awardTicket($data);

        $msg = [
            "type" => "success",
            "message" => "Award Ticket(s) Success!",
        ];

        return redirect()->back()
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
            //'Location',
            'Ticket Status',
            'Branch',
            'Task Name',
            'Question',
            'Answer',
            'Reward'
        ];
        
        $tickets = $this->merchantService->getTicketReport(); 



        foreach ($tickets as $key => $k)
        {
            $k->createdAt = new DateTime($k->createdAt);

            foreach ($k->task_details as $detailKey => $details) {
                # code...
          
                $base_data = array(
                    'Ticket ID' => $k->task_ticket_id,
                    'Full Name' => "{$k->user_detail->first_name} {$k->user_detail->last_name}",
                    'Account Level' => $k->user_detail->account_level,
                    'Email' => $k->user_detail->email,
                    'Device ID' => $k->device_id,
                    'Approval Status' => $k->approval_status,
                    'Campaign ID' => $k->campaign->campaign_id,
                    'Campaign Name' => $k->campaign->campaign_name,
                    'Ticket Submitted' => $k->createdAt->format("Y-m-d H:i:s"),
                    'Mobile Number' => $k->user_detail->settlement_account_number,
                    //'Location' => "No info available yet",
                    'Ticket Status' => $k->approval_status,
                    'Branch' => "{$k->branch->name} {$k->branch->address} {$k->branch->city}",
                    'Task Name' => $details->task_question->task_name,
                    'Question' => $details->task_question->question,
                    'Answer' => $details->response,
                    'Reward' => $details->task_question->reward_amount
                );

                array_push($csv_data, $base_data);
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
