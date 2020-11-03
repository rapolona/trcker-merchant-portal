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
        return json_decode($this->repository->listActiveCampaigns());
    }

    public function getTotalRespondents()
    {
        return json_decode($this->repository->getTotalRespondents());
    }
}
