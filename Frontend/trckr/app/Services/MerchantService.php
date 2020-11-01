<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\MerchantRepository;

class MerchantService
{
    protected $repository;

    public function __construct(MerchantRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getProfile($request)
    {
        return $this->repository->get($request);
    }

    public function update($request)
    {
        return $this->repository->update($request);
    }

    public function campaign($request)
    {
        return $this->repository->campaign();
    }

    public function login($request)
    {
        return $this->repository->login($request);
    }

    public function logout($request)
    {
        return $this->repository->logout($request);
    }
}
