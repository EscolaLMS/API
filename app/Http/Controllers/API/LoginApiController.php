<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Swagger\LoginSwagger;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Services\Contracts\UserServiceContract;
use Exception;
use Illuminate\Http\JsonResponse;

class LoginApiController extends Controller implements LoginSwagger
{
    private UserServiceContract $userService;

    public function __construct(UserServiceContract $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->login(
                $request->input('email'),
                $request->input('password'),
            );

            $token = $user->createToken(config('passport.personal_access_client.secret'))->accessToken;

            return new JsonResponse(['success' => true, 'token' => $token], 200);
        } catch (Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], 422);
        }
    }
}
