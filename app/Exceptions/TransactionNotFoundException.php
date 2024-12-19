<?php

namespace App\Exceptions;

class TransactionNotFoundException extends ApiException
{
    public function __construct()
    {
        parent::__construct('Transaction not found', 404);
    }
}
