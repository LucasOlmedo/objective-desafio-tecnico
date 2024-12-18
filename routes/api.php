<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::apiResource('account', AccountController::class);

Route::apiResource('transaction', App\Http\Controllers\TransactionController::class)
    ->except(['update', 'destroy']);
