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
use DateTime;

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
            $start_date = new DateTime($k->start_date);
            $start_date = $start_date->format("F d, Y");
            $end_date = new DateTime($k->end_date);
            $end_date = $end_date->format("F d, Y");
            $campaigns[] = (object) [
                'no' => $count+1,
                'campaign_id' => $k->campaign_id,
                'campaign_name' => ( ! empty($k->campaign_name)) ? $k->campaign_name : "{$k->campaign_id}-No Campaign Name",
                'budget' => ( ! empty($k->budget)) ? $k->budget : "{$k->campaign_id}-No Budget",
                'duration' => "{$start_date} to {$end_date}",
                'status' => ($k->status) ? "Ongoing" : "Completed"
            ];
            $count+=1;
        }

        return view('concrete.campaign.campaign', ['campaigns' => (object) $campaigns]);
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

        //get all campaigns
        $campaign_id = $request->input('campaign_id');

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/campaign";

        $session = $request->session()->get('session_merchant');

        if ( ! $session) return redirect('/');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $response = Http::withToken($token)->get($api_endpoint);

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

        //get the specific campaign for based on campaign id
        $campaign = array();
        foreach ($response as &$k) {
            if ($k->campaign_id == $campaign_id) $campaign = $k;
            $start_date = new DateTime($k->start_date);
            $k->start_date = $start_date->format("F d, Y");
            $end_date = new DateTime($k->end_date);
            $k->end_date = $end_date->format("F d, Y");
        }

        //getting specific campaign detail
        $api_endpoint = Config::get('trckr.capability_url') . "capability/campaigndetail?campaign_id=$campaign_id";

        $response = Http::withToken($token)->get($api_endpoint);

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

        $campaign_detail = json_decode($response->body());

        return view('concrete.campaign.view', ['campaign' => $campaign, 'campaign_detail' => $campaign_detail]);
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

        $task_type = json_decode($response->body());

        $campaign_type = (object) array(
            (object) array(
                'campaign_type_id' => 1,
                'name' => "Merchandising"
            ),
            (object) array(
                'campaign_type_id' => 2,
                'name' => "Mystery Shopper"
            ),
            (object) array(
                'campaign_type_id' => 3,
                'name' => "Shopper Insignting"
            ),
        );
        //var_dump($campaign_type);exit;

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
        /*
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
        */

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

        return view('concrete.campaign.create', ['campaign_type' => $campaign_type, 'branches' => $branches, 'task_type' => $task_type]);//, 'tasks' => $custom_tasks]);
    }

    public function create_campaign(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            "task_actions" => "required",
            "start_date" => "required|date|after_or_equal:today",
            "end_date" => "required|date|after_or_equal:start_date",
            "budget" => "required",
            'campaign_name' => 'required|max:64',
            'campaign_type' => 'required',
            'campaign_description' => 'required|max:64',
            "budget" => "required|lt:1000000000",
            "reward" => "required|lt:budget",
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
            "campaign_type" => $data['campaign_type'],
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
                "message" => "Failed to Create Campaign", // with error:" . $response->body(),
                "file" => $request_data,
            ], 422);
        }

        return Response()->json([
            "success" => true,
            "message" => "Campaign creation successful!", // . $response->body(),
            "file" => $request_data
        ]);
    }

    public function campaign_type(Request $request)
    {
        //api for getting task type ajax, for create campaign
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
            "message" => "Campaign Type Tasks retrieval Succesful!",
            "file" => $filtered_task_action
        ]);
    }

    private function task_type($task_id)
    {
        //api for getting task type non-ajax, for edit campaign
        $api_endpoint = Config::get('trckr.backend_url') . "merchant/task";

        $session = request()->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $response = Http::withToken($token)->get($api_endpoint, []);

        if ($response->status() !== 200)
        {
            if ($response->status() === 403) {
                $validator = Validator::make(request()->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");

                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
            }

            if ($response->status() === 500) {
                $handler = json_decode($response->body());

                if ($handler->message->name == "JsonWebTokenError")

                $validator = Validator::make(request()->all(), []);
                $validator->getMessageBag()->add('email', "Session Expired. Please login again. {$response->body()}");

                return redirect('/')
                    ->withErrors($validator)
                    ->withInput();
            }

            //general handling
            return redirect('/dashboard');
        }

        $task_actions = json_decode($response->body());

        $filtered_task_action = array();
        foreach($task_actions as $k)
        {
            if( $k->task_classification_id == $task_id)
            {
                $filtered_task_action[] = $k;

            }
        }

        return Response()->json([
            "success" => true,
            "message" => "Campaign Type Tasks retrieval Succesful!",
            "file" => $filtered_task_action
        ]);
    }

    public function edit(Request $request)
    {
        //api call for campaigns
        //http://localhost:6001/merchant/campaign
        /*
        $api_endpoint = "http://localhost:6001/merchant/campaign";
        $campaigns = Http::withToken($this->$_backend_token)->get($api_endpoint,  []);
        $campaigns = json_decode($campaigns);
        */

        //get all campaigns
        $campaign_id = $request->input('campaign_id');

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/campaign";

        $session = $request->session()->get('session_merchant');

        if ( ! $session) return redirect('/');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $response = Http::withToken($token)->get($api_endpoint);

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

        //get the specific campaign for based on campaign id
        $campaign = array();
        foreach ($response as &$k) {
            if ($k->campaign_id == $campaign_id) $campaign = $k;
            $start_date = new DateTime($k->start_date);
            $k->start_date = $start_date->format("Y-m-d");
            $end_date = new DateTime($k->end_date);
            $k->end_date = $end_date->format("Y-m-d");
        }

        //getting specific campaign detail
        $api_endpoint = Config::get('trckr.capability_url') . "capability/campaigndetail?campaign_id=$campaign_id";

        $response = Http::withToken($token)->get($api_endpoint);

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

        $campaign_detail = json_decode($response->body());

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

        $task_type = json_decode($response->body());

        $campaign_type = (object) array(
            (object) array(
                'campaign_type_id' => 1,
                'name' => "Merchandising"
            ),
            (object) array(
                'campaign_type_id' => 2,
                'name' => "Mystery Shopper"
            ),
            (object) array(
                'campaign_type_id' => 3,
                'name' => "Shopper Insignting"
            ),
        );
        //var_dump($campaign_type);exit;

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

        #echo "<pre>";
        #var_dump($campaign);
        #var_dump($campaign_detail);
        #echo "</pre>";

        //manual population of task questions onload
        $tasks_per_type = array();

        foreach ($campaign_detail->campaign_tasks as $k) {
            $tasks_per_type[$k->task_classification_id] = $this->task_type($k->task_classification_id)->getData();
            $tasks_per_type[$k->task_classification_id] = $tasks_per_type[$k->task_classification_id]->file;
        }

        return view('concrete.campaign.edit', ['campaign' => $campaign, 'campaign_detail' => $campaign_detail, 'tasks_per_type' => $tasks_per_type, 'campaign_type' => $campaign_type, 'branches' => $branches, 'task_type' => $task_type]);
    }

    public function edit_campaign(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            "campaign_id" => "required",
            "task_actions" => "required",
            "start_date" => "required|date|after_or_equal:today",
            "end_date" => "required|date|after_or_equal:start_date",
            "budget" => "required",
            'campaign_name' => 'required|max:64',
            'campaign_type' => 'required',
            'campaign_description' => 'required|max:64',
            "budget" => "required|lt:1000000000",
            "reward" => "required|lt:budget",
            "status" => "",
            "task_type" => "required",
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
            "campaign_id" => $data['campaign_id'],
            "start_date" => $data['start_date'],
            "end_date" => $data['end_date'],
            "budget" => $data['budget'],
            "campaign_name" => $data['campaign_name'],
            "campaign_type" => $data['campaign_type'],
            "campaign_description" => $data['campaign_description'],
            "reward" => array(
                "reward_name" => "Cash Voucher",
                "reward_description" => "Cash Voucher",
                "type" => "VOUCHER",
                "amount" => $data['reward']
            ),
            "status" => 1,
            "task_type" => $data['task_type'],
            //"task_actions" => array(),
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


        $api_endpoint = Config::get('trckr.backend_url') . "merchant/campaign/update";

        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $response = Http::withToken($token)->put($api_endpoint, $request_data);

        if ($response->status() !== 200)
        {
            //provide handling for failed merchant profile modification
            return Response()->json([
                "success" => false,
                "message" => "Failed to Edit Campaign.", // with error:" . $response->body(),
                "file" => $request_data,
            ], 422);
        }

        return Response()->json([
            "success" => true,
            "message" => "Campaign modification successful!", // . $response->body(),
            "file" => $request_data
        ]);
    }


    public function delete_campaign(request $request)
    {
        $data = (array) $request->all();

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/campaign";
        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $count = 1;
        $debug = array();

        $campaigns = explode(",", $data['campaigns']);
        foreach($campaigns as $c) {
            $response = Http::withToken($token)->delete($api_endpoint, ["campaign_id" => $c]);
            $debug[] = $response;

            if ($response->status() !== 200)
            {
                //provide handling for failed merchant profile modification
                return Response()->json([
                    "success" => false,
                    "message" => "Failed Deleting Task {$count}", // with error: [{$response->status()}] {$response->body()}",
                    "file" => json_encode($response),
                    "data" => json_encode($c)
                ], 422);
            }
            $count+=1;
        }

        return Response()->json([
            "success" => true,
            "message" => "Deleted Campaign successful!", //. $response->body(),
            "file" => $data['campaigns']
        ]);
    }
}
