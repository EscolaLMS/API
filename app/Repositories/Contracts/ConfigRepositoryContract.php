<?php

namespace App\Repositories\Contracts;

use App\Models\Config;

interface ConfigRepositoryContract
{
    public function save(string $code, array $options): void;

    public function getOption(string $code, string $key): ?Config;

    public function getOptions(string $code): array;
}
