<?php
namespace App\Repositories;

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
            return $this->trackerApi('get', $this->api . 'activecampaign', []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getTotalRespondents()
    {
        try {
            return $this->trackerApi('get', $this->api . 'totalrespondents', []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }
}
