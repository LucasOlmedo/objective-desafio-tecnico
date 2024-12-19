<?php

namespace App\Repositories;

use App\Models\Account;

class AccountRepository
{
    protected $model;

    public function __construct(Account $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getByAccountNumber(string $accountNumber)
    {
        return $this->model->where('account_number', $accountNumber)->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function update(Account $account, array $data)
    {
        $account->update($data);
        return $account->fresh();
    }

    public function delete(Account $account)
    {
        return $account->delete();
    }
}
