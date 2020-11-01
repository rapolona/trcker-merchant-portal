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


    public function create($request)
    {
        return $this->repository->create();
    }

    public function update($request)
    {
        return $this->repository->update($request);
    }

}
