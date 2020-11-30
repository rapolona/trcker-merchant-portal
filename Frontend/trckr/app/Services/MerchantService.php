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

    public function getProfile()
    {
        return $this->repository->get();
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

    public function logout()
    {
        return $this->repository->logout();
    }

    public function forgotPassword($data)
    {
        return $this->repository->forgotPassword($data);
    }

    public function changePassword($data)
    {
        return $this->repository->changePassword($data);
    }

    public function getAllTickets()
    {
        return $this->repository->getAllTickets();
    }
}
