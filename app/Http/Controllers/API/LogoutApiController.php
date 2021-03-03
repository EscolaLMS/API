<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Swagger\LogoutSwagger;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\LogoutRequest;
use Illuminate\Http\JsonResponse;

class LogoutApiController extends Controller implements LogoutSwagger
{
    public function logout(LogoutRequest $request): JsonResponse
    {
        $token = $request->user()->token();
        $token->revoke();
        return new JsonResponse(['success' => true, 'message' => 'You have been successfully logged out!'], 200);
    }
}
