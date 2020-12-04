<?php
namespace App\Repositories;

use Config;

class MerchantRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/";
    }

    public function get()
    {
        try {
            return $this->trackerApi('get', $this->api . 'profile', []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function update($data)
    {
        try {
            return $this->trackerApi('put', $this->api . 'profile', $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function campaign($request)
    {
        try {
            return $this->trackerApi('get', $this->api . 'campaign', []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function login($data)
    {
        try {
            return $this->trackerApi('post', $this->api . 'auth', $data, false);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function logout()
    {
        try {
            return $this->trackerApi('post', $this->api . 'logout', []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function forgotPassword($data)
    {
        try {
            return $this->trackerApi('post', $this->api . 'forgotpassword', $data,false);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function changePassword($data)
    {
        try {
            return $this->trackerApi('post', $this->api . 'changepassword', $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function approveTicket($data)
    {
        try {
            return $this->trackerApi('put', $this->api . 'approve', $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function rejectTicket($data)
    {
        try {
            return $this->trackerApi('put', $this->api . 'reject', $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getAllTickets()
    {
        try {
            return $this->trackerApi('get', $this->api . 'alltickets', []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function getTicketReport()
    {
        try {
            return $this->trackerApi('get', $this->api . 'ticketreport', []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }
}
