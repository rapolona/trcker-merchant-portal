<?php
namespace App\Services;

use Illuminate\Log;
use App\Repositories\ProductRepository;

class ProductService
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return json_decode($this->repository->getAll());
    }

    public function get($productId)
    {
        return json_decode($this->repository->get($productId));
    }

    public function create($data)
    {
        return json_decode($this->repository->create($data));
    }

    public function update($data)
    {
        return json_decode($this->repository->update($data));
    }

    public function delete($data)
    {
        return json_decode($this->repository->delete($data));
    }
}
