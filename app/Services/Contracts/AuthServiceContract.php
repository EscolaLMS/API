<?php

namespace App\Services\Contracts;

interface AuthServiceContract
{
    public function forgotPassword(string $email, string $returnUrl): void;

    public function resetPassword(string $email, string $token, string $password): void;
}
