<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Swagger\H5PSwagger;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\H5PContentResource;
use App\Models\Category;
use App\Repositories\Contracts\CategoriesRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Repositories\Contracts\H5PContentRepositoryContract;

/**
 * Class CategoryController
 * @package App\Http\Controllers\API
 */
class H5PAPIController extends AppBaseController implements H5PSwagger
{
    private H5PContentRepositoryContract $h5pRepository;

    public function __construct(H5PContentRepositoryContract $h5pRepository)
    {
        $this->h5pRepository = $h5pRepository;
    }

    /**
     * Display a listing of the h5p content elements.
     * GET|HEAD /h5ps
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $content = $this->h5pRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return H5PContentResource::collection($content)->response();
    }
}
