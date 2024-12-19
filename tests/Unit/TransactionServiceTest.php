<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Services\AccountService;
use App\Services\TransactionService;
use Mockery;
use PHPUnit\Framework\TestCase;

class TransactionServiceTest extends TestCase
{
    protected $mockTransactionRepository;
    protected $mockAccountService;
    protected $mockAccountRepository;
    protected TransactionService $transactionService;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockTransactionRepository = Mockery::mock(TransactionRepository::class);
        $this->mockAccountRepository = Mockery::mock(AccountRepository::class);

        $this->mockAccountService = Mockery::mock(AccountService::class, [
            'accountRepository' => $this->mockAccountRepository,
        ]);

        $this->transactionService = app(TransactionService::class, [
            'transactionRepository' => $this->mockTransactionRepository,
            'accountService' => $this->mockAccountService,
        ]);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testGetAllTransactions()
    {
        $transactions = [
            [
                'id' => 1,
                'account_number' => 12345,
                'type' => 'D',
                'amount' => 12.99,
                'fee' => 0.39,
                'total_amount' => 13.38,
                'created_at' => '2024-12-19 13:00:00',
            ],
            [
                'id' => 2,
                'account_number' => 12345,
                'type' => 'P',
                'amount' => 12.99,
                'fee' => 0,
                'total_amount' => 12.99,
                'created_at' => '2024-12-19 14:00:00',
            ],
        ];

        $this->mockTransactionRepository->shouldReceive('getAll')
            ->once()
            ->andReturn($transactions);

        $result = $this->transactionService->getAllTransactions();

        $this->assertEquals($transactions, $result);
    }

    public function testGetTransaction()
    {
        $transaction = [
            'id' => 1,
            'account_number' => 12345,
            'type' => 'D',
            'amount' => 12.99,
            'fee' => 0.39,
            'total_amount' => 13.38,
            'created_at' => '2024-12-19 13:00:00',
        ];

        $this->mockTransactionRepository->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($transaction);

        $result = $this->transactionService->getTransaction(1);

        $this->assertEquals($transaction, $result);
    }

    public function testCreateTransaction()
    {
        $mockAccount = $this->mockAccountModel();

        $data = [
            'account_number' => $mockAccount->account_number,
            'type' => 'D',
            'amount' => 10,
        ];

        $this->mockAccountService->shouldReceive('getAllAccounts')
            ->with($data['account_number'])
            ->andReturn([$mockAccount]);

        $this->mockAccountService->shouldReceive('getAccount')
            ->with($mockAccount->id)
            ->andReturn($mockAccount);

        $this->mockAccountService->shouldReceive('deductBalance')
            ->once()
            ->with($mockAccount->id, Mockery::on(function ($arg) use ($data) {
                return abs($arg - $data['amount'] - 0.3) < 0.01;
            }))
            ->andReturn(true);

        $this->mockTransactionRepository->shouldReceive('create')
            ->once()
            ->with([
                'account_number' => $mockAccount->account_number,
                'type' => 'D',
                'amount' => 10,
                'account_id' => $mockAccount->id,
                'fee' => 0.3,
                'total_amount' => 10.3,
            ])
            ->andReturn([
                'id' => 1,
                'account_number' => $mockAccount->account_number,
                'type' => 'D',
                'amount' => 10,
                'fee' => 0.3,
                'total_amount' => 10.3,
                'created_at' => '2024-12-19 13:00:00',
            ]);

        $result = $this->transactionService->createTransaction($data);

        $this->assertEquals([
            'id' => 1,
            'account_number' => $mockAccount->account_number,
            'type' => 'D',
            'amount' => 10,
            'fee' => 0.3,
            'total_amount' => 10.3,
            'created_at' => '2024-12-19 13:00:00',
        ], $result);
    }

    private function mockAccountModel()
    {
        $mock = Mockery::mock(Account::class)->makePartial();
        $mock->id = 1;
        $mock->account_number = 12345;
        $mock->balance = 100.00;
        return $mock;
    }
}
