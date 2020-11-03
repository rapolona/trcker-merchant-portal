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
        return json_decode($this->repository->getAll());
    }

    public function get($id)
    {
        return json_decode($this->repository->get($id));
    }

    public function create($data)
    {
        return json_decode($this->repository->create($data));
    }

    public function update($data)
    {
        return json_decode($this->repository->update($data));
    }

    public function delete($data)
    {
        return json_decode($this->repository->delete($data));
    }
}
