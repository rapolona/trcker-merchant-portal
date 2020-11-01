<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\DashboardRepository;

class DashboardService
{
    protected $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getActiveCampaign()
    {
        return $this->dashboardRepository->listActiveCampaigns();
    }

    public function getTotalRespondents()
    {
        return $this->dashboardRepository->getTotalRespondents();
    }
}
