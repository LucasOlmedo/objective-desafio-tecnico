<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @OA\Get(
     *     path="/api/transaction",
     *     summary="Get all transactions",
     *     tags={"transactions"},
     *     @OA\Response(
     *         response=200,
     *         description="Transaction list",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="account_number", type="integer"), 
     *                 @OA\Property(property="type", type="string"),
     *                 @OA\Property(property="formatted_type", type="string"),
     *                 @OA\Property(property="amount", type="number", format="float"),
     *                 @OA\Property(property="fee", type="number", format="float"),
     *                 @OA\Property(property="total_amount", type="number", format="float"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $transactions = $this->transactionService->getAllTransactions();
        return TransactionResource::collection($transactions);
    }

    /**
     * @OA\Post(
     *     path="/api/transaction",
     *     summary="Create a new transaction",
     *     tags={"transactions"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="account_number", type="integer"),
     *             @OA\Property(property="type", type="string", enum={"P", "D", "C"}),
     *             @OA\Property(property="amount", type="number", format="float"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="account_number", type="integer"),
     *             @OA\Property(property="balance", type="number", format="float"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )     
     * )
     */
    public function store(TransactionRequest $request)
    {
        $transaction = $this->transactionService->createTransaction($request->validated());
        return new AccountResource($transaction->account);
    }

    /**
     * @OA\Get(
     *     path="/api/transaction/{id}",
     *     summary="Get transaction by id",
     *     tags={"transactions"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Transaction ID",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="account_number", type="integer"),
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="formatted_type", type="string"),
     *             @OA\Property(property="amount", type="number", format="float"),
     *             @OA\Property(property="fee", type="number", format="float"),
     *             @OA\Property(property="total_amount", type="number", format="float"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Transaction not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function show(int $id)
    {
        $transaction = $this->transactionService->getTransaction($id);
        return new TransactionResource($transaction);
    }
}
