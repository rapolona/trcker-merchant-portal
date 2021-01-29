<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\RespondentService;
use Response, Config, Validator;

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

        //print_r( $user ); exit();
        return view('concrete.respondent.view', ['user' => $user]);
    }

    public function block($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            "reason" => "required"
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = [
            'user_id' => $id,
            'reason' => $request->reason
        ];

        $this->respondentService->block($data);

        $msg = [
            "type" => "success",
            "message" =>  "User was Blocked Successfully!",
        ];

        return redirect('/respondent')->with("formMessage", $msg);
    }

    public function exportRespondentCsvexportList(Request $request)
    {
        
    }


    public function exportList(Request $request)
    {
        $filename = 'hustle-users' . now() . '.csv';
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $filename,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'status' => $request->status,
            'email' => $request->email,
            'settlement_account_number' => $request->mobile
        ];
        
        $users = $this->respondentService->getAll($data);



        $columns = array(
            'Email', 
            'Last Name', 
            'First Name', 
            'Account Level', 
            'Birthday', 
            'Age', 
            'Gender', 
            'Type', 
            'Mobile', 
            'Region', 
            'Province', 
            'City', 
            'Status', 
            'Registration Date');

        $callback = function() use ($users, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($users->rows as $user) {

                //print_r($user); exit();
                fputcsv($file, array(
                    $user->email,
                    $user->last_name,
                    $user->first_name,
                    $user->account_level,
                    date('Y-m-d', strtotime($user->birthday)),
                    $user->age,
                    $user->gender,
                    $user->settlement_account_type,
                    $user->settlement_account_number,
                    isset($user->region)? $user->region : '',
                    isset($user->province)? $user->province : '',
                    isset($user->city)? $user->city : '',
                    $user->status,
                    date('Y-m-d', strtotime($user->createdAt))
                    )
                );
            }
            fclose($file);
        };
        return  Response::stream($callback, 200, $headers)->sendContent();
    }

}