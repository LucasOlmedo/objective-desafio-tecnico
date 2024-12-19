<?php

namespace App\Exceptions;

class InsufficientAccountBalanceException extends ApiException
{
    public function __construct()
    {
        parent::__construct('The account balance cannot be negative', 500);
    }
}
