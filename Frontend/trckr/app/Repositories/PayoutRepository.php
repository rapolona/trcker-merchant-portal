<?php
namespace App\Repositories;

use Config;

class PayoutRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/campaign/";
    }

    public function getAll($data = null)
    {
        try {
            $data = ($data==null) ? [] : $data;
            $uri = http_build_query($data);
            return $this->trackerApi('get', $this->api . '?' . $uri, $data);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

}