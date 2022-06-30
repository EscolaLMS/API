<?php

namespace App\Exceptions;

use EscolaLms\Auth\Exceptions\OnboardingNotCompleted;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (OnboardingNotCompleted $exception, $request) {
            return response()->json(['error' => $exception->getMessage()], 401);
        });

        $this->renderable(function (ForbiddenException $exception, $request) {
            return response()->json(['error' => $exception->getMessage()], 403);
        });

        $this->renderable(function (AccessDeniedHttpException $exception, $request) {
            return response()->json(['error' => $exception->getMessage()], 403);
        });

        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e) && app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
        });
    }

    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        $errors = [];

        foreach ($exception->errors() as $key => $error)
        {
            $transKey = 'validation.attributes.' . $key;
            $key = trans()->has($transKey) ? trans($transKey) : $key;
            $errors[$key] = $error;
        }

        return response()->json([
            'message' => __($exception->getMessage()),
            'errors' => $errors,
        ], $exception->status);
    }
}
