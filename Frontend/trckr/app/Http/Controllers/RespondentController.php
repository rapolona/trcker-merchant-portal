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
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $data = [
            //'first_name' => $request->first_name,
           // 'last_name' => $request->last_name,
           // 'status' => $request->status,
           // 'email' => $request->email,
           // 'settlement_account_number' => $request->mobile
        ];
        
        $list = $this->respondentService->getAll($data);

        print_r($list); exit();

        $columns = array('ReviewID', 'Provider', 'Title', 'Review', 'Location', 'Created', 'Anonymous', 'Escalate', 'Rating', 'Name');

        $callback = function() use ($reviews, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($reviews as $review) {
                fputcsv($file, array($review->reviewID, $review->provider, $review->title, $review->review, $review->location, $review->review_created, $review->anon, $review->escalate, $review->rating, $review->name));
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
}

}