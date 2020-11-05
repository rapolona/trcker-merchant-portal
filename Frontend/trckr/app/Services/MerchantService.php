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

    public function getProfile($data)
    {
        return $this->repository->get($data);
    }

    public function update($data)
    {
        return $this->repository->update($data);
    }

    public function campaign($data)
    {
        return $this->repository->campaign($data);
    }

    public function login($data)
    {
        return $this->repository->login($data);
    }

    public function logout($data)
    {
        return $this->repository->logout($data);
    }
}
