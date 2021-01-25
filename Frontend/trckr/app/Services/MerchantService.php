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

    public function forgotPassword($data, $validate)
    {
        return $this->repository->forgotPassword($data, $validate);
    }

    public function changePassword($data)
    {
        return $this->repository->changePassword($data);
    }

    public function getAllTickets($data)
    {
        return $this->repository->getAllTickets($data);
    }

    public function getTicketReport()
    {
        return $this->repository->getTicketReport();
    }
}
