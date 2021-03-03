<?php

namespace App\Http\Controllers\API\Swagger;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface H5PSwagger
{
    /**
     * @OA\Get(
     *      tags={"h5p"},
     *      path="/api/h5p",
     *      description="Get h5p content elements",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Bad request",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      )
     *   )
     */
    public function index(Request $request): JsonResponse;
}
