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

class CampaignController extends Controller
{
    public function index()
    {
        $this->view();
    }

    public function view(Request $request)
    {        
        //api call for campaigns
        //http://localhost:6001/merchant/campaign
        /*
        $api_endpoint = "http://localhost:6001/merchant/campaign";
        $campaigns = Http::withToken($this->$_backend_token)->get($api_endpoint,  []);
        $campaigns = json_decode($campaigns);
        */

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/campaign";

        $session = $request->session()->get('session_merchant');
        
        if ( ! $session) return redirect('/');
        $token = ( ! empty($session->token)) ? $session->token : "";

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

            if ($response->status() === 500) {
                $handler = json_decode($response->body());
                
                if ($handler->message->name == "JsonWebTokenError")

                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");
            
                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();      
            }

            //general handling
            return redirect('/dashboard');
        }

        $response = json_decode($response);

        $campaigns = array();
        $count = 0;
        foreach ($response as $k)
        {
            $campaigns[] = (object) [
                'no' => $count+1,
                'campaign_id' => $k->campaign_id,
                'campaign_name' => ( ! empty($k->campaign_name)) ? $k->campaign_name : "{$k->campaign_id}-No Campaign Name",
                'budget' => ( ! empty($k->budget)) ? $k->budget : "{$k->campaign_id}-No Budget",
                'duration' => "{$k->start_date} to {$k->end_date}",
                'status' => ($k->status) ? "Ongoing" : "Completed"
            ];
            $count+=1;
        }

        return view('campaign.campaign', ['campaigns' => (object) $campaigns]);
    }

    public function view_campaign(Request $request)
    {        
        //api call for campaigns
        //http://localhost:6001/merchant/campaign
        /*
        $api_endpoint = "http://localhost:6001/merchant/campaign";
        $campaigns = Http::withToken($this->$_backend_token)->get($api_endpoint,  []);
        $campaigns = json_decode($campaigns);
        */
        $campaign_id = $request->input('campaign_id');

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/campaign";

        $session = $request->session()->get('session_merchant');
        
        if ( ! $session) return redirect('/');
        $token = ( ! empty($session->token)) ? $session->token : "";

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

            if ($response->status() === 500) {
                $handler = json_decode($response->body());
                
                if ($handler->message->name == "JsonWebTokenError")

                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");
            
                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();      
            }

            //general handling
            return redirect('/dashboard');
        }

        $response = json_decode($response);

        $campaign = array();
        foreach ($response as $k) {
            if ($k->campaign_id == $campaign_id) $campaign = $k;
        }

        return view('campaign.view', ['campaign' => $campaign]);
    }

    public function create(Request $request)
    {
        //api for getting campaign_type
        $api_endpoint = Config::get('trckr.backend_url') . "api/task_action_classification";

        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";

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

            if ($response->status() === 500) {
                $handler = json_decode($response->body());
                
                if ($handler->message->name == "JsonWebTokenError")

                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");
            
                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();      
            }

            //general handling
            return redirect('/dashboard');
        }

        $campaign_type = json_decode($response->body());

        //api for getting merchant tasks
        $api_endpoint = Config::get('trckr.backend_url') . "merchant/task";

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

            if ($response->status() === 500) {
                $handler = json_decode($response->body());
                
                if ($handler->message->name == "JsonWebTokenError")

                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");
            
                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();      
            }

            //general handling
            return redirect('/dashboard');
        }

        $tasks = json_decode($response->body());

        #get task_classification_id of custom task
        $custom_task_action_classification_id = "";
        foreach($campaign_type as $k) {

            if ($k->task_classification_id == "custom") {
                $custom_task_action_classification_id = $k->task_classification_id;
                break;   
            }
        }

        $custom_tasks = array();
        foreach($tasks as $k)
            if ($k->task_classification_id == $custom_task_action_classification_id)
                $custom_tasks[] = $k;


        //api for getting branches
        $api_endpoint = Config::get('trckr.backend_url') . "merchant/branches";

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

            if ($response->status() === 500) {
                $handler = json_decode($response->body());
                
                if ($handler->message->name == "JsonWebTokenError")

                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");
            
                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();      
            }

            //general handling
            return redirect('/dashboard');
        }

        $branches = json_decode($response->body());

        return view('campaign.create', ['campaign_type' => $campaign_type, 'branches' => $branches, 'tasks' => $custom_tasks]);
    }

    public function create_campaign(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            "start_date" => "required",
            "end_date" => "required",
            "budget" => "required",
            'campaign_name' => 'required|max:64',
            //'campaign_type' => 'required',
            'campaign_description' => 'required|max:64',
            "budget" => "required",
            "reward" => "required",
            "status" => "",
            "task_type" => "",
            "branches" => "required",
            "audience" => "required"
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

        $request_data = array(
            "start_date" => $data['start_date'],
            "end_date" => $data['end_date'],
            "budget" => $data['budget'],
            "campaign_name" => $data['campaign_name'],
            "campaign_description" => $data['campaign_description'],
            "reward" => array(
                "reward_name" => "Cash Voucher",
                "reward_description" => "Cash Voucher",
                "type" => "VOUCHER",
                "amount" => $data['reward']
            ),
            "status" => 1,
            "task_type" => "",
            "task_actions" => array(),
            "branches" => array(),
            "tasks" => array()
        );
        
        if ($data['audience'][0] == "All") {
            $request_data['allow_everyone'] = 1;
        }

        if ($data['audience'][0] == "Super Shopper") {
            $request_data['super_shopper'] = 1;
        }

        //ensure at least 1 respondent
        $respondents = ceil( $data['budget'] / $data['reward'] / count($data['branches']));

        foreach($data['branches'] as $k) {
            $request_data['branches'][] = array(
                'branch_id' => $k,
                'respondents' => $respondents
            );
        }

        foreach($data['task_actions'] as $k) {
            $request_data['tasks'][] = array(
                'task_id' => $k
            );
        }

        /*
        //set campaign status to ongoing
        $data['status'] = 1;

        //check audience data
        //check tasks data
        $campaign_task_actions = array();
        for ($i = 0; $i < count($data['task_action_id']); $i++)
        {
            $campaign_task_actions[] = array(
                'task_action_id' => $data['task_action_id'][$i],
                'title' => $data['task_action_name'][$i],
                'description' => $data['task_action_description'][$i]
            );
        }
        $data['campaign_task_actions'] = $campaign_task_actions;
        unset($data['task_action_id']);
        unset($data['task_action_name']);
        unset($data['task_action_description']);
        //check branches data

        $branches = array();
        $branches = array('branch_id' => $branches);
        
        for($i = 0; $i < count($data['branches']); $i++) {
            $branches[] = array(
                "branch_id" => $data['branches'][$i]
            );
        }
        */
        //$data['branches'] = $branches;

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/campaign/create";

        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $response = Http::withToken($token)->post($api_endpoint, $request_data);

        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile modification
            return Response()->json([
                "success" => false,
                "message" => "Failed to Create Campaign with error:" . $response->body(),
                "file" => $request_data,
            ], 422);
        }

        return Response()->json([
            "success" => true,
            "message" => "Campaign creation successful!" . $response->body(),
            "file" => $request_data
        ]);
    }

    public function campaign_type(Request $request)
    {
        $task_id = $request->query("task_id");

        //api for getting campaign_type
        $api_endpoint = Config::get('trckr.backend_url') . "merchant/task";

        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";
        
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

            if ($response->status() === 500) {
                $handler = json_decode($response->body());
                
                if ($handler->message->name == "JsonWebTokenError")

                $validator = Validator::make($request->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");
            
                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();      
            }

            //general handling
            return redirect('/dashboard');
        }

        $task_actions = json_decode($response->body());

        $task_config = [
            [
                'data_source' => "PHOTO",
                "data_type" => "Image"
            ],
            [
                "data_source" => "INTEGER",
                "data_type" => "Integer"
            ],
            [
                "data_source" => "TRUE OR FALSE",
                "data_type" => "Boolean"
            ],
            [
                "data_source" => "Single Select Dropdown",
                "data_type" => "Text"
            ],
            [
                "data_source" => "Calculated Value",
                "data_type" => "Percentage"
            ],
            [
                "data_source" => "FLOAT",
                "data_type" => "Currency"
            ],
            [
                "data_source" => "TEXT",
                "data_type" => "Text"
            ],
            [
                "data_source" => "DATEFIELD",
                "data_type" => "Date"
            ],
            [
                "data_source" => "FLOAT",
                "data_type" => "Percentage"
            ]
        ];

        $filtered_task_action = array();
        foreach($task_actions as $k)
        {
            if( $k->task_classification_id == $task_id)
            {
                $filtered_task_action[] = $k;
                /*
                foreach($task_config as $tc) {
                    if ($k->data_source == "Single Select Dropdown") {
                        $k->selection = true;
                    }
                }
                */
            }
        }

        return Response()->json([
            "success" => true,
            "message" => "Campaign Type Tasks retrieval Succesfull",
            "file" => $filtered_task_action
        ]);
    }
}
