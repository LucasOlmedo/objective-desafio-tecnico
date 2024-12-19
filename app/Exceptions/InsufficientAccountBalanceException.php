<?php

namespace App\Exceptions;

class InsufficientAccountBalanceException extends AccountException
{
    public function __construct()
    {
        parent::__construct('The account balance cannot be negative', 500);
    }
}
