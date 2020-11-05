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

    public function get($id)
    {
        return $this->repository->get($id);
    }

    public function create($data)
    {
        return $this->repository->create($data);
    }

    public function update($data)
    {
        return $this->repository->update($data);
    }

    public function delete($data)
    {
        return $this->repository->delete($data);
    }
}
