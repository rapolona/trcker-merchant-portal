<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
                "action" => 1,
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
                "action" => 1,
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
                "action" => 1,
            ) 
        );

        return view('ticket.ticket', ['tickets' => $tickets]);
    }

    //AJAX for Accept Ticket ticket.ticket.blade.php
    public function approve_ticket(Request $request)
    {

    }

    //AJAX for Reject Ticket ticket.ticket.blade.php
    public function reject_ticket(Request $request)
    {
        
    }
}
