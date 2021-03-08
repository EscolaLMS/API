<?php


namespace EscolaLms\Core\Dtos\Contracts;

use App\Models\Contracts\MediaModelContract;
use App\Services\EscolaLMS\Contracts\MediaContract;

interface InstantiateFromMedia
{
    public static function instantiateFromMedia(MediaContract $media, MediaModelContract $file): self;
}
