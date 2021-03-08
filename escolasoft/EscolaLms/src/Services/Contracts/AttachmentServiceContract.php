<?php

namespace EscolaLms\Core\Services\Contracts;

use EscolaLms\Core\Models\Attachment;
use Illuminate\Http\UploadedFile;

interface AttachmentServiceContract
{
    public function store(UploadedFile $file): Attachment;
}
