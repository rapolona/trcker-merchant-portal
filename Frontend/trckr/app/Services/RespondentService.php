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

}