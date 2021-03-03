<?php

namespace App\Services;

use App\Services\Contracts\ImageServiceContract;
use Exception;

class ImageService implements ImageServiceContract
{
    public function url(?string $path = null, string $template = 'original'): ?string
    {
        if (is_null($path)) {
            return asset('backend/assets/images/course_detail.jpg');
        }

        if (!array_key_exists($template, config('imagecache.templates')) && $template !== 'original') {
            throw new Exception("Image template `$template` not exists");
        }

        return url(config('imagecache.route'), [$template, $path]);
    }
}
