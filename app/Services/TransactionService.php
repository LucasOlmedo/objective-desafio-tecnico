<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use App\Enums\TransactionTypeEnum;

class TransactionService
{
    protected $transactionRepository;
    protected $accountService;

    protected const FEES = [
        TransactionTypeEnum::CREDIT => 0.05,
        TransactionTypeEnum::DEBIT => 0.03,
        TransactionTypeEnum::PIX => 0,
    ];

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
        [$fee, $totalAmount] = $this->calculateFee($data['amount'], $data['type']);
        $data['fee'] = $fee;
        $data['total_amount'] = $totalAmount;
        $this->accountService->deductBalance($account->id, $data['total_amount']);
        return $this->transactionRepository->create($data);
    }

    public function getTransaction(int $id)
    {
        return $this->transactionRepository->find($id);
    }

    private function calculateFee(float $amount, string $type)
    {
        $fee = $amount * self::FEES[$type];
        return [
            $fee,
            $amount + $fee,
        ];
    }
}
