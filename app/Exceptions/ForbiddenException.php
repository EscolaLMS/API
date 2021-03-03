<?php

namespace App\Exceptions;

class ForbiddenException extends \Exception
{
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'Forbidden');
    }
}
