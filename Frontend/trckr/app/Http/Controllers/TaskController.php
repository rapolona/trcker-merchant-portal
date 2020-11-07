<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Validator,Redirect,File;
use Config, Session;
use App\Services\TaskService;

class TaskController extends Controller
{

    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
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
        $tasks = $this->taskService->getTaskByMerchant();
        return view('concrete.task.task', ['tasks' => $tasks]);
    }

    /**
     * View task
     *
     * @return View
     */
    public function view_task($taskId)
    {
        $data = [
            'task_id' => $taskId
        ];

        $task = $this->taskService->getTaskById($data);
        $task_classification = $this->taskService->getTaskActionClassification();

        return view('concrete.task.view', ['task' => $task, 'task_id' => $taskId, 'task_classification' => $task_classification]);
    }

    /**
     * List controller instance
     *
     * @return View
     */
    public function create_task_get(Request $request)
    {
        $data = [];
        $data['task_classification'] = $this->taskService->getTaskActionClassification();
        $data['task_config'] = array();
        return view('concrete.task.create', $data);
    }

    /**
     * List controller instance
     *
     * @return View
     */
    public function create_task_post(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'task_name' => 'required|max:64',
            'task_description' => 'required|max:255',
            //'subject_level' => 'required|max:64',
            'task_classification_id' => 'required',
            'banner_image' => 'required',
            'form_builder' => 'required|min:3'
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request_data = array(
            'task_name' => $data['task_name'],
            'task_description' => $data['task_description'],
            //'subject_level' => $data['subject_level'],
            'task_classification_id' => $data['task_classification_id'],
            'banner_image' => 'data:' . $data['banner_image']->getMimeType() . ';base64,' . base64_encode(file_get_contents($data['banner_image'])),
            'task_questions' => array()
        );

        $temp_task_questions = json_decode($data['form_builder']);

        foreach ($temp_task_questions as $k)
        {
            $temp = array();
            $temp['question'] = $k->label;
            $temp['required_inputs'] = $k->className;

            if (in_array($k->className, array('CHECKBOX', 'SINGLE SELECT DROPDOWN')))
            {
                $temp['task_question_choices'] = array();
                foreach ($k->values as $j)
                {
                    $temp['task_question_choices'][] = array(
                        "choices_value" => $j->label
                    );
                }
            }
            $request_data['task_questions'][] = $temp;
        }

        $this->taskService->create($request_data);

        $data = [];
        $data['task_classification'] = $this->taskService->getTaskActionClassification();
        $data['task_config'] = array();
        $msg = [
            'success' => true,
            'type' => "success",
            'message' => "Add Task successful!"
        ];
        $data['formMessage'] = $msg;

        return view('concrete.task.create', $data);
    }

    public function delete_task(request $request)
    {
        $data = (array) $request->all();

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/task";
        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";

        $count = 1;
        $debug = array();

        $tasks = explode(",", $data['tasks']);
        foreach($tasks as $t) {
            $response = Http::withToken($token)->delete($api_endpoint, ["task_action_id" => $t]);
            $debug[] = $response;

            if ($response->status() !== 200)
            {
                //provide handling for failed merchant profile modification
                return Response()->json([
                    "success" => false,
                    "message" => "Failed Deleting Task {$count}.", // with error: [{$response->status()}] {$response->body()}",
                    "file" => json_encode($response),
                    "data" => json_encode($t)
                ], 422);
            }
            $count+=1;
        }

        return Response()->json([
            "success" => true,
            "message" => "Deleted Tasks successful!", //. $response->body(),
            "file" => $data['tasks']
        ]);
    }

    /**
     * GET Task
     *
     * @return View
     */
    public function edit_task_get($taskId)
    {
        $data = [
            'task_id' => $taskId
        ];

        $task = $this->taskService->getTaskById($data);
        $task_classification = $this->taskService->getTaskActionClassification();
        return view('concrete.task.edit', ['task' => $task, 'task_id' => $taskId, 'task_classification' => $task_classification]);
    }

    public function edit_task_post(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'task_name' => 'required|max:64',
            'task_description' => 'required|max:255',
            //'subject_level' => 'required|max:64',
            'task_classification_id' => 'required',
            //'banner_image' => 'required',
            'form_builder' => 'required|min:3'
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
            'task_name' => $data['task_name'],
            'task_description' => $data['task_description'],
            //'subject_level' => $data['subject_level'],
            'task_classification_id' => $data['task_classification_id'],
            'task_questions' => array(),
            'task_id' => $data['task_id']
        );

        if ( ! empty($data['banner_image'])) $request_data['banner_image'] = 'data:' . $data['banner_image']->getMimeType() . ';base64,' . base64_encode(file_get_contents($data['banner_image']));

        $temp_task_questions = json_decode($data['form_builder']);

        foreach ($temp_task_questions as $k)
        {
            $temp = array();
            $temp['task_question_id'] = $k->name;
            $temp['question'] = $k->label;
            $temp['required_inputs'] = $k->className;
            if (in_array($k->className, array('CHECKBOX', 'SINGLE SELECT DROPDOWN')))
            {
                $temp['task_question_choices'] = array();
                foreach ($k->values as $j)
                {
                    $temp['task_question_choices'][] = array(
                        "choices_value" => $j->label,
                        "choices_id" => $j->value
                    );
                }
            }
            $request_data['task_questions'][] = $temp;
        }

        $api_endpoint = Config::get('trckr.backend_url') . "merchant/task";

        $session = $request->session()->get('session_merchant');
        $token = ( ! empty($session->token)) ? $session->token : "";
        $merchant_id = $session->merchant_id;

        $response = Http::withToken($token)->put($api_endpoint, $request_data);

        if ($response->status() !== 200)
        {
            return Response()->json([
                "success" => false,
                "message" => "Failed to Modify Task.", // with error:" . $response->body(),
                "file" => $request_data,
            ], 422);
        }

        return Response()->json([
            "success" => true,
            "message" => "Task Modification Successful!",// . $response->body(),
            "file" => json_encode($request_data)
        ]);
    }
}
