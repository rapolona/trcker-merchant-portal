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
        $filter = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'status' => $request->status,
            'email' => $request->email,
            'mobile' => $request->mobile
        ];

        $data = [
            'count_per_page' => isset($request->per_page)? $request->per_page : $this->defaultPerPage,
            'page' => isset($request->page)? $request->page : 1,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'status' => $request->status,
            'email' => $request->email,
            'settlement_account_number' => $request->mobile
        ];
        
        $list = $this->respondentService->getAll($data);
        $pagination = [
            'per_page' => $data['count_per_page'],
            'current_page' => $list->current_page,
            'total_pages' => $list->total_pages
        ];

        return view('concrete.respondent.list', ['users' => $list->rows, 'pagination' => $pagination, 'filter' => $filter ]);
    }


    public function get($id, Request $request)
    {
        $user = $this->respondentService->get($id);
        return view('concrete.respondent.view', ['user' => $user]);
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