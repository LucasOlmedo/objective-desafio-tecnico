<?php

namespace App\Services;

use App\Repositories\AccountRepository;

class AccountService
{
    protected $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function getAllAccounts()
    {
        return $this->accountRepository->getAll();
    }

    public function createAccount(array $data)
    {
        return $this->accountRepository->create($data);
    }

    public function getAccount(int $id)
    {
        return $this->accountRepository->find($id);
    }

    public function updateAccount(int $id, array $data)
    {
        $account = $this->getAccount($id);
        return $this->accountRepository->update($account, $data);
    }

    public function deleteAccount(int $id)
    {
        $account = $this->getAccount($id);
        return $this->accountRepository->delete($account);
    }
}
