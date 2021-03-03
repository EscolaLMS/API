<?php

namespace App\Http\Controllers\API\Swagger;

use App\Http\Requests\API\ForgotPasswordRequest;
use App\Http\Requests\API\RefreshTokenRequest;
use App\Http\Requests\ResendVerificationEmailRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SocialAuthRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

interface AuthSwagger
{
    /**
     * @OA\Post(
     *     path="/api/auth/password/forgot",
     *     summary="Password forget",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 example="user@escola-lms.com",
     *             ),
     *             @OA\Property(
     *                 property="return_url",
     *                 type="string",
     *                 example="https://getkibble.com/password-reset",
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          ),
     *      ),
     * )
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse;

    /**
     * @OA\Post(
     *     path="/api/auth/password/reset",
     *     summary="Password reset",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 example="user@escola-lms.com",
     *             ),
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 example="hFD4UpgzTMNBeCGyfUrbvHtDb2yzSnuE",
     *             ),
     *            @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 example="zaq1@WSX",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *     )
     * )
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse;

    /**
     * @OA\Get(
     *     path="/api/auth/refresh",
     *     summary="Refresh access token",
     *     tags={"Auth"},
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          ),
     *      ),
     * )
     */
    public function refresh(RefreshTokenRequest $request): JsonResponse;

    /**
     * @OA\Get(
     *     path="/api/auth/social/{provider}",
     *     summary="Redirect to social login",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *          name="provider",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              enum={"facebook", "google"},
     *          ),
     *      ),
     *     @OA\Response(
     *          response=302,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          ),
     *      ),
     * )
     */
    public function socialRedirect(SocialAuthRequest $request): RedirectResponse;

    /**
     * @OA\Get(
     *     path="/api/auth/social/{provider}/callback",
     *     summary="Redirect after sucessfull login",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *          name="provider",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              enum={"facebook", "google"},
     *          ),
     *      ),
     *     @OA\Response(
     *          response=302,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          ),
     *      ),
     * )
     */
    public function socialCallback(SocialAuthRequest $request): RedirectResponse;

    /**
     * @OA\Post(
     *     path="/api/auth/email/resend",
     *     summary="Resend email verification",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 example="user@escola-lms.com",
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *     )
     * )
     */
    public function resendEmailVerification(ResendVerificationEmailRequest $request): JsonResponse;
}
