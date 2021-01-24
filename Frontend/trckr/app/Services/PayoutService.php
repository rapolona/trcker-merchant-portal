<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\PayoutRepository;

class PayoutService
{
    protected $repository;

    public function __construct(PayoutRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll($data)
    {
        return $this->repository->getAll($data);
    }

    public function get($data)
    {
        return $this->repository->get($data);
    }

    public function updatePayout($data)
    {
        return $this->repository->updatePayout($data);
    }

}