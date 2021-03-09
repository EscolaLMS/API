<?php

namespace App\Services;

use App\Dto\UserSaveDto;
use EscolaLms\Core\Enums\UserRole;
use EscolaLms\Auth\Events\PasswordForgotten;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Contracts\AuthServiceContract;
use App\Services\Contracts\UserServiceContract;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthService implements AuthServiceContract
{
    private UserServiceContract $userService;
    private UserRepositoryContract $userRepository;

    public function __construct(UserServiceContract $userService, UserRepositoryContract $userRepository)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
    }

    public function forgotPassword(string $email, string $returnUrl): void
    {
        $user = $this->userRepository->findByEmailOrFail($email);

        event(new PasswordForgotten($user, $returnUrl));
    }

    public function resetPassword(string $email, string $token, string $password): void
    {
        $user = $this->userRepository->findByEmailOrFail($email);

        if ($token !== $user->password_reset_token) {
            throw new AuthorizationException('Wrong password reset token');
        }

        $this->userRepository->update([
            'password' => Hash::make($password),
            'password_reset_token' => null,
        ], $user->getKey());
    }

    public function getTokenBySocial(string $provider): string
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();
        $user = $this->userRepository->findByEmail($socialUser->email);

        if (is_null($user)) {
            $name = Str::of($socialUser->name);
            $firstName = Str::of($name->explode(' ')->first())->trim();
            $lastName = $name->after($firstName)->trim();

            $userSaveDto = new UserSaveDto(
                $firstName,
                $lastName,
                true,
                [UserRole::STUDENT],
                $socialUser->email,
            );

            $user = $this->userService->create($userSaveDto);
        }

        return $user->createToken(config('passport.personal_access_client.secret'))->accessToken;
    }
}
