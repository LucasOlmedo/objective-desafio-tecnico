<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Http\Resources\AccountResource;
use App\Services\AccountService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Objective Financial API",
 *     description="",
 *     version="1.0.0",
 * )
 */

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * @OA\Get(
     *     path="/api/account",
     *     summary="Get all accounts",
     *     tags={"accounts"},
     *     @OA\Parameter(
     *         name="account_number",
     *         in="query",
     *         description="Account number",
     *         required=false
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Account list",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="account_number", type="integer"),
     *                 @OA\Property(property="balance", type="number", format="float"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Account not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $accountNumber = $request->query('account_number');
        $accounts = $this->accountService->getAllAccounts($accountNumber);
        return AccountResource::collection($accounts);
    }

    /**
     * @OA\Post(
     *     path="/api/account",
     *     summary="Create a new account",
     *     tags={"accounts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="account_number", type="integer"),
     *             @OA\Property(property="balance", type="number", format="float"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Account created",
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
    public function store(AccountRequest $request)
    {
        $account = $this->accountService->createAccount($request->validated());
        return new AccountResource($account);
    }

    /**
     * @OA\Get(
     *     path="/api/account/{id}",
     *     summary="Get account by id",
     *     tags={"accounts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Account id",
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Account",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="account_number", type="integer"),
     *             @OA\Property(property="balance", type="number", format="float"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Account not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function show(int $id)
    {
        $account = $this->accountService->getAccount($id);
        return new AccountResource($account);
    }

    /**
     * @OA\Put(
     *     path="/api/account/{id}",
     *     summary="Update account by id",
     *     tags={"accounts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Account id",
     *         required=true
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="account_number", type="integer"),
     *             @OA\Property(property="balance", type="number", format="float"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Account updated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="account_number", type="integer"),
     *             @OA\Property(property="balance", type="number", format="float"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Account not found"
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
    public function update(AccountRequest $request, string $id)
    {
        $account = $this->accountService->updateAccount($id, $request->validated());
        return new AccountResource($account);
    }

    /**
     * @OA\Delete(
     *     path="/api/account/{id}",
     *     summary="Delete account by id",
     *     tags={"accounts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Account id",
     *         required=true
     *     ),    
     *     @OA\Response(
     *         response=200,
     *         description="Account deleted",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Account not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $this->accountService->deleteAccount($id);
        return response()->json([
            'message' => 'Account deleted successfully',
        ]);
    }
}
