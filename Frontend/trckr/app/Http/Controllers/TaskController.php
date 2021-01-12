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

    private $defaultPerPage;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
        $this->defaultPerPage = Config::get('trckr.table_pagination');
    }

    public function listAjax(Request $request)
    {
        $data = [
            'count_per_page' => isset($request->per_page)? $request->per_page : $this->defaultPerPage,
            'page' => isset($request->per_page)? $request->page : 1
        ];
        
        $list = $this->taskService->getAll($data);

        $list = [
            'data' => $list->rows,
            'per_page' => $data['count_per_page'],
            'current_page' => $list->current_page,
            'total_pages' => $list->total_pages
        ];

        return Response::json(['data' => $list, 'msg' => 'Success!' ], 200);
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

        $taskForm = $this->taskService->generateFormBuilder($task->task_questions);

        $task_classification = $this->taskService->getTaskActionClassification();

        return view('concrete.task.view', ['task' => $task, 'task_id' => $taskId, 'task_classification' => $task_classification, 'taskForm' => $taskForm]);
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
            'banner_image' => 'required|max:100',
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
        //print_r($task); exit();
        $taskForm = $this->taskService->generateFormBuilder($task->task_questions);
        $task_classification = $this->taskService->getTaskActionClassification();
        return view('concrete.task.edit', ['task' => $task, 'task_id' => $taskId, 'task_classification' => $task_classification, 'taskForm' => $taskForm]);
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
            'form_builder' => 'required|min:3',
            'banner_image' => 'max:100'
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
            $task_question_id = $k->name;
            if (!is_string($task_question_id) || (preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $task_question_id) !== 1)) {
                $task_question_id = '';
            }

            $temp['task_question_id'] = $task_question_id ;
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

        //print_r($request_data); exit();

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
        $data['taskForm'] = $this->taskService->generateFormBuilder($task->task_questions);

        return view('concrete.task.edit', $data);
    }
}
