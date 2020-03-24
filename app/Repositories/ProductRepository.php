<?php

namespace App\Repositories;

use App\Product;
use App\interfaces\RepositoryInterface;

class ProductRepository implements RepositoryInterface
{
    protected $model;

    /**
     * PostRepository constructor.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        if (!$product = $this->findProductById($id)) {
            return null;        
        }
        $this->model->where('id', $id)->update($data);
        return true;
    }

    public function delete($id)
    {
        if (!$this->findProductById($id)) {
            return null;        
        }
        return $this->model->destroy($id);
    }

    public function find($id)
    {
        if (!$product = $this->findProductById($id)) {
            return null;        
        }
        return $product;
    }

    public function findProductBySlug($slug)
    {
       return $this->model->query()->where("slug", $slug)->first();
    }

    private function findProductById($id)
    {
       return $this->model->query()->find($id);
    }
}