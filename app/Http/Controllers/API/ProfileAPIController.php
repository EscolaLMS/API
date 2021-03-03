<?php

namespace App\Http\Controllers\API;

use App\Dto\UserUpdateAuthDataDto;
use App\Dto\UserUpdateDto;
use App\Http\Controllers\API\Swagger\ProfileSwagger;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\ProfileUpdateAuthDataRequest;
use App\Http\Requests\API\ProfileUpdatePasswordRequest;
use App\Http\Requests\API\ProfileUpdateRequest;
use App\Http\Requests\API\UploadAvatarRequest;
use App\Http\Requests\MyProfileRequest;
use App\Http\Requests\UpdateInterests;
use App\Http\Requests\UserSettingsUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserSettingsResource;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Contracts\UserServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileAPIController extends AppBaseController implements ProfileSwagger
{
    private UserRepositoryContract $userRepository;
    private UserServiceContract $userService;

    public function __construct(UserRepositoryContract $userRepository, UserServiceContract $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    public function me(MyProfileRequest $request): JsonResponse
    {
        return (new UserResource($request->user()))->response();
    }

    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $userUpdateDto = UserUpdateDto::instantiateFromRequest($request);
        $success = (bool) $this->userRepository->update(
            $userUpdateDto->toArray(),
            $request->user()->getKey(),
        );

        return new JsonResponse(['success' => $success], $success ? 200 : 422);
    }

    public function updateAuthData(ProfileUpdateAuthDataRequest $request): JsonResponse
    {
        $userUpdateDto = UserUpdateAuthDataDto::instantiateFromRequest($request);
        $success = (bool) $this->userRepository->update(
            $userUpdateDto->toArray(),
            $request->user()->getKey(),
        );

        return new JsonResponse(['success' => $success], $success ? 200 : 422);
    }

    public function updatePassword(ProfileUpdatePasswordRequest $request): JsonResponse
    {
        $success = $this->userRepository->updatePassword(
            $request->user(),
            $request->input('new_password'),
        );

        return new JsonResponse(['success' => $success], $success ? 200 : 422);
    }

    public function uploadAvatar(UploadAvatarRequest $request): JsonResponse
    {
        $avatarUrl = $this->userService->uploadAvatar(
            $request->user(),
            $request->file('avatar'),
        );
        $success = (bool) $avatarUrl;
        return new JsonResponse(['success' => $success, 'avatar_url' => $avatarUrl], $success ? 200 : 422);
    }

    public function deleteAvatar(Request $request): JsonResponse
    {
        $success = $this->userService->deleteAvatar($request->user());
        return new JsonResponse(['success' => $success], $success ? 200 : 422);
    }

    public function interests(UpdateInterests $request): JsonResponse
    {
        $this->userRepository->updateInterests(
            $request->input('interests'),
            $request->user()->getKey(),
        );

        return (new UserResource($request->user()))->response();
    }

    public function settings(Request $request): JsonResponse
    {
        $user = $request->user();

        return (new UserSettingsResource($user->settings))->response();
    }

    public function settingsUpdate(UserSettingsUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $this->userRepository->updateSettings($user, $request->all());

        return (new UserSettingsResource($user->settings))->response();
    }
}
