<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator,Redirect,File;
use Config, Session;
use DateTime;
use App\Services\CampaignService;
use App\Services\CapabilityService;
use App\Services\TaskService;
use App\Services\BranchService;

class CampaignController extends Controller
{
    private $campaignService;

    private $capabilityService;

    private $taskService;

    private $branchService;

    public function __construct(
        CampaignService $campaignService,
        CapabilityService $capabilityService,
        TaskService $taskService,
        BranchService $branchService)
    {
        $this->campaignService = $campaignService;
        $this->capabilityService = $capabilityService;
        $this->taskService = $taskService;
        $this->branchService = $branchService;
    }

    public function index()
    {
        $this->view();
    }

    /**
     * List controller instance
     *
     * @return View
     */
    public function view(Request $request)
    {
        $campaigns = $this->campaignService->getAll();
        return view('concrete.campaign.campaign', ['campaigns' => $campaigns]);
    }

    public function merchant_branch(Request $request)
    {
        $data = (array) $request->all();
        unset($data["_"]);
        foreach($data as &$k)
            if ($k == "all") $k = NULL;

        $branches = $this->branchService->getAll($data);

        //$filters = $this->branchService->getFilters();

        $datatables_branches = array();
        foreach ($branches as $b)
        {
            $datatables_branches[] = [
                $b->branch_id,
                $b->name,
                $b->business_type,
                $b->store_type,
                $b->brand,
                $b->address,
                $b->city,
                $b->region,
                $b->branch_id,
            ];
        }
        
        return Response()->json([
            "data" => $datatables_branches
        ], 200);
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
        $data = (array) $request->all();

        $task_type = $this->taskService->getTaskActionClassification();

        $tasks = $this->taskService->getTaskByMerchant();

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

        $branches = $this->branchService->getAll($data);
        $branch_filters = $this->branchService->getFilters();

        foreach ($tasks as &$k)
            $k->task_id = $k->task_classification_id . "|" . $k->task_id;

        return view('concrete.campaign.create', ['campaign_type' => $campaign_type, 'branches' => $branches, 'branch_filters' => $branch_filters, 'task_type' => $task_type, 'tasks' => $tasks]);
    }

    public function create_campaign(Request $request)
    {
        $data = $request->all();

        $validations = [
            "start_date" => "required|date|after_or_equal:today",
            "end_date" => "required|date|after_or_equal:start_date",
            'campaign_name' => 'required|max:64',
            'campaign_type' => 'required',
            'campaign_description' => 'required',
            "budget" => "required|numeric|lt:1000000000",
            "reward.*" => "required|numeric|lt:budget",
            "task_actions.*" => "required",
            "status" => "",
            "task_type" => "",
            //"branches" => "required",
            "audience" => "required",
            "thumbnail_url" => "required|max:100"
        ];

        //validation on daterange
        $date_range = explode(" - ", $data["daterange"]);
        $data["start_date"] = DateTime::createFromFormat("m/d/Y" , $date_range[0]);
        $data["start_date"] = $data["start_date"]->format('Y-m-d');
        $data["end_date"] = DateTime::createFromFormat("m/d/Y" , $date_range[1]);
        $data["end_date"] = $data["end_date"]->format('Y-m-d');

        //validation on submissions
        $data["branches"] = array();
        $total_respondents = 0;
        foreach ($data as $k => $v)
        {
            if (strpos($k, 'branch_id-nobranch') !== false AND $v == "on") {
                $validations["submissions-nobranch"] = "required|numeric";
                $total_respondents += $data["submissions-nobranch"];
            }
            if (strpos($k, 'branch_id') !== false AND $v == "on"){
                $temp = explode("-", $k, 2);
                $validations["submissions-" . $temp[1]] = "required|numeric";
                $data["branches"][] = array(
                    "branch_id" => $temp[1],
                    "respondent_count" => $data["submissions-" . $temp[1]]
                );
                break;
                $total_respondents += $data["submissions-" . $temp[1]];
            }
        }
        
        //if branch is empty and not no branch
        if (count($data['branches']) == 0 AND ! isset($data['branch_id-nobranch'])) {
            $validations["branch_id"] = "required";
        }

        //validation on task action classifiations, tasks and rewards
        $temp_task_actions = $data['task_actions'];
        $temp_task_type = $data['task_type'];
        $temp_reward= $data['reward'];
        unset($data['task_actions']);
        unset($data['task_type']);
        unset($data['reward']);
        $data['task_actions'] = array();

        $count = 0;
        $total_rewards = 0;
        foreach($temp_task_actions as $k)
        {
            if ( ! empty($k)) {
                $temp = explode("|", $k);
                $data['task_actions'][] = $temp[1];
                $data['task_type'][] = $temp[0];
                $data['reward'][] = $temp_reward[$count];
                $total_rewards += $temp_reward[$count];
            }
            else {
                $data['task_actions'][] = NULL;
                $data['task_type'][] = NULL;
                $data['reward'][] = NULL;
            }
            $count+=1;
        }

        $data["rewards_sum"] = $total_respondents * $total_rewards;
        $validations["rewards_sum"] = "lte:budget";

        $validator = Validator::make($data, $validations);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request_data = array(
            "start_date" => $data['start_date'],
            "end_date" => $data['end_date'],
            "budget" => $data['budget'],
            "campaign_name" => $data['campaign_name'],
            "campaign_description" => $data['campaign_description'],
            "thumbnail_url" => 'data:' . $data['thumbnail_url']->getMimeType() . ';base64,' . base64_encode(file_get_contents($data['thumbnail_url'])),
            "description_image_url" => "",
            "super_shoppers" => ($data['audience'] == "super_shopper") ? 1 : 0,
            "allow_everyone" => ($data['audience'] == "All") ? 1 : 0,
            "campaign_type" => $data['campaign_type'],
            "branches" => array(),
            "tasks" => array()
        );

        if ( ! empty($data["branch_id-nobranch"]) AND $data["branch_id-nobranch"] == "on") {
            $request_data["at_home_campaign"] = 1;
            $request_data["at_home_respondent_count"] = $data["submissions-nobranch"];
            $request_data["reward"] = array(
                "reward_name" => "Cash",
                "reward_description" => "Cash reward",
                "type" => "CASH",
                "amount" => $data["rewards_sum"]
            );
            unset($request_data["branches"]);
        }
        else $request_data["branches"] = $data["branches"];

        for($i = 0; $i < count($data['task_actions']); $i++) {
            $request_data['tasks'][$i] = array(
                'task_id' => $data['task_actions'][$i],
                'reward_amount' => $data['reward'][$i]
            );
        }

        $response = $this->campaignService->create($request_data);

        if ( ! empty($response->campaign_id))
            $msg = [
                "success" => true,
                "type" => "success",
                "message" => "Create Campaign Successful!",
            ];
        else
            $msg = [
                "success" => true,
                "type" => "danger",
                "message" => "Create Campaign Failed - {$response->message}",
            ];

        $task_type = $this->taskService->getTaskActionClassification();

        $tasks = $this->taskService->getTaskByMerchant();

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

        $branches = $this->branchService->getAll($request);
        $branch_filters = $this->branchService->getFilters();

        foreach ($tasks as &$k)
            $k->task_id = $k->task_classification_id . "|" . $k->task_id;

        return view('concrete.campaign.create', ['formMessage' => $msg, 'campaign_type' => $campaign_type, 'branches' => $branches, 'branch_filters' => $branch_filters, 'task_type' => $task_type, 'tasks' => $tasks]);
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

    public function edit(Request $request, $campaign_id)
    {
        $data = (array) $request->all();

        $campaign = $this->campaignService->get($campaign_id);
        $campaigns = $this->campaignService->getAll();

        $campaign->campaign_id = $campaign_id;
        
        $campaign->start_date = DateTime::createFromFormat("Y-m-d" , $campaign->start_date);
        $campaign->start_date = $campaign->start_date->format('m/d/Y');
        $campaign->end_date = DateTime::createFromFormat("Y-m-d" , $campaign->end_date);
        $campaign->end_date = $campaign->end_date->format('m/d/Y');
        $campaign->daterange = "{$campaign->start_date} - {$campaign->end_date}";

        $campaign_detail = $this->capabilityService->getCampaignDetails(array("campaign_id" => $campaign_id));

        $task_type = $this->taskService->getTaskActionClassification();

        $tasks = $this->taskService->getTaskByMerchant();

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

        $branches = $this->branchService->getAll(array());

        $campaign->at_home_campaign = 0;
        $campaign->at_home_respondents_count = 0;
        foreach($campaigns as $k) {
            if ($k->campaign_id == $campaign->campaign_id AND $k->at_home_campaign == 1) {
                $campaign->at_home_campaign = 1;
                $campaign->at_home_respondents_count = $k->branches[0]->campaign_branch_association->respondent_count;
            }
        }        

        $branch_filters = $this->branchService->getFilters();

        $tasks_per_type = array();
        //manual population of task questions onload
        foreach ($campaign->tasks as &$k) {
            foreach ($tasks as $j) {
                if ($k->task_id == $j->task_id) {
                    $tasks_per_type[$j->task_classification_id] = $this->task_type($j->task_classification_id)->getData();
                    $tasks_per_type[$j->task_classification_id] = $tasks_per_type[$j->task_classification_id]->file;
                    foreach($tasks_per_type[$j->task_classification_id] as &$i) {
                        $i->task_id = "{$i->task_classification_id}|{$i->task_id}";
                    }
                    $k->task_classification_id = $j->task_classification_id;
                    $k->task_id = "{$k->task_classification_id}|{$k->task_id}";
                }
            }
        }
        
        return view('concrete.campaign.edit', ['campaign' => $campaign, 'campaign_detail' => $campaign_detail, 'tasks_per_type' => $tasks_per_type, 'campaign_type' => $campaign_type, 'branches' => $branches, 'branch_filters' => $branch_filters, 'task_type' => $task_type]);
    }

    public function edit_campaign(Request $request, $campaign_id)
    {
        $data = $request->all();

        $validations = [
            "start_date" => "required|date|after_or_equal:today",
            "end_date" => "required|date|after_or_equal:start_date",
            'campaign_name' => 'required|max:64',
            'campaign_type' => 'required',
            'campaign_description' => 'required',
            "budget" => "required|numeric|lt:1000000000",
            "reward.*" => "required|numeric|lt:budget",
            "task_actions.*" => "required",
            "status" => "",
            "task_type" => "",
            //"branches" => "required",
            "audience" => "required",
            //"thumbnail_url" => "required|max:100"
        ];

        //validation on daterange
        $date_range = explode(" - ", $data["daterange"]);
        $data["start_date"] = DateTime::createFromFormat("m/d/Y" , $date_range[0]);
        $data["start_date"] = $data["start_date"]->format('Y-m-d');
        $data["end_date"] = DateTime::createFromFormat("m/d/Y" , $date_range[1]);
        $data["end_date"] = $data["end_date"]->format('Y-m-d');

        //validation on submissions
        $data["branches"] = array();
        $total_respondents = 0;
        foreach ($data as $k => $v)
        {
            if (strpos($k, 'branch_id-nobranch') !== false AND $v == "on") {
                $validations["submissions-nobranch"] = "required|numeric";
                $total_respondents += $data["submissions-nobranch"];
            }
            if (strpos($k, 'branch_id') !== false AND $v == "on"){
                $temp = explode("-", $k, 2);
                $validations["submissions-" . $temp[1]] = "required|numeric";
                $data["branches"][] = array(
                    "branch_id" => $temp[1],
                    "respondent_count" => $data["submissions-" . $temp[1]]
                );
                $total_respondents += $data["submissions-" . $temp[1]];
            }
        }

        //if branch is empty and not no branch
        if (count($data['branches']) == 0 AND ! isset($data['branch_id-nobranch'])) {
            $validations["branch_id"] = "required";
        }

        //validation on task action classifiations, tasks and rewards
        $temp_task_actions = $data['task_actions'];
        $temp_task_type = $data['task_type'];
        $temp_reward= $data['reward'];
        unset($data['task_actions']);
        unset($data['task_type']);
        unset($data['reward']);
        $data['task_actions'] = array();

        $count = 0;
        $total_rewards = 0;
        foreach($temp_task_actions as $k)
        {
            if ( ! empty($k)) {
                $temp = explode("|", $k);
                $data['task_actions'][] = $temp[1];
                $data['task_type'][] = $temp[0];
                $data['reward'][] = $temp_reward[$count];
                $total_rewards += $temp_reward[$count];
            }
            else {
                $data['task_actions'][] = NULL;
                $data['task_type'][] = NULL;
                $data['reward'][] = NULL;
            }
            $count+=1;
        }

        $validations["rewards"] = "lte:" . $total_respondents * $total_rewards;

        $data["rewards_sum"] = $total_respondents * $total_rewards;
        $validations["rewards_sum"] = "lte:budget";

        $validator = Validator::make($data, $validations);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request_data = array(
            "campaign_id" => $campaign_id,
            "start_date" => $data['start_date'],
            "end_date" => $data['end_date'],
            "budget" => $data['budget'],
            "campaign_name" => $data['campaign_name'],
            "campaign_description" => $data['campaign_description'],
            "description_image_url" => "",
            "super_shoppers" => ($data['audience'] == "super_shopper") ? 1 : 0,
            "allow_everyone" => ($data['audience'] == "All") ? 1 : 0,
            "campaign_type" => $data['campaign_type'],
            "branches" => array(),
            "tasks" => array()
        );

        if ( ! empty($data["thumbnail_url"])) $request_data["thumbnail_url"] = 'data:' . $data['thumbnail_url']->getMimeType() . ';base64,' . base64_encode(file_get_contents($data['thumbnail_url']));

        if ( ! empty($data["branch_id-nobranch"]) AND $data["branch_id-nobranch"] == "on") {
            unset($request_data["branches"]);
            $request_data["at_home_campaign"] = 1;
            $request_data["at_home_respondent_count"] = $data["submissions-nobranch"];
            $request_data["reward"] = array(
                "reward_name" => "Cash",
                "reward_description" => "Cash reward",
                "type" => "CASH",
                "amount" => $data["rewards_sum"]
            );
        }
        else $request_data["branches"] = $data["branches"];

        for($i = 0; $i < count($data['task_actions']); $i++) {
            $request_data['tasks'][$i] = array(
                'task_id' => $data['task_actions'][$i],
                'reward_amount' => $data['reward'][$i]
            );
        }

        $response = $this->campaignService->update($request_data);

        $msg = [
            "success" => true,
            "type" => "success",
            "message" => "Update Campaign Successful!",
        ];

        $task_type = $this->taskService->getTaskActionClassification();

        $tasks = $this->taskService->getTaskByMerchant();

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

        $branches = $this->branchService->getAll($request);
        $branch_filters = $this->branchService->getFilters();
        
        foreach ($tasks as &$k)
            $k->task_id = $k->task_classification_id . "|" . $k->task_id;

        return view('concrete.campaign.create', ['formMessage' => $msg, 'campaign_type' => $campaign_type, 'branches' => $branches, 'branch_filters' => $branch_filters, 'task_type' => $task_type, 'tasks' => $tasks]);
        ////////
/*
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
*/
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

    public function duplicate_campaign($campaignId)
    {
        $campaign = (array) $this->campaignService->get($campaignId);
        $duplicate = $this->campaignService->duplicate($campaign);
        $msg = [
            "type" => "success",
            "message" => $campaign['campaign_name'] . " was successfully duplicated!",
        ];
        return redirect('/campaign/view')
            ->with("formMessage", $msg);
    }

    public function status_campaign($status, $campaignId)
    {
        $campaign = (array) $this->campaignService->get($campaignId);
        $this->campaignService->updateStatus($status, $campaignId);
        $msg = [
            "type" => "success",
            "message" => $campaign['campaign_name'] . " was successfully disabled!",
        ];

        return redirect('/campaign/view')
            ->with("formMessage", $msg);
    }

    /**
     * reject instance
     *
     * @return redirect
     */
    public function bulk_action(Request $request)
    {
        $data = (array) $request->all();
        $status = $data['status'];

        if(!isset($data['campaign_id'])){
            $msg = [
                "type" => "warning",
                "message" => "Pls check an item you want to set into " . $data['status'],
            ];

            return redirect('/campaign/view/')
                ->with("formMessage", $msg);
        }

        foreach($data['campaign_id'] as $id){
            $this->campaignService->updateStatus($status, $id);
        }

        $msg = [
            "type" => "success",
            "message" =>  ucfirst($data['status']) . " Campaigns(s) Success!",
        ];

        return redirect('/campaign/view/')
            ->with("formMessage", $msg);
    }

}
