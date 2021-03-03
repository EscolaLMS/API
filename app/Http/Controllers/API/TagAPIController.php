<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Swagger\TagSwagger;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateTagAPIRequest;
use App\Http\Requests\API\UpdateTagAPIRequest;
use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryContract;
use Illuminate\Http\Request;
use Response;

/**
 * Class TagController
 * @package App\Http\Controllers\API
 */

class TagAPIController extends AppBaseController implements TagSwagger
{
    private TagRepositoryContract $tagRepository;

    public function __construct(TagRepositoryContract $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * Display a listing of the Tag.
     * GET|HEAD /tags
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tags = $this->tagRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($tags->toArray(), 'Tags retrieved successfully');
    }



    /**
     * Display the specified Tag.
     * GET|HEAD /tags/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Tag $tag */
        $tag = $this->tagRepository->find($id);

        if (empty($tag)) {
            return $this->sendError('Tag not found');
        }

        return $this->sendResponse($tag->toArray(), 'Tag retrieved successfully');
    }

    /**
    * Display unique tags
    * GET|HEAD /tags
    *
    * @param Request $request
    * @return Response
    */
    public function unique(Request $request)
    {
        $tags = $this->tagRepository->unique();

        return $this->sendResponse(array_map(function ($tag) {
            return $tag['title'];
        }, $tags->toArray()), 'Tags retrieved successfully');
    }
}
