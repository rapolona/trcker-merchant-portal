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
}
