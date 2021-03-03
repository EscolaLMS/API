<?php

namespace EscolaSoft\EscolaLms\Services;

use EscolaSoft\EscolaLms\Models\Attachment;
use EscolaSoft\EscolaLms\Repositories\Contracts\AttachmentRepositoryContract;
use EscolaSoft\EscolaLms\Services\Contracts\AttachmentServiceContract;
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
