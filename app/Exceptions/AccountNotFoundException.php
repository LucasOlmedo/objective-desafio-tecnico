<?php

namespace App\Exceptions;

class AccountNotFoundException extends ApiException
{
    public function __construct()
    {
        parent::__construct('Account not found', 404);
    }
}
