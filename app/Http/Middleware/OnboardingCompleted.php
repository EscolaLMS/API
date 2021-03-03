<?php

namespace App\Http\Middleware;

use App\Exceptions\OnboardingNotCompleted;
use Closure;
use Illuminate\Http\Request;

class OnboardingCompleted
{
    /**
     * The URIs that should be reachable while onboarding isn't completed.
     *
     * @var array
     */
    protected const EXCEPT = [
        'api/auth/logout',
        'api/profile/interests',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws OnboardingNotCompleted
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (
            $user &&
            !$user->onboarding_completed &&
            !in_array($request->path(), self::EXCEPT)
        ) {
            throw new OnboardingNotCompleted();
        }

        return $next($request);
    }
}
