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

    /**
     * GET Task
     *
     * @return View
     */
    public function edit_task_post($taskId, Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'task_name' => 'required|max:64',
            'task_description' => 'required|max:255',
            'task_classification_id' => 'required',
            'form_builder' => 'required|min:3'
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request_data = array(
            'task_name' => $data['task_name'],
            'task_description' => $data['task_description'],
            'task_classification_id' => $data['task_classification_id'],
            'task_questions' => array(),
            'task_id' => $data['task_id']
        );

        if ( ! empty($data['banner_image']))
            $request_data['banner_image'] = 'data:' . $data['banner_image']->getMimeType() . ';base64,' . base64_encode(file_get_contents($data['banner_image']));

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

        $this->taskService->update($request_data);

        $task = $this->taskService->getTaskById(['task_id' => $request_data['task_id']]);

        $data = [];
        $data['task_classification'] = $this->taskService->getTaskActionClassification();
        $data['task_config'] = array();
        $msg = [
            'success' => true,
            'type' => "success",
            'message' => "Update Task successful!"
        ];
        $data['formMessage'] = $msg;
        $data['task'] = $task;

        return view('concrete.task.edit', $data);
    }
}
