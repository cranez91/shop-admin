<?php

namespace App\Repositories;

use App\User;
use App\interfaces\RepositoryInterface;

class UserRepository implements RepositoryInterface
{
    protected $model;

    /**
     * PostRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
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
        if (!$user = $this->findUserById($id)) {
            return null;        
        }
        $this->model->where('id', $id)->update($data);
        return true;
    }

    public function delete($id)
    {
        if (!$this->findUserById($id)) {
            return null;        
        }
        return $this->model->destroy($id);
    }

    public function find($id)
    {
        if (!$user = $this->findUserById($id)) {
            return null;        
        }
        return $user;
    }

    private function findUserById($id)
    {
       return $this->model->query()->find($id);
    }
}