<?php

namespace EscolaLms\Core\Http\Controllers;

use EscolaLms\Core\Http\Controllers\Swagger\AttachmentAPISwaggerInterface;
use EscolaLms\Core\Http\Requests\API\CreateAttachmentRequest;
use EscolaLms\Core\Http\Resources\AttachmentResource;
use EscolaLms\Core\Services\Contracts\AttachmentServiceContract;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class AttachmentAPIController
 * @package App\Http\Controllers\API
 */
class AttachmentAPIController extends EscolaLmsBaseController implements AttachmentAPISwaggerInterface
{
    private AttachmentServiceContract $attachmentService;

    /**
     * AttachmentAPIController constructor.
     * @param AttachmentServiceContract $attachmentService
     */
    public function __construct(AttachmentServiceContract $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function store(CreateAttachmentRequest $request): JsonResponse
    {
        try {
            $attachment = $this->attachmentService->store($request->file('file'));
            return (new AttachmentResource($attachment))->response()->setStatusCode(201);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 400);
        }
    }
}
