<?php
namespace App\Repositories;

use Config;

class CapabilityRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.capability_url');
    }

    public function createTaskTicket($data)
    {
        try {
            return $this->trackerApi('post', $this->api . 'capability/taskticket', $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getTicket($data)
    {
        try {
            $uri = http_build_query($data);
            return $this->trackerApi('get', $this->api . 'capability/tasktickets?' . $uri , $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getTicketsByCampaignId($data)
    {
        try {
            return $this->trackerApi('get', $this->api . 'capability/tasktickets', $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getTicketsByUserId($data)
    {
        try {
            return $this->trackerApi('get', $this->api . 'capability/tasktickets', $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getCampaigns()
    {
        try {
            return $this->trackerApi('get', $this->api . 'capability/campaign', []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getCampaignById($data)
    {
        try {
            return $this->trackerApi('get', $this->api . 'capability/campaign', $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getCampaignDetails($data)
    {
        try {
            $uri = http_build_query($data);
            return $this->trackerApi('get', $this->api . 'capability/campaigndetail?' . $uri, $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getCities()
    {
        try {
            return $this->trackerApi('get', $this->api . 'capability/cityGetAll', []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }
}
