<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\CapabilityRepository;
use App\Repositories\MerchantRepository;

class CapabilityService
{
    protected $repository;

    protected $merchantRepository;

    public function __construct(
        CapabilityRepository $repository,
        MerchantRepository $merchantRepository)
    {
        $this->repository = $repository;
        $this->merchantRepository = $merchantRepository;
    }

    public function createTaskTicket($data)
    {
        return $this->repository->createTaskTicket($data);
    }

    public function getTicket($data)
    {
        return $this->repository->getTicket($data);
    }

    public function approveTicket($data)
    {
        return $this->merchantRepository->approveTicket($data);
    }

    public function rejectTicket($data)
    {
        return $this->merchantRepository->rejectTicket($data);
    }

    public function getTicketsByCampaignId($data)
    {
        return $this->repository->getTicketsByCampaignId($data);
    }

    public function getTicketsByUserId($data)
    {
        return $this->repository->getTicketsByUserId($data);
    }

    public function getCampaigns()
    {
        return $this->repository->getCampaigns();
    }

    public function getCampaignById($data)
    {
        return $this->repository->getCampaignById($data);
    }

    public function getCampaignDetails($data)
    {
        return $this->repository->getCampaignDetails($data);
    }

    public function getCities()
    {
        return $this->repository->getCities();
    }

    public function getProvinces()
    {
        return $this->repository->getProvinces();
    }

    public function getRegions()
    {
        return $this->repository->getRegions();
    }
}

