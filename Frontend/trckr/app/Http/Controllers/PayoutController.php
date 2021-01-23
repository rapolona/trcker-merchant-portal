<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\PayoutService;
use Response, Config;

class PayoutController extends Controller
{

    private $payoutService;

    private $defaultPerPage;

    public function __construct(PayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
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

    public function get(Request $request)
    {
        return view('concrete.payout.view');
    }

    public function update(Request $request)
    {

    }

    public function exportList(Request $request)
    {
        
    }

}