<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Http\Resources\AccountResource;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $accountNumber = $request->query('account_number');
        $accounts = $this->accountService->getAllAccounts($accountNumber);
        return AccountResource::collection($accounts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AccountRequest $request)
    {
        $account = $this->accountService->createAccount($request->validated());
        return new AccountResource($account);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $account = $this->accountService->getAccount($id);
        return new AccountResource($account);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AccountRequest $request, string $id)
    {
        $account = $this->accountService->updateAccount($id, $request->validated());
        return new AccountResource($account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->accountService->deleteAccount($id);
        return response()->json([
            'message' => 'Account deleted successfully',
        ]);
    }
}
