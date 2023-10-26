<?php

namespace App\Services\Contracts;

interface HealthCheckServiceContract
{
    public function getHealthData(): array;
}
