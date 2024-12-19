<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Repositories\AccountRepository;
use App\Services\AccountService;
use Mockery;
use PHPUnit\Framework\TestCase;

class AccountServiceTest extends TestCase
{
    protected $mockRepository;
    protected AccountService $accountService;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockRepository = Mockery::mock(AccountRepository::class)->makePartial();
        $this->accountService = app(AccountService::class, ['accountRepository' => $this->mockRepository]);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testGetAllAccounts()
    {
        $accounts = [
            [
                'id' => 1,
                'account_number' => 12345,
                'balance' => 100.00
            ],
            [
                'id' => 2,
                'account_number' => 67890,
                'balance' => 200.00
            ],
        ];

        $this->mockRepository->shouldReceive('getAll')
            ->once()
            ->andReturn($accounts);

        $result = $this->accountService->getAllAccounts();

        $this->assertEquals($accounts, $result);
    }

    public function testGetAccountByNumber()
    {
        $account = [
            'id' => 1,
            'account_number' => 12345,
            'balance' => 100.00
        ];

        $this->mockRepository->shouldReceive('getByAccountNumber')
            ->once()
            ->with(12345)
            ->andReturn($account);

        $result = $this->accountService->getAllAccounts(12345);

        $this->assertEquals([$account], $result);
    }

    public function testCreateAccount()
    {
        $data = [
            'account_number' => 12345,
            'balance' => 100.00
        ];

        $this->mockRepository->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn(
                (object)[
                    'id' => 1,
                    'account_number' => 12345,
                    'balance' => 100.00
                ]
            );

        $result = $this->accountService->createAccount($data);

        $this->assertEquals(12345, $result->account_number);
        $this->assertEquals(100.00, $result->balance);
    }

    public function testGetAccount()
    {
        $account = [
            'id' => 1,
            'account_number' => 12345,
            'balance' => 100.00
        ];

        $this->mockRepository->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($account);

        $result = $this->accountService->getAccount(1);

        $this->assertEquals($account, $result);
    }

    public function testUpdateAccount()
    {
        $mockAccount = $this->mockAccountModel();

        $data = [
            'balance' => 200.00
        ];

        $this->mockRepository->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($mockAccount);

        $this->mockRepository->shouldReceive('update')
            ->once()
            ->with($mockAccount, $data)
            ->andReturn(
                (object)[
                    'id' => 1,
                    'account_number' => 12345,
                    'balance' => 200.00
                ]
            );

        $result = $this->accountService->updateAccount(1, $data);

        $this->assertEquals(200.00, $result->balance);
    }

    public function testDeleteAccount()
    {
        $mockAccount = $this->mockAccountModel();

        $this->mockRepository->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn($mockAccount);

        $this->mockRepository->shouldReceive('delete')
            ->once()
            ->with($mockAccount)
            ->andReturn(true);

        $result = $this->accountService->deleteAccount(1);

        $this->assertTrue($result);
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
