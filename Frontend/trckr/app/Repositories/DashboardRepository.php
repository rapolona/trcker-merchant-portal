<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Log;
use Config;

class DashboardRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/dashboard/";
    }

    public function listActiveCampaigns()
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->get($this->api . 'activecampaign', []));
        } catch(\Exception $e) {
            return false;
        }
    }

    public function getTotalRespondents()
    {
        try {
            return $this->validateResponse(Http::withToken($this->token())->get($this->api . 'totalrespondents', []));
        } catch(\Exception $e) {
            return false;
        }
    }
}
