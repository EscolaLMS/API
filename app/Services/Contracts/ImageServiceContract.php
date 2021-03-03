<?php

namespace App\Services\Contracts;

interface ImageServiceContract
{
    public function url(?string $path = null, string $template = 'original'): ?string;
}
