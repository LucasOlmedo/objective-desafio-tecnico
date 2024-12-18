<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        $accounts = $this->accountService->getAllAccounts();
        return response()->json($accounts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $account = $this->accountService->createAccount($request->all());
        return response()->json($account);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $account = $this->accountService->getAccount($id);
        return response()->json($account);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $account = $this->accountService->updateAccount($id, $request->all());
        return response()->json($account);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $account = $this->accountService->deleteAccount($id);
        return response()->json($account);
    }
}
