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

    public function create($request)
    {
        return $this->repository->create();
    }

    public function update($request)
    {
        return $this->repository->update($request);
    }
}
