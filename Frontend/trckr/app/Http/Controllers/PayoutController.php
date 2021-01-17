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
        return view('concrete.payout.list');
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