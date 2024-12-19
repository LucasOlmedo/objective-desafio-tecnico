<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
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
        return TransactionResource::collection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request)
    {
        $transaction = $this->transactionService->createTransaction($request->validated());
        return new TransactionResource($transaction);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $transaction = $this->transactionService->getTransaction($id);
        return new TransactionResource($transaction);
    }
}
