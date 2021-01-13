<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator,Redirect,File;
use Config, Session;
use App\Services\BranchService;

class BranchController extends Controller
{
    private $branchService;

    private $defaultPerPage;

    private $fieldValidation = [
        'name' => 'required|max:64',
        'business_type' => 'required|max:64',
        'store_type' => 'required|max:64',
        'brand' => 'required|max:64',
        'region' => 'required|max:64',
        'province' => 'required|max:64',
        'address' => 'required|max:64',
        'city' => 'required|max:64',
        'longitude' => 'required',
        'latitude' => 'required'
    ];

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
        $this->defaultPerPage = Config::get('trckr.table_pagination');
    }

    public function upload_branch(Request $request)
    {
        request()->validate([
            'file'  => 'required|mimes:csv,txt|max:2048',
        ]);

        if ($files = $request->file('file')) {
            //store file
            $file = $request->file->store('public/documents');
            $path = $request->file('file')->getPathName();
            //get stored file
            $content = File::get($path);
            //parse
            $content = explode(PHP_EOL, $content);

            //header
            $header = explode(";", $content[0]);

           // print_r($content); exit();

            $default_headers = ['name','business_type', 'store_type', 'brand', 'region', 'province', 'address','city', 'longitude', 'latitude'];

            // VALIDATE HEADERS
            foreach($header as $h)
            {
                if ( ! in_array(trim($h), $default_headers)){
                    $msg = [
                        "type" => "warning",
                        "message" => "CSV Invalid Header {$h}",
                    ];
                    return redirect('m/branch')->with(['formMessage' => $msg]);
                }
            }
            unset($content[0]);

            //branches
            $branches = array();
            $count = 1;
            foreach ($content as $c) {
                if(!empty(trim($c))) {


                    $temp = explode(";", trim($c));

                    if (count($temp) != count($default_headers)) {

                        $msg = [
                            "type" => "warning",
                            "message" => "Invalid Input on Item {$count}",
                        ];
                        return redirect('m/branch')->with(['formMessage' => $msg]);
                    }

                    //'name','business_type', 'store_type', 'brand', 'region', 'province', 'address','city', 'longitude', 'latitude'
                    $temp_branches = array(
                        $header[0] => $temp[0],
                        $header[1] => $temp[1],
                        $header[2] => $temp[2],
                        $header[3] => $temp[3],
                        $header[4] => $temp[4],
                        $header[5] => $temp[5],
                        $header[6] => $temp[6],
                        $header[7] => $temp[7],
                        $header[8] => $temp[8],
                        trim($header[9]) => trim($temp[9])
                    );

                    // print_r($temp_branches); exit();

                    $validator = Validator::make($temp_branches, $this->fieldValidation);

                    if ($validator->fails()) {
                        $error_string = "Row {$count}: Fields with Errors";
                        foreach ($validator->errors()->messages() as $k => $v) {
                            $error_string .= "{$k}: ";
                            foreach ($v as $l)
                                $error_string .= "{$l}";
                        }

                        $msg = [
                            "type" => "warning",
                            "message" => "Failed to Upload the file, please check your csv file, {$error_string}",
                        ];
                        return redirect('m/branch')->with(['formMessage' => $msg]);
                    }

                    $branches[] = $temp_branches;
                }
                $count+=1;

            }

            $api_endpoint = Config::get('trckr.backend_url') . "merchant/branch";
            $session = $request->session()->get('session_merchant');
            $token = ( ! empty($session->token)) ? $session->token : "";

            $count = 1;
            $debug = array();
            foreach($branches as $b)
            {
                $response = Http::withToken($token)->post($api_endpoint, $b);
                $debug[] = $response;


                if ($response->status() !== 200)
                {
                    $msg = [
                        "type" => "warning",
                        "message" => "Failed Adding Branch ",
                    ];
                    return redirect('m/branch')->with(['formMessage' => $msg]);
                }
                $count+=1;
            }

            $msg = [
                "type" => "success",
                "message" => "Uploaded file successfully",
            ];
            return redirect('m/branch')->with(['formMessage' => $msg]);
        }

        $msg = [
            "type" => "danger",
            "message" => "Failed to Upload the file",
        ];
        return redirect('m/branch')->with(['formMessage' => $msg]);
    }

    /**
     * List controller instance
     *
     * @return View
     */
    public function branch(Request $request)
    {
        $data = (array) $request->all();
        $branches = $this->branchService->getAll($data);
        $filters = $this->branchService->getFilters();
        return view('concrete.merchant.branches', ['branches' => $branches, 'filters' => $filters, 'selectedFilter' => $data]);
    }

    /**
     * Add form view
     *
     * @return View
     */
    public function add_branch_get()
    {
        $filters = $this->branchService->getFilters();
        return view('concrete.branch.add', ['filters' => $filters]);
    }

    /**
     * Add form POSt
     *
     * @return View
     */
    public function add_branch_post(Request $request)
    {
        $data = (array) $request->all();

        $validator = Validator::make($request->all(), [
            'province' => 'required',
            'region' => 'required',
            'brand' => 'required',
            'store_type' => 'required',
            'business_type' => 'required',
            'name' => 'required|max:64',
            'address' => 'required|max:64',
            'city' => 'required|max:64',
            'longitude' => array('required','regex:/^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$/'),
            'latitude' => array('required','regex:/^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$/'),
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $response = $this->branchService->create($data);

        $msg = [
            "success" => true,
            "type" => "success",
            "message" => "Add branch successful!",
        ];

        $filters = $this->branchService->getFilters();
        return view('concrete.branch.add', ['formMessage' => $msg, 'filters' => $filters ]);
    }

    /**
     * Edit form get
     *
     * @return View
     */
    public function edit_branch_get($branchId)
    {
        $branch = $this->branchService->get($branchId);
        $filters = $this->branchService->getFilters();
        return view('concrete.branch.edit', ['branch' => $branch, 'branch_id' => $branchId, 'filters' => $filters]);
    }

    public function edit_branch_post($branchId, Request $request)
    {
        $data = (array) $request->all();

        $validator = Validator::make($request->all(), [
            'province' => 'required',
            'region' => 'required',
            'brand' => 'required',
            'store_type' => 'required',
            'business_type' => 'required',
            'branch_id' => 'required',
            'name' => 'required|max:64',
            'address' => 'required|max:64',
            'city' => 'required|max:64',
            'longitude' => array('required','regex:/^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$/'),
            'latitude' => array('required','regex:/^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$/'),
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $response = $this->branchService->update($data);

        $msg = [
            "success" => true,
            "type" => "success",
            "message" => "Branch was successfully modified!",
        ];

        $branch = $this->branchService->get($branchId);
        $filters = $this->branchService->getFilters();
        return view('concrete.branch.edit', ['formMessage' => $msg,
            'branch' => $branch,
            'branch_id' => $branchId,
            'filters' => $filters ]);
    }

    public function delete_branch($branchId)
    {
        $data = [
            "branch_id" => $branchId
        ];

        $response = $this->branchService->delete($data);

        $msg = [
            "success" => true,
            "type" => "success",
            "message" => "Branch was successfully deleted!",
        ];

        return redirect('/m/branch')
            ->with("formMessage", $msg);
    }

    /**
     * Delete Multiple Product
     *
     * @return Redirect
     */
    public function bulkDelete(Request $request)
    {

        $ids = json_decode($request->delete_ids);

        foreach($ids as $branch_id){
            $data = [
                "branch_id" => $branch_id
            ];

            $response = $this->branchService->delete($data);
        }

        $msg = [
            "success" => true,
            "type" => "success",
            "message" => "Branches were successfully deleted!",
        ];

        return redirect('/m/branch')
            ->with("formMessage", $msg);
    }
}
