<?php
namespace App\Repositories;

use Config;

class ProductRepository extends Repository
{

    private $api;

    public function __construct()
    {
        $this->api = Config::get('trckr.backend_url') . "merchant/";
    }

    public function getAll()
    {
        try {
            return $this->trackerApi('get', $this->api . 'products', []);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }

    public function get($id)
    {
        try {
            return $this->trackerApi('get', $this->api . 'product/' . $id, []);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }

    public function create($data)
    {
        try {
            return $this->trackerApi('post', $this->api . 'product', $data);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }

    public function update($data)
    {
        try {
            return $this->trackerApi('put', $this->api . 'product', $data);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }

    public function delete($data)
    {
        try {
            return $this->trackerApi('delete', $this->api . 'product', $data);
        } catch(\Exception $e) {
            $this->sessionExpired();
        }
    }
}
