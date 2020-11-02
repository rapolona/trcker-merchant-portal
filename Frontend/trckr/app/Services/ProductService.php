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
        return $this->repository->getAll();
    }

    public function create($data)
    {
        return $this->repository->create($data);
    }

    public function update($request)
    {
        return $this->repository->update($request);
    }

    public function delete($request)
    {
        return $this->repository->delete($request);
    }
}
