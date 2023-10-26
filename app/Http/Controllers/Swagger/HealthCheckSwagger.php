<?php

namespace App\Http\Controllers\Swagger;

use Illuminate\Http\JsonResponse;

interface HealthCheckSwagger
{

    /**
     * @OA\Get(
     *     path="/api/health-check",
     *     summary="Check health of the application",
     *     tags={"Api"},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                type="array",
     *                @OA\Items(
     *                    type="object",
     *                    @OA\Property(
     *                        property="db_status",
     *                        type="string",
     *                    ),
     *                    @OA\Property(
     *                        property="redis_status",
     *                        type="string",
     *                    ),
     *                    @OA\Property(
     *                        property="cpu_usage",
     *                        type="string",
     *                    ),
     *                    @OA\Property(
     *                        property="disk",
     *                        type="string",
     *                    ),
     *                ),
     *            )
     *         )
     *      ),
     * )
     *
     */
    public function healthCheck(): JsonResponse;
}
