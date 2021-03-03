<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\ImageRequest;
use App\Services\Contracts\ImageServiceContract;
use Intervention\Image\Exception\NotReadableException;

class ImageApiController extends AppBaseController
{
    private ImageServiceContract $imageService;

    public function __construct(ImageServiceContract $imageService)
    {
        $this->imageService = $imageService;
    }

    public function show(ImageRequest $request, string $path)
    {
        try {
            $image = $this->imageService->getImage(
                $path,
                $request->input('width'),
                $request->input('height'),
            );

            return $image->response();
        } catch (NotReadableException $exception) {
            return $this->sendError('File not exists', 404);
        }
    }
}
