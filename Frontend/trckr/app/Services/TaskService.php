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
        return json_decode($this->repository->create($data));
    }

    public function update($data)
    {
        return json_decode($this->repository->update($data));
    }

    public function getTaskByMerchant($data)
    {
        return json_decode($this->repository->get($data));
    }

    public function getTaskById($data)
    {
        return json_decode($this->repository->get($data));
    }
}
