<?php

namespace App\Services;

use App\Exceptions\AccountNotFoundException;
use App\Exceptions\InsufficientAccountBalanceException;
use App\Repositories\AccountRepository;

class AccountService
{
    protected $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function getAllAccounts(string $accountNumber = null)
    {
        if ($accountNumber) {
            $account = $this->accountRepository->getByAccountNumber($accountNumber);
            return empty($account) ? throw new AccountNotFoundException() : [$account];
        }
        return $this->accountRepository->getAll();
    }

    public function createAccount(array $data)
    {
        return $this->accountRepository->create($data);
    }

    public function getAccount(int $id)
    {
        try {
            return $this->accountRepository->find($id);
        } catch (\Exception $e) {
            throw new AccountNotFoundException();
        }
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

    public function deductBalance(int $id, float $amount)
    {
        $account = $this->getAccount($id);
        $account->balance -= $amount;
        if ($account->balance < 0) {
            throw new InsufficientAccountBalanceException();
        }
        return $this->accountRepository->update($account, ['balance' => $account->balance]);
    }
}
