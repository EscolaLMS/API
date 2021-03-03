<?php

namespace App\Http\Controllers\API;

use App\Dto\UserSaveDto;
use App\Http\Controllers\API\Swagger\RegisterSwagger;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\RegisterRequest;
use App\Services\Contracts\UserServiceContract;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class RegisterApiController extends Controller implements RegisterSwagger
{
    private UserServiceContract $userService;

    public function __construct(UserServiceContract $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $userSaveDto = UserSaveDto::instantiateFromRequest($request);
        $user = $this->userService->create($userSaveDto);
        event(new Registered($user));

        return new JsonResponse(['success' => true], 200);
    }
}
