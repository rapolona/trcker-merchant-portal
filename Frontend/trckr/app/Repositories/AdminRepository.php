<?php
namespace App\Repositories;

use Config;

class AdminRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "admin/";
    }

    public function create($data)
    {
        try {
            return $this->trackerApi('post', $this->api . 'create', $data);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }
}
