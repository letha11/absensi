<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  ...$guards
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard('karyawan')->check()) {
                // Redirect ke HOME yang didefinisikan di RouteServiceProvider
                return redirect(RouteServiceProvider::HOME);
            }

            if (Auth::guard('user')->check()) {
                return redirect(RouteServiceProvider::HOMEADMIN);
            }
            return $next($request);
        }
    }
}