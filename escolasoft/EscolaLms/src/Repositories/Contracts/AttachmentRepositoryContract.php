<?php

namespace EscolaLms\Core\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

interface AttachmentRepositoryContract extends BaseRepositoryContract
{
    public function store(UploadedFile $file): Model;
}
