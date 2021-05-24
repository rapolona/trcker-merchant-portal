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
use App\Services\CampaignService;

class TicketController extends Controller
{

    private $capabilityService;

    private $merchantService;

    private $defaultPerPage;

    private $campaignService;

    public function __construct(CapabilityService $capabilityService, 
        CampaignService $campaignService,
        MerchantService $merchantService)
    {
        $this->campaignService = $campaignService;
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
            'awarded' => $request->awarded,
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
            'campaign_id' => $request->campaign_id
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

        $campaigns = $this->campaignService->getAll([]);
        return view('concrete.ticket.ticket', [
            'tickets' => $rows, 
            'pagination' => $pagination, 
            'filter' => $filter,
            'campaigns' => $campaigns->rows
        ]);
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
     * award instance
     *
     * @return redirect
     */
    public function bulk_award_ticket($campaignId, Request $request)
    {
        $data = [
            'campaign_id' => $campaignId
        ];
        $this->merchantService->bulkAwardTicket($data);

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
        $data = [
            'campaign_id' => $request->campaign_id
        ];

        $csv = $this->merchantService->exportCsv($data);

        print_r( $csv->s3_csv_url ) ;
    }



}
