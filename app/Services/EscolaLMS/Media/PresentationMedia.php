<?php

namespace App\Services\EscolaLMS\Media;

use App\Services\EscolaLMS\Contracts\MediaContract;
use Illuminate\Contracts\Auth\Authenticatable;

class PresentationMedia extends Media implements MediaContract
{
    public function create($content, Authenticatable $user): MediaContract
    {
        // TODO: Implement create() method.
        return $this;
    }
}
