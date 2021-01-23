<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\TaskRepository;

class TaskService
{
    protected $repository;

    protected $taskType = [
        "TRUE OR FALSE" => "radio-group",
        "FLOAT" => "number",
        "DATETIME" => "date",
        "TEXT" => "text",
        "CURRENCY" => "number",
        "CALCULATED VALUE" => "number",
        "SINGLE SELECT DROPDOWN" => "select",
        "CHECKBOX" => "checkbox-group",
        "IMAGE" => "file",
        "INTEGER" => "number"
    ];

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create($data)
    {
        return $this->repository->create($data);
    }

    public function update($data)
    {
        return $this->repository->update($data);
    }

    public function getTaskByMerchant()
    {
        return $this->repository->getTaskByCurrentMerchant();
    }

    public function getTaskByMerchantWithFilters($data)
    {
        return $this->repository->getTaskByCurrentMerchantWithFilters($data);
    }

    public function getTaskById($data)
    {
        return $this->repository->get($data);
    }

    public function getTaskActionClassification()
    {
        return $this->repository->getTaskActionClassification();
    }

    public function generateFormBuilder($questions)
    {
        //print_r($questions); exit();
        $taskForm = [];
        foreach ($questions as $key => $value) {
            $nQuestion =  [
                'type' => $this->taskType[$value->required_inputs],
                'label' => $value->question,
                'id' => $value->task_question_id,
                'className' => $value->required_inputs,
                'name' => $value->task_question_id//$this->taskType[$value->required_inputs]."-1478701075825",
            ];
            if(count($value->task_question_choices) > 0){
                $myChoice = [];
                foreach ($value->task_question_choices as $keyChoice => $choice) {
                    $myChoice[$keyChoice] = [
                        'label' => $choice->choices_value,
                        'value' => $choice->choices_id
                    ];
                }

                $nQuestion['values'] = $myChoice;
            }

            $taskForm[$key] = $nQuestion;
        }
        return $taskForm;
    }

}
