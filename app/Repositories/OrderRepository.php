<?php

namespace App\Repositories;

use App\Order;
use App\interfaces\RepositoryInterface;

class OrderRepository implements RepositoryInterface
{
    protected $model;

    /**
     * PostRepository constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->model = $order;
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
        if (!$order = $this->findOrderById($id)) {
            return null;        
        }
        $this->model->where('id', $id)->update($data);
        return true;
    }

    public function delete($id)
    {
        if (!$this->findOrderById($id)) {
            return null;        
        }
        return $this->model->destroy($id);
    }

    public function find($id)
    {
        if (!$order = $this->findOrderById($id)) {
            return null;        
        }
        return $order;
    }

    private function findOrderById($id)
    {
       return $this->model->query()->find($id);
    }
}