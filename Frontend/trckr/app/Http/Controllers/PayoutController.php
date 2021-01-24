<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\PayoutService;
use App\Services\RespondentService;
use Response, Config, Validator;

class PayoutController extends Controller
{

    private $payoutService;

    private $respondentService;

    private $defaultPerPage;

    public function __construct(PayoutService $payoutService, RespondentService $respondentService)
    {
        $this->payoutService = $payoutService;
        $this->respondentService = $respondentService;
        $this->defaultPerPage = Config::get('trckr.table_pagination');
    }

    public function getAll(Request $request)
    {
        $filter = [
            'name' => $request->name,
            'status' => $request->status
        ];

        $data = [
            'count_per_page' => isset($request->per_page)? $request->per_page : $this->defaultPerPage,
            'page' => isset($request->page)? $request->page : 1,
            'name' => $request->name,
            'status' => $request->status
        ];
        
        $payouts = $this->payoutService->getAll($data);

        //print_r($payouts); exit();

        $pagination = [
            'per_page' => $data['count_per_page'],
            'current_page' => $payouts->current_page,
            'total_pages' => $payouts->total_pages
        ];

        return view('concrete.payout.list', ['payouts' => $payouts->rows, 'pagination' => $pagination, 'filter' => $filter ]);
    }

    public function get($id, Request $request)
    {
        $data = [
            'user_payout_request_id' => $id
        ];
        $payout = $this->payoutService->get($data);

        $user = $this->respondentService->get($payout->user_id);
        return view('concrete.payout.view', [
            'payout' => $payout,
            'user' => $user
        ]);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            "reference_id" => "required",
            "remarks" => "required"
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'user_payout_request_id' => $id,
            'status' => $request->status,
            'reference_id' => $request->reference_id,
            'remarks' => $request->remarks
        ];

        $this->payoutService->updatePayout($data);

        $msg = [
            "type" => "success",
            "message" =>  "Payout Approve Success!",
        ];

        return redirect('/payout')->with("formMessage", $msg);
    }

    public function exportList(Request $request)
    {
        
    }

}