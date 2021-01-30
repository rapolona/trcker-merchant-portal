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

    public function forgotPassword($data, $validate)
    {
        try {
            return $this->trackerApi('post', $this->api . 'forgotpassword', $data, false, $validate);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function changePassword($data, $validate)
    {
        try {
            return $this->trackerApi('put', $this->api . 'changepassword', $data, false, $validate);
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

    public function getAllTickets($data = null)
    {
        try {
            $data = ($data==null) ? [] : $data;
            $uri = http_build_query($data);
            return $this->trackerApi('get', $this->api . 'alltickets?' . $uri , $data);
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

    public function awardTicket($data)
    {
        try {
            return $this->trackerApi('post', $this->api . 'award', $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function nextPrevTicket($data)
    {
        try {
            $data = ($data==null) ? [] : $data;
            $uri = http_build_query($data);
            return $this->trackerApi('get', $this->api . 'nextAndPrev?' . $uri , $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function updateAdminEmail($data)
    {
        try {
            return $this->trackerApi('put', $this->api . 'admindetail', $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

}
