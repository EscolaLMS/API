<?php

namespace App\Exceptions;

use Exception;

class InsufficientBalanceException extends Exception
{
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'Insufficient balance');
    }
}
