<?php

namespace App\Http\Controllers\API\Swagger;

use App\Http\Requests\API\LogoutRequest;
use Illuminate\Http\JsonResponse;

interface LogoutSwagger
{
    /**
     * @OA\Post(
     *      path="/api/auth/logout",
     *      description="User logout",
     *      tags={"Auth"},
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      )
     *   )
     */
    public function logout(LogoutRequest $request): JsonResponse;
}
