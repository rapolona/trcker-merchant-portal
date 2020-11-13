<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator,Redirect,File;
use Config, Session;
use DateTime;
use App\Services\CampaignService;
use App\Services\TaskService;
use App\Services\BranchService;

class CampaignController extends Controller
{
    private $campaignService;

    private $taskService;

    private $branchService;

    public function __construct(
        CampaignService $campaignService,
        TaskService $taskService,
        BranchService $branchService)
    {
        $this->campaignService = $campaignService;
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

        echo json_encode((object) array("data" => $datatables_branches));

        /*
        return Response()->json([
            "data" => $datatables_branches
        ], 200);
        */
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

        //print_r($data); exit();

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
            "audience" => "required",
            "thumbnail_url" => "required|url"
        ];

        //validation on daterange
        $date_range = explode(" - ", $data["daterange"]);
        $data["start_date"] = DateTime::createFromFormat("m/d/Y" , $date_range[0]);
        $data["start_date"] = $data["start_date"]->format('Y-m-d');
        $data["end_date"] = DateTime::createFromFormat("m/d/Y" , $date_range[1]);
        $data["end_date"] = $data["end_date"]->format('Y-m-d');

        //validation on submissions
        $data["branches"] = array();

        //validation on task action classifiations, tasks and rewards
        $temp_task_actions = $data['task_id'];
        $temp_task_type = $data['task_type'];
        $temp_reward= $data['reward_amount'];
        unset($data['task_actions']);
        unset($data['task_type']);
        unset($data['reward']);
        $data['task_actions'] = array();

        $count = 0;
        foreach($temp_task_actions as $k => $v)
        {
            $data['task_actions'][] = $temp_task_actions[$k];
            $data['task_type'][] = $temp_task_type[$k];
            $data['reward'][] = $temp_reward[$k];
        }

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
            "thumbnail_url" => $data['thumbnail_url'],
            "description_image_url" => "",
            "super_shoppers" => ($data['audience'] == "super_shopper") ? 1 : 0,
            "allow_everyone" => ($data['audience'] == "All") ? 1 : 0,
            "task_type" => $data['campaign_type'],
            "branches" => array(),
            "tasks" => array()
        );

        if ( ! empty($data["branch_id-nobranch"]) && $data["branch_id-nobranch"] == "on") {
            $request_data["at_home_campaign"] = 1;
            $request_data["at_home_respondent_count"] = $data["nobranch_submissions"];
            $request_data["reward"] = array(
                "reward_name" => "Cash",
                "reward_description" => "Cash reward",
                "type" => "CASH",
                "amount" => array_sum($data["reward"])
            );
        }
        else {
            foreach($data['branch_id'] as $k => $v){
                $request_data['branches'][] = array(
                    'branch_id' => $v,
                    'respondent_count' => $data["submission"][$k]
                );
            }
        }

        for($i = 0; $i < count($data['task_actions']); $i++) {
            $request_data['tasks'][$i] = array(
                'task_id' => $data['task_actions'][$i],
                'reward_amount' => $data['reward'][$i]
            );
        }

        //print_r($request_data); exit();

        $response = $this->campaignService->create($request_data);

        if ( ! empty($response->campaign_id))
            $msg = [
                "type" => "success",
                "message" => "Create Campaign Successful!",
            ];
        else
            $msg = [
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

    public function edit($campaignId, Request $request)
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
        $campaign = (array) $this->campaignService->get($campaignId);

        foreach ($tasks as &$k)
            $k->task_id = $k->task_classification_id . "|" . $k->task_id;

        return view('concrete.campaign.edit', [
            'campaign_type' => $campaign_type,
            'branches' => $branches,
            'branch_filters' => $branch_filters,
            'task_type' => $task_type,
            'tasks' => $tasks,
            'campaign' => $campaign
        ]);
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
