<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class AccountException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'error' => true,
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
