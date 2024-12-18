<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactions = $this->transactionService->getAllTransactions();
        return response()->json($transactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $transaction = $this->transactionService->createTransaction($request->all());
        return response()->json($transaction);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $transaction = $this->transactionService->getTransaction($id);
        return response()->json($transaction);
    }
}
