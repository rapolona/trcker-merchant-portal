<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\CapabilityRepository;

class CapabilityService
{
    protected $repository;

    public function __construct(CapabilityRepository $repository)
    {
        $this->repository = $repository;
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
        return $this->repository->approveTicket($data);
    }

    public function rejectTicket($data)
    {
        return $this->repository->rejectTicket($data);
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
}

