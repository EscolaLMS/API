<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Swagger\AuthSwagger;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ForgotPasswordRequest;
use App\Http\Requests\API\RefreshTokenRequest;
use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\ResendVerificationEmailRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SocialAuthRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Contracts\AuthServiceContract;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthApiController extends Controller implements AuthSwagger
{
    private AuthServiceContract $authService;
    private UserRepositoryContract $userRepository;

    /**
     * @param AuthServiceContract $authService
     */
    public function __construct(AuthServiceContract $authService, UserRepositoryContract $userRepository)
    {
        $this->authService = $authService;
        $this->userRepository = $userRepository;
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $this->authService->forgotPassword(
            $request->input('email'),
            $request->input('return_url'),
        );

        return new JsonResponse(['success' => true], 200);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->authService->resetPassword(
                $request->input('email'),
                $request->input('token'),
                $request->input('password'),
            );

            return new JsonResponse(['success' => true], 200);
        } catch (AuthorizationException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 401);
        }
    }

    public function refresh(RefreshTokenRequest $request): JsonResponse
    {
        $token = $request->user()->createToken(config('passport.personal_access_client.secret'))->accessToken;

        return new JsonResponse(['success' => true, 'token' => $token]);
    }

    public function socialRedirect(SocialAuthRequest $request): RedirectResponse
    {
        return Socialite::driver($request->route('provider'))->stateless()->redirect();
    }

    public function socialCallback(SocialAuthRequest $request): RedirectResponse
    {
        $token = $this->authService->getTokenBySocial($request->route('provider'));

        return redirect(config('app.frontend_url') . '/#/social-login?token=' . $token);
    }

    public function verifyEmail(EmailVerificationRequest $request, string $id, string $hash): RedirectResponse
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (
            hash_equals($id, (string) $user->getKey()) &&
            hash_equals($hash, sha1($user->getEmailForVerification())) &&
            !$user->hasVerifiedEmail()
        ) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return redirect(config('app.frontend_url'));
    }

    public function resendEmailVerification(ResendVerificationEmailRequest $request): JsonResponse
    {
        $user = $this->userRepository->findByEmail($request->input('email'));

        if ($user) {
            $user->sendEmailVerificationNotification();
        }

        return new JsonResponse(['success' => true]);
    }
}
