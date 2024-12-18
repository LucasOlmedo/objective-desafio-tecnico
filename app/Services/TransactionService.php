<?php

namespace App\Services;

use App\Repositories\TransactionRepository;

class TransactionService
{
    protected $transactionRepository;
    protected $accountService;

    public function __construct(TransactionRepository $transactionRepository, AccountService $accountService)
    {
        $this->transactionRepository = $transactionRepository;
        $this->accountService = $accountService;
    }

    public function getAllTransactions()
    {
        return $this->transactionRepository->getAll();
    }

    public function createTransaction(array $data)
    {
        $account = $this->accountService->getAllAccounts($data['account_number']);
        $data['account_id'] = $account->id;
        return $this->transactionRepository->create($data);
    }

    public function getTransaction(int $id)
    {
        return $this->transactionRepository->find($id);
    }
}
