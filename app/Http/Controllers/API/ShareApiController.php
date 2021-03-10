<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Swagger\ShareSwagger;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\ShareRequest;
use App\Models\User;
use EscolaLms\Core\Repositories\Contracts\ConfigRepositoryContract;
use Illuminate\Http\JsonResponse;

class ShareApiController extends AppBaseController implements ShareSwagger
{
    private ConfigRepositoryContract $configRepository;

    public function __construct(ConfigRepositoryContract $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function linkedin(ShareRequest $request): JsonResponse
    {
        $query = [
            'mini' => true,
            'url' => $request->input('url'),
        ];
        return new JsonResponse(['success' => true, 'url' => 'https://www.linkedin.com/shareArticle?' . http_build_query($query)]);
    }

    public function facebook(ShareRequest $request): JsonResponse
    {
        $query = [
            'u' => $request->input('url'),
        ];
        return new JsonResponse(['success' => true, 'url' => 'https://www.facebook.com/sharer/sharer.php?' . http_build_query($query)]);
    }

    public function twitter(ShareRequest $request): JsonResponse
    {
        $query = [
            'url' => $request->input('url'),
        ];
        return new JsonResponse(['success' => true, 'url' => 'https://twitter.com/intent/tweet?' . http_build_query($query)]);
    }


}
