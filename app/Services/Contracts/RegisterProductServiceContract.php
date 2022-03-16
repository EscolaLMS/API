<?php

namespace App\Services\Contracts;

interface RegisterProductServiceContract
{
    public function registerProductToResource(string $class, int $id): array;
}
