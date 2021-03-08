<?php

namespace EscolaLms\Core\Http\Controllers\Swagger;

use EscolaLms\Core\Http\Requests\API\CreateAttachmentRequest;
use Illuminate\Http\JsonResponse;

interface AttachmentAPISwaggerInterface
{

    /**
     * @param CreateAttachmentRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/attachments",
     *      summary="Store a attachment as file in storage",
     *      security={
     *         {"passport": {}}
     *      },
     *      tags={"Attachments"},
     *      description="Store Image",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="file",
     *                      type="string",
     *                      format="binary"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      )
     * )
     */
    public function store(CreateAttachmentRequest $request): JsonResponse;
}
