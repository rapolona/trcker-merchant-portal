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
            $this->sessionExpired();
        }
    }

    public function getTicket($data)
    {
        try {
            $uri = http_build_query($data);
            return $this->trackerApi('get', $this->api . 'capability/tasktickets?' . $uri , $data);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }

    public function approveTicket($data)
    {
        try {
            return $this->trackerApi('put', $this->api . 'merchant/approve', $data);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }

    public function rejectTicket($data)
    {
        try {
            return $this->trackerApi('put', $this->api . 'merchant/reject', $data);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }

    public function getTicketsByCampaignId($data)
    {
        try {
            return $this->trackerApi('get', $this->api . 'capability/tasktickets', $data);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }

    public function getTicketsByUserId($data)
    {
        try {
            return $this->trackerApi('get', $this->api . 'capability/tasktickets', $data);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }

    public function getCampaigns()
    {
        try {
            return $this->trackerApi('get', $this->api . 'capability/campaign', []);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }

    public function getCampaignById($data)
    {
        try {
            return $this->trackerApi('get', $this->api . 'capability/campaign', $data);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }

    public function getCampaignDetails($data)
    {
        try {
            return $this->trackerApi('get', $this->api . 'capability/campaigndetail', $data);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }
}
