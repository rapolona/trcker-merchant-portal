<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\TaskRepository;

class TaskService
{
    protected $repository;

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

    public function getTaskById($data)
    {
        return $this->repository->get($data);
    }

    public function getTaskActionClassification()
    {
        return $this->repository->getTaskActionClassification();
    }

}
