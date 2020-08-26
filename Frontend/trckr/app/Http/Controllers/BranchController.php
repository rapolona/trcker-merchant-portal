<?php

namespace App\Http\Controllers;

Use App\User;
use App\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;   
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Validator,Redirect,File;
use Config, Session;

class BranchController extends Controller
{
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

                $branches[] = array(
                    $header[0] => $temp[0], 
                    $header[1] => $temp[1],
                    $header[2] => $temp[2],
                    $header[3] => $temp[3],
                    $header[4] => $temp[4]
                );
            }

            $api_endpoint = Config::get('trckr.backend_url') . "merchant/branch";
            $session = $request->session()->get('session_merchant');
            $token = $session->token;
            
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
                        "message" => "Failed Adding Branch {$count} with error: [{$response->status()}] {$response->body()}",
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

    public function branch(Request $request)
    {
        //api call for bramches
        //http://localhost:6001/merchant/branches

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/branches";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;

        $response = Http::withToken($token)->get($api_endpoint, []);
        
        if ($response->status() !== 200)
        {
            if ($response->status() === 403) {
                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");
            
                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();      
            }

            //provide handling for failed branch retrieval
            return redirect('/dashboard');
        }

        $branches = json_decode($response);

        $count = 0;
        foreach ($branches as &$b)
        {
            $b->no = $count+1;
            $count+=1;
        } 
        
        return view('merchant.branches', ['branches' => $branches]);
    }

    public function add_branch_get()
    {
        return view('branch.add', []);
    }

    public function add_branch_post(Request $request)
    {
        $data = (array) $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:64',
            'address' => 'required|max:64',
            'city' => 'required|max:64',
            'longitude' => 'required',
            'latitude' => 'required'
        ]);

        if ($validator->fails())
        {
            $error_string = "<b>Fields with Errors</b><br/>";
            foreach ($validator->errors()->messages() as $k => $v)
            {
                $error_string .= "{$k}: <br/>";
                foreach ($v as $l)
                    $error_string .= "{$l}<br/>";
            }

            return Response()->json([
                "success" => false,
                "message" => $error_string,
                "file" => $data,
            ], 422);
        }

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/branch";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;

        $response = Http::withToken($token)->post($api_endpoint, $data);
        
        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile modification
            return Response()->json([
                "success" => false,
                "message" => "Failed to Add Branch with error:" . $response->body(),
                "file" => $data,
            ], 422);
        }

        return Response()->json([
            "success" => true,
            "message" => "Add Branch successfully saved!", // . $response->body(),
            "file" => $data,
        ]);
    }

    public function edit_branch_get(Request $request)
    {
        $branch_id = $request->query('branch_id');

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/branch";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;

        $response = Http::withToken($token)->get($api_endpoint, []);
        
        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile retrieval
            return redirect('/dashboard');
        }

        $branches = json_decode($response->body());

        $edit_branch = array();
        foreach($branches as $b)
        {
            if ($b->branch_id == $branch_id) {
                $edit_branch = $b;
            }
        }

        return view('branch.edit', ['branch' => $edit_branch, 'branch_id' => $branch_id]);
    }

    public function edit_branch_post(Request $request)
    {
        $branch_id = $request->query('branch_id');

        $data = (array) $request->all();

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required|max:64',
            'address' => 'required|max:64',
            'city' => 'required|max:64',
            'longitude' => 'required',
            'latitude' => 'required'
        ]);

        if ($validator->fails())
        {
            $error_string = "<b>Fields with Errors</b><br/>";
            foreach ($validator->errors()->messages() as $k => $v)
            {
                $error_string .= "{$k}: <br/>";
                foreach ($v as $l)
                    $error_string .= "{$l}<br/>";
            }

            return Response()->json([
                "success" => false,
                "message" => $error_string,
                "file" => $data,
            ], 422);
        }

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/branch";

        $session = $request->session()->get('session_merchant');
        $token = $session->token;

        $response = Http::withToken($token)->put($api_endpoint, $data);
        
        if ($response->status() !== 200)
        {
            //provide handling for failed Edit branch
            return Response()->json([
                "success" => false,
                "message" => "Failed to Edit Branch with error:" . $response->body(),
                "file" => $data,
            ], 422);
        }

        return Response()->json([
            "success" => true,
            "message" => "Edit Branch successfully saved!" . $response->body(),
            "file" => $data,
        ]);
    }

    public function delete_branch(Request $request)
    {
        $data = (array) $request->all();

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/branch";
        $session = $request->session()->get('session_merchant');
        $token = $session->token;
        
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
                    "message" => "Failed Deleting Branch {$count} with error: [{$response->status()}] {$response->body()}",
                    "file" => json_encode($response),
                    "data" => json_encode($b)
                ], 422);
            }
            $count+=1;
        }

        return Response()->json([
            "success" => true,
            "message" => "Deleted Branches successfully", //. $response->body(),
            "file" => $data['branches']
        ]);
    }
}
