<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\RespondentService;
use Response, Config;

class RespondentController extends Controller
{

    private $respondentService;

    private $defaultPerPage;

    public function __construct(RespondentService $respondentService)
    {
        $this->respondentService = $respondentService;
        $this->defaultPerPage = Config::get('trckr.table_pagination');
    }

    public function getAll(Request $request)
    {
        return view('concrete.respondent.list');
    }

     public function get(Request $request)
    {
        return view('concrete.respondent.view');
    }

     public function block(Request $request)
    {
        
    }

     public function exportList(Request $request)
    {
        
    }

     public function exportRespondentCsv(Request $request)
    {
        
    }

}