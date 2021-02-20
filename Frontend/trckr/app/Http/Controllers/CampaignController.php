<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use League\HTMLToMarkdown\HtmlConverter;
use Validator,Redirect,File;
use Config, Session;
use DateTime;
use Response;
use App\Services\CampaignService;
use App\Services\TaskService;
use App\Services\BranchService;
use App\Services\CapabilityService;
use Illuminate\Mail\Markdown;

class CampaignController extends Controller
{
    private $campaignService;

    private $taskService;

    private $branchService;

    private $capabilityService;

    private $defaultPerPage;

    public function __construct(
        CampaignService $campaignService,
        TaskService $taskService,
        CapabilityService $capabilityService,
        BranchService $branchService)
    {
        $this->campaignService = $campaignService;
        $this->taskService = $taskService;
        $this->branchService = $branchService;
        $this->capabilityService = $capabilityService;
        $this->defaultPerPage = Config::get('trckr.table_pagination');
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
        $filter = [
            'name' => $request->name,
            'status' => $request->status,
            'daterange' => $request->daterange
        ];

        $dateStart = "";
        $dateEnd = "";

        if(isset($request->daterange) && !empty($request->daterange)){
            $date_range = explode(" - ", $request->daterange);
            $dateStart = DateTime::createFromFormat("m/d/Y" , $date_range[0]);
            $dateStart  = $dateStart ->format('Y-m-d');
            $dateEnd= DateTime::createFromFormat("m/d/Y" , $date_range[1]);
            $dateEnd = $dateEnd->format('Y-m-d');          
        }

        $data = [
            'count_per_page' => isset($request->per_page)? $request->per_page : $this->defaultPerPage,
            'page' => isset($request->page)? $request->page : 1,
            'campaign_name' => $request->name,
            'status' => $request->status,
            'date_range_start' => $dateStart,
            'date_range_end' => $dateEnd
        ];
        
        $campaigns = $this->campaignService->getAll($data);

        $pagination = [
            'per_page' => $data['count_per_page'],
            'current_page' => $campaigns->current_page,
            'total_pages' => $campaigns->total_pages
        ];

        return view('concrete.campaign.campaign', ['campaigns' => $campaigns->rows, 'pagination' => $pagination, 'filter' => $filter ]);
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

    public function getCities()
    {
        $cities = $this->capabilityService->getCities();
        return Response()->json([
            "data" => $cities
        ], 200);
    }

    public function view_campaign($campaignId)
    {
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

        $branches = $this->branchService->getAll([]);
        $branch_filters = $this->branchService->getFilters();
        $campaign = (array) $this->campaignService->get($campaignId);
        $campaign['audience'] = ($campaign['allow_everyone']==1)? 'All' : 'super_shopper';
        $campaign['daterange'] = date('m/d/Y', strtotime($campaign['start_date'])) . " - " . date('m/d/Y', strtotime($campaign['end_date']));
        $campaign['branch_id-nobranch'] = ($campaign['branches'][0]->branch_id == 'fbe9b0cf-5a77-4453-a127-9a8567ff3aa7')? true : false;
        $campaign['branch_id-nobranch_value'] = ($campaign['branches'][0]->branch_id == 'fbe9b0cf-5a77-4453-a127-9a8567ff3aa7')? $campaign['branches'][0]->respondent_count : '';

        // BRANCH ALIGNMENT TO CREATE old()
        $campaign['branch_id'] = [];
        $campaign['submission'] = [];
        $campaign['campaign_id'] = $campaignId;
        foreach ($campaign['branches'] as $bKey =>$branch){
            $campaign['branch_id'][$bKey] = $branch->branch_id;
            $campaign['submission'][$bKey] = $branch->respondent_count;
        }

        $cities = $this->capabilityService->getCities();
        //print_r($campaign); exit();

        return view('concrete.campaign.view', [
            'campaign_type' => $campaign_type,
            'branches' => $branches,
            'branch_filters' => $branch_filters,
            'campaign' => $campaign,
            'cities' => $cities
        ]);
    }

    public function create(Request $request)
    {
        $data = (array) $request->all();

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
        $cities = $this->capabilityService->getCities();

        return view('concrete.campaign.create', ['campaign_type' => $campaign_type, 'branches' => $branches, 'branch_filters' => $branch_filters, 'tasks' => $tasks, 'cities' => $cities]);
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

        //validation on task action classifiations, tasks and rewards
        $temp_task_actions = $data['task_id'];
        $temp_reward= $data['reward_amount'];
        unset($data['task_actions']);
        unset($data['reward']);
        $data['tasks'] = [];
        $data['task_actions'] = array();

        $count = 0;
        foreach($temp_task_actions as $k => $v)
        {
            $data['tasks'][$k]['task_id'] = $temp_task_actions[$k];
            $data['tasks'][$k]['reward_amount'] = $temp_reward[$k];
            $data['tasks'][$k]['mandatory']= ($data['man'][$k] > 0)? 1 : 0;
        }

        $data['audience_cities'] = [];

        if(isset($data['audience_city'])){
            foreach ($data['audience_city'] as $key => $value) {
                $data['audience_cities'][$key] = ['city_id' => $value] ;
            }
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
            "thumbnail_image_base64" => $data['thumbnail_url'],
            "thumbnail_image_name" => "",
            "description_image_url" => "",
            "super_shoppers" => ($data['audience'] == "super_shopper") ? 1 : 0,
            "allow_everyone" => ($data['audience'] == "All") ? 1 : 0,
            "task_type" => $data['campaign_type'],
            "campaign_type" => $data['campaign_type'],
            "branches" => array(),
            "tasks" => $data['tasks'],
            "permanent_campaign" => isset($data['permanent_campaign'])? 1 : 0,
            "audience_age_max" => $data['audience_age_max'],
            "audience_age_min" => $data['audience_age_min'],
            "audience_gender" => $data['audience_gender'],
            "audience_cities" => $data['audience_cities']

        );

        $request_data['campaign_description'] = Markdown::parse($request_data['campaign_description'])->toHtml();

        if ( ! empty($data['thumbnail_url'])){
            $base64_encoded_string = base64_encode(file_get_contents($data['thumbnail_url']));
            $request_data['thumbnail_image_base64'] = 'data:' . $data['thumbnail_url']->getMimeType() . ';base64,' . $base64_encoded_string;
            $extension = explode('/', $data['thumbnail_url']->getMimeType());
            $request_data['thumbnail_image_name'] = date('YmdHis') . '.' . $extension[1];
        }

        if ( ! empty($data["branch_id-nobranch"]) && $data["branch_id-nobranch"] == "on") {
            $request_data["at_home_campaign"] = 1;
            $request_data["at_home_respondent_count"] = $data["nobranch_submissions"];
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
        $cities = $this->capabilityService->getCities();

        return view('concrete.campaign.create', ['formMessage' => $msg, 'campaign_type' => $campaign_type, 'branches' => $branches, 'branch_filters' => $branch_filters, 'tasks' => $tasks, 'cities' => $cities]);
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

        $campaign['audience'] = ($campaign['allow_everyone']==1)? 'All' : 'super_shopper';
        $campaign['daterange'] = date('m/d/Y', strtotime($campaign['start_date'])) . " - " . date('m/d/Y', strtotime($campaign['end_date']));
        $campaign['branch_id-nobranch'] = ($campaign['branches'][0]->branch_id == 'fbe9b0cf-5a77-4453-a127-9a8567ff3aa7')? true : false;
        $campaign['branch_id-nobranch_value'] = ($campaign['branches'][0]->branch_id == 'fbe9b0cf-5a77-4453-a127-9a8567ff3aa7')? $campaign['branches'][0]->respondent_count : '';


        $converter = new HtmlConverter();
        $campaign['campaign_description'] = $converter->convert($campaign['campaign_description']);

        $campaign['audience_age_min'] = $campaign['audience_age_min'] > 0 ? $campaign['audience_age_min'] : '';
        $campaign['audience_age_max'] = $campaign['audience_age_max'] > 500 ? '' : $campaign['audience_age_max'];

        // BRANCH ALIGNMENT TO CREATE old()
        $campaign['branch_id'] = [];
        $campaign['submission'] = [];
        $campaign['campaign_id'] = $campaignId;
        foreach ($campaign['branches'] as $bKey =>$branch){
            $campaign['branch_id'][$bKey] = $branch->branch_id;
            $campaign['submission'][$bKey] = $branch->respondent_count;
        }

        $cities = $this->capabilityService->getCities();
        //print_r($campaign); exit();

        return view('concrete.campaign.edit', [
            'campaign_type' => $campaign_type,
            'branches' => $branches,
            'branch_filters' => $branch_filters,
            'tasks' => $tasks,
            'cities' => $cities,
            'campaign' => $campaign
        ]);


    }

    public function edit_campaign($campaignId, Request $request)
    {
        $data = $request->all();

        $validations = [
            "start_date" => "required|date|before_or_equal:today",
            "end_date" => "required|date|after_or_equal:start_date",
            'campaign_name' => 'required|max:64',
            'campaign_type' => 'required',
            'campaign_description' => 'required',
            "budget" => "required|numeric|lt:1000000000",
            "reward.*" => "required|numeric|lt:budget",
            "task_actions.*" => "required",
            "status" => "",
            "task_type" => "",
            "audience" => "required"
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
        $temp_reward= $data['reward_amount'];
        unset($data['task_actions']);
        unset($data['reward']);
        $data['task_actions'] = array();

        $count = 0;
        foreach($temp_task_actions as $k => $v)
        {
            $data['tasks'][$k]['task_id'] = $temp_task_actions[$k];
            $data['tasks'][$k]['reward_amount'] = $temp_reward[$k];
            $data['tasks'][$k]['mandatory']= ($data['man'][$k] > 0)? 1 : 0;
        }

        $data['audience_cities'] = [];

        if(isset($data['audience_city'])){
            foreach ($data['audience_city'] as $key => $value) {
                $data['audience_cities'][$key] = ['city_id' => $value] ;
            }
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
            "thumbnail_url" => !empty($data['thumbnail_url']) ? $data['thumbnail_url'] : '',
            "description_image_url" => "",
            "super_shoppers" => ($data['audience'] == "super_shopper") ? 1 : 0,
            "allow_everyone" => ($data['audience'] == "All") ? 1 : 0,
            "task_type" => $data['campaign_type'],
            "campaign_type" => $data['campaign_type'],
            "branches" => array(),
            "tasks" => array(),
            "campaign_id" => $data['campaign_id'],
        );

        $request_data['campaign_description'] = Markdown::parse($request_data['campaign_description'])->toHtml();


        if ( ! empty($data['thumbnail_url']))
            $request_data['thumbnail_url'] = 'data:' . $data['thumbnail_url']->getMimeType() . ';base64,' . base64_encode(file_get_contents($data['thumbnail_url']));

        if ( ! empty($data["branch_id-nobranch"]) && $data["branch_id-nobranch"] == "on") {
            $request_data["at_home_campaign"] = 1;
            $request_data["at_home_respondent_count"] = $data["nobranch_submissions"];
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

        $response = $this->campaignService->update($request_data);

        $msg = [
                "type" => "success",
                "message" => "Update Campaign Successful!",
            ];

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

        $branches = $this->branchService->getAll([]);
        $branch_filters = $this->branchService->getFilters();
        $campaign = (array) $this->campaignService->get($campaignId);
        $campaign['audience'] = ($campaign['allow_everyone']==1)? 'All' : 'super_shopper';
        $campaign['daterange'] = date('m/d/Y', strtotime($campaign['start_date'])) . " - " . date('m/d/Y', strtotime($campaign['end_date']));
        $campaign['branch_id-nobranch'] = ($campaign['branches'][0]->branch_id == 'fbe9b0cf-5a77-4453-a127-9a8567ff3aa7')? true : false;
        $campaign['branch_id-nobranch_value'] = ($campaign['branches'][0]->branch_id == 'fbe9b0cf-5a77-4453-a127-9a8567ff3aa7')? $campaign['branches'][0]->respondent_count : '';

        $converter = new HtmlConverter();
        $campaign['campaign_description'] = $converter->convert($campaign['campaign_description']);

        $campaign['audience_age_min'] = $campaign['audience_age_min'] > 0 ? $campaign['audience_age_min'] : '';
        $campaign['audience_age_max'] = $campaign['audience_age_max'] > 500 ? '' : $campaign['audience_age_max'];

        // BRANCH ALIGNMENT TO CREATE old()
        $campaign['branch_id'] = [];
        $campaign['submission'] = [];
        $campaign['campaign_id'] = $campaignId;
        foreach ($campaign['branches'] as $bKey =>$branch){
            $campaign['branch_id'][$bKey] = $branch->branch_id;
            $campaign['submission'][$bKey] = $branch->respondent_count;
        }


        $cities = $this->capabilityService->getCities();

        return view('concrete.campaign.edit', [
            'campaign_type' => $campaign_type,
            'branches' => $branches,
            'branch_filters' => $branch_filters,
            'task_type' => $task_type,
            'tasks' => $tasks,
            'campaign' => $campaign,
            'cities' => $cities,
            'formMessage' => $msg
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
            "message" => $campaign['campaign_name'] . " was successfully " . $status. "d!",
        ];

        return redirect()->back()->with("formMessage", $msg);
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

    public function gallery($id, Request $request)
    {
        $data = [
            'count_per_page' => isset($request->per_page)? $request->per_page : $this->defaultPerPage,
            'page' => isset($request->page)? $request->page : 1,
            'campaign_id' => $id,
        ];
        $gallery = $this->campaignService->getGalleryByCampaign($data);

        $pagination = [
            'per_page' => $data['count_per_page'],
            'current_page' => $gallery->current_page,
            'total_pages' => $gallery->total_pages
        ];

        return view('concrete.layouts.gallery', ['gallery' => $gallery->rows, 'pagination' => $pagination ]);
    }

}
