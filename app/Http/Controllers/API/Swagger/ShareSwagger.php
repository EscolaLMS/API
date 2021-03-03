<?php

namespace App\Http\Controllers\API\Swagger;

use App\Http\Requests\API\ShareRequest;
use Illuminate\Http\JsonResponse;

interface ShareSwagger
{

    /**
     * @OA\Get(
     *      path="/api/share/linkedin",
     *      description="Share to my linkedin account",
     *      tags={"Share"},
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          name="url",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
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
    public function linkedin(ShareRequest $request): JsonResponse;

    /**
     * @OA\Get(
     *      path="/api/share/facebook",
     *      description="Share to my facebook timeline",
     *      tags={"Share"},
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          name="url",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
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
    public function facebook(ShareRequest $request): JsonResponse;

    /**
     * @OA\Get(
     *      path="/api/share/twitter",
     *      description="Share to my twitter timeline",
     *      tags={"Share"},
     *      security={
     *          {"passport": {}},
     *      },
     *      @OA\Parameter(
     *          name="url",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
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
    public function twitter(ShareRequest $request): JsonResponse;
}
