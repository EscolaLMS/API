<?php

namespace EscolaSoft\EscolaLms\Services\Contracts;

use EscolaSoft\EscolaLms\Models\Attachment;
use Illuminate\Http\UploadedFile;

interface AttachmentServiceContract
{
    public function store(UploadedFile $file): Attachment;
}
