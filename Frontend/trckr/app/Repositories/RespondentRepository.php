<?php
namespace App\Repositories;

use Config;

class RespondentRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/users/";
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

    public function get($id)
    {
        try {
            return $this->trackerApi('get', $this->api . $id, []);
        } catch(\Exception $e) {
            $this->sessionExpired($e);
        }
    }

}