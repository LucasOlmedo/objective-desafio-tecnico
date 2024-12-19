<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Enums\TransactionTypeEnum;
use App\Exceptions\TransactionNotFoundException;

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
        [$account] = $this->accountService->getAllAccounts($data['account_number']);
        $data['account_id'] = $account->id;

        [$fee, $totalAmount] = $this->calculateFee($data['amount'], $data['type']);
        $data['fee'] = $fee;
        $data['total_amount'] = $totalAmount;

        $this->accountService->deductBalance($account->id, $data['total_amount']);
        return $this->transactionRepository->create($data);
    }

    public function getTransaction(int $id)
    {
        try {
            return $this->transactionRepository->find($id);
        } catch (\Exception $e) {
            throw new TransactionNotFoundException();
        }
    }

    private function calculateFee(float $amount, string $type)
    {
        $fee = $amount * TransactionTypeEnum::FEES[$type];
        return [
            $fee,
            $amount + $fee,
        ];
    }
}
