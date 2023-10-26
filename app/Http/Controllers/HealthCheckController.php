<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Swagger\HealthCheckSwagger;
use App\Services\Contracts\HealthCheckServiceContract;
use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use Illuminate\Http\JsonResponse;

class HealthCheckController extends EscolaLmsBaseController implements HealthCheckSwagger
{
    public function __construct(private HealthCheckServiceContract $healthCheckService)
    {
    }

    public function healthCheck(): JsonResponse
    {
        return $this->sendResponse($this->healthCheckService->getHealthData());
    }
}
