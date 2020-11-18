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

    public function getTotalRespondents($groupBy=null)
    {
        try {
            $uri = "";
            if($groupBy){
                $uri = "?" . http_build_query($groupBy);
            }
            return $this->trackerApi('get', $this->api . 'totalrespondents' .$uri, []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function countCampaign($groupBy=null)
    {
        try {
            $uri = "";
            if($groupBy){
                $uri = "?" . http_build_query($groupBy);
            }
            return $this->trackerApi('get', $this->api . 'countcampaign' .$uri, []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function respondentPerStatus($groupBy=null)
    {
        try {
            return $this->trackerApi('get', $this->api . 'respondentperstatus', []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }



}
