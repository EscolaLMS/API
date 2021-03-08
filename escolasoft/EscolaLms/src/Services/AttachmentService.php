<?php

namespace EscolaLms\Core\Services;

use EscolaLms\Core\Models\Attachment;
use EscolaLms\Core\Repositories\Contracts\AttachmentRepositoryContract;
use EscolaLms\Core\Services\Contracts\AttachmentServiceContract;
use Illuminate\Http\UploadedFile;

class AttachmentService implements AttachmentServiceContract
{
    private AttachmentRepositoryContract $attachmentRepository;

    /**
     * AttachmentService constructor.
     * @param AttachmentRepositoryContract $attachmentRepository
     */
    public function __construct(AttachmentRepositoryContract $attachmentRepository)
    {
        $this->attachmentRepository = $attachmentRepository;
    }

    public function store(UploadedFile $file): Attachment
    {
        return $this->attachmentRepository->store($file);
    }
}
