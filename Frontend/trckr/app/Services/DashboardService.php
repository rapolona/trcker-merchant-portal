<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\DashboardRepository;

class DashboardService
{
    protected $repository;

    public function __construct(DashboardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getActiveCampaign()
    {
        return $this->repository->listActiveCampaigns();
    }

    public function getTotalRespondents()
    {
        return $this->repository->getTotalRespondents();
    }

    public function getTotalRespondentsByCampaignAndStatus()
    {
        return $this->repository->getTotalRespondents(['groupby' => 'CAMPAIGN,STATUS']);
    }

    public function getCountCampaignByStatus()
    {
        return $this->repository->countCampaign(['groupby' => 'status']);
    }

    public function getCountCampaignByType()
    {
        return $this->repository->countCampaign(['groupby' => 'campaign_type']);
    }

    public function getCountCampaignByStatusAndType()
    {
        return $this->repository->countCampaign(['groupby' => 'status,campaign_type']);
    }

}
