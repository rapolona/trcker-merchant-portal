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
        return json_decode($this->repository->get($data));
    }

    public function update($data)
    {
        return json_decode($this->repository->update($data));
    }

    public function campaign($data)
    {
        return json_decode($this->repository->campaign($data));
    }

    public function login($data)
    {
        return json_decode($this->repository->login($data));
    }

    public function logout($data)
    {
        return json_decode($this->repository->logout($data));
    }
}
