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

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
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
            $content = explode("\r\n", $content);

            //header
            $header = explode(";", $content[0]);
            $default_headers = array('name','address','city', 'longitude', 'latitude');
            foreach($header as $h)
            {
                if ( ! in_array($h, $default_headers)){
                    return Response()->json([
                        "success" => false,
                        "message" => "CSV Invalid Header {$h}"
                    ], 422);
                }
            }
            unset($content[0]);

            //branches
            $branches = array();
            $count = 1;
            foreach ($content as $c) {
                $temp = explode(";", $c);

                if ( count($temp) != count($default_headers)){
                    return Response()->json([
                        "success" => false,
                        "message" => "Invalid Input on Item {$count}"
                    ]);
                }

                $temp_branches = array(
                    $header[0] => $temp[0],
                    $header[1] => $temp[1],
                    $header[2] => $temp[2],
                    $header[3] => $temp[3],
                    $header[4] => $temp[4]
                );

                $validator = Validator::make($temp_branches, [
                    'name' => 'required|max:64',
                    'address' => 'required|max:64',
                    'city' => 'required|max:64',
                    'longitude' => array('required','regex:/^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$/'),
                    'latitude' => array('required','regex:/^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$/'),
                ]);

                if ($validator->fails())
                {
                    $error_string = "<b>Row {$count}: Fields with Errors</b><br/>";
                    foreach ($validator->errors()->messages() as $k => $v)
                    {
                        $error_string .= "{$k}: <br/>";
                        foreach ($v as $l)
                            $error_string .= "{$l}<br/>";
                    }

                    return Response()->json([
                        "success" => false,
                        "message" => $error_string,
                        "file" => $temp_branches,
                    ], 422);
                }

                $branches[] = $temp_branches;
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
                    //provide handling for failed merchant profile modification
                    return Response()->json([
                        "success" => false,
                        "message" => "Failed Adding Branch {$count}", //with error: [{$response->status()}] {$response->body()}",
                        "file" => json_encode($response),
                        "data" => json_encode($b)
                    ], 422);
                }
                $count+=1;
            }

            return Response()->json([
                "success" => true,
                "message" => "Uploaded file successfully", // . $response->body(),
                "file" => $branches
            ]);
        }

        return Response()->json([
            "success" => false,
            "message" => "Failed to Upload the file",
            "file" => ''
        ]);
    }

    /**
     * List controller instance
     *
     * @return View
     */
    public function branch(Request $request)
    {
        $branches = $this->branchService->getAll();
        return view('concrete.merchant.branches', ['branches' => $branches]);
    }

    /**
     * Add form view
     *
     * @return View
     */
    public function add_branch_get()
    {
        return view('concrete.branch.add', []);
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

        return view('concrete.branch.add', ['formMessage' => $msg ]);
    }

    /**
     * Edit form get
     *
     * @return View
     */
    public function edit_branch_get($branchId)
    {
        $branch = $this->branchService->get($branchId);
        return view('concrete.branch.edit', ['branch' => $branch, 'branch_id' => $branchId]);
    }

    public function edit_branch_post($branchId, Request $request)
    {
        $data = (array) $request->all();

        $validator = Validator::make($request->all(), [
            'branch_id' => 'required',
            'name' => 'required|max:64',
            'address' => 'required|max:64',
            'city' => 'required|max:64',
            'longitude' => array('required','regex:/^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$/'),
            'latitude' => array('required','regex:/^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$/'),
        ]);

        if ($validator->fails())
        {
            print_r($validator->errors()); exit();
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $response = $this->branchService->update($data);

        $msg = [
            "success" => true,
            "type" => "success",
            "message" => "Branch was successfully modified!",
        ];

        $branch = $this->branchService->get($branchId);
        return view('concrete.branch.edit', ['formMessage' => $msg, 'branch' => $branch, 'branch_id' => $branchId]);
    }

    public function delete_branch($branchId)
    {
        $data = (array) $request->all();

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/branch";
        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $count = 1;
        $debug = array();

        $branches = explode(",", $data['branches']);
        foreach($branches as $b) {
            $response = Http::withToken($token)->delete($api_endpoint, ["branch_id" => $b]);
            $debug[] = $response;

            if ($response->status() !== 200)
            {
                //provide handling for failed merchant profile modification
                return Response()->json([
                    "success" => false,
                    "message" => "Failed Deleting Branch {$count}", //with error: [{$response->status()}] {$response->body()}",
                    "file" => json_encode($response),
                    "data" => json_encode($b)
                ], 422);
            }
            $count+=1;
        }

        return Response()->json([
            "success" => true,
            "message" => "Deleted Branches successful!", //. $response->body(),
            "file" => $data['branches']
        ]);
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

        return redirect('/merchant/branch')
            ->with("formMessage", $msg);
    }
}
