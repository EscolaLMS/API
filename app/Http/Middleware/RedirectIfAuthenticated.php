<?php

namespace App\Http\Middleware;

use EscolaLms\Core\Enums\UserRole;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (Auth::user()->hasRole(UserRole::INSTRUCTOR)) {
                return redirect()->route('instructor.dashboard');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
