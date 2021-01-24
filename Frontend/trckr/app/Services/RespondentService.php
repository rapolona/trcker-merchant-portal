<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\RespondentRepository;

class RespondentService
{
    protected $repository;

    public function __construct(RespondentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($data)
    {
        return $this->repository->getAll($data);
    }

    public function get($id)
    {
        return $this->repository->get($id);
    }

    public function block($data)
    {
        return $this->repository->block($data);
    }

}