<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\BranchRepository;

class BranchService
{
    protected $repository;

    public function __construct(BranchRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function create($request)
    {
        return $this->repository->create();
    }

    public function update($request)
    {
        return $this->repository->update($request);
    }

    public function delete($request)
    {
        return $this->repository->delete($request);
    }
}
