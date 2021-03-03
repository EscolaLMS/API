<?php

namespace App\Http\Controllers\API\Swagger;

use Illuminate\Http\Request;

interface TagSwagger
{
    /**
     * @OA\Get(
     *      tags={"tags"},
     *      path="/api/tags",
     *      description="Get Tags",
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
    public function index(Request $request);

    /**
     * @OA\Get(
     *      tags={"tags"},
     *      path="/api/tags/unique",
     *      description="Get Unique Tags names",
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
    public function unique(Request $request);
}
