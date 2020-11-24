<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\CampaignRepository;

class CampaignService
{
    protected $repository;

    public function __construct(CampaignRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function get($id)
    {
        return $this->repository->get($id);
    }

    public function create($data)
    {
        return $this->repository->create($data);
    }

    public function update($data)
    {
        return $this->repository->update($data);
    }

    public function duplicate($campaign)
    {
        unset($campaign['campaign_id']);
        $campaign['start_date'] = date('Y-m-d');
        $campaign['end_date'] = date('Y-m-d');
        $campaign['campaign_name'] = $campaign['campaign_name'] . " copy";
        if(count($campaign['branches']) > 2 || $campaign['branches'][0]->branch_id!='fbe9b0cf-5a77-4453-a127-9a8567ff3aa7' ){
            unset($campaign['at_home_campaign']);
        }
        //print_r($campaign); exit();
        return $this->repository->create($campaign);
    }

    public function updateStatus($status, $campaignId)
    {
        if($status=="enable"){
            return  $this->repository->enableCampaign($campaignId);
        }
        return $this->repository->disableCampaign($campaignId);
    }

}
