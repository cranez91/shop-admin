<?php

namespace App\Repositories;

use App\ShoppingCart;
use App\interfaces\RepositoryInterface;

class ShoppingCartRepository implements RepositoryInterface
{
    protected $model;

    /**
     * PostRepository constructor.
     *
     * @param ShoppingCart $cart
     */
    public function __construct(ShoppingCart $cart)
    {
        $this->model = $cart;
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
        if (!$cart = $this->findShoppingCartById($id)) {
            return null;        
        }
        $this->model->where('id', $id)->update($data);
        return true;
    }

    public function delete($id)
    {
        if (!$this->findShoppingCartById($id)) {
            return null;        
        }
        return $this->model->destroy($id);
    }

    public function find($id)
    {
        if (!$cart = $this->findShoppingCartById($id)) {
            return null;        
        }
        return $cart;
    }

    private function findShoppingCartById($id)
    {
       return $this->model->query()->find($id);
    }
}