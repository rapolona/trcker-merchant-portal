<?php
namespace App\Repositories;

use Config;

class PayoutRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/payout_requests/";
    }

    public function getAll($data = null)
    {
        try {
            $data = ($data==null) ? [] : $data;
            $uri = http_build_query($data);
            return $this->trackerApi('get', $this->api . 'listall?' . $uri, $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function get($data)
    {
        try {
            return $this->trackerApi('get', $this->api. 'findone/' . $data['user_payout_request_id'] , $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

    public function award($data)
    {
        try {
            return $this->trackerApi('post', Config::get('trckr.backend_url') . '/merchant/award/' , $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

}