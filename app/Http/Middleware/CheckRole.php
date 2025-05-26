<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User; // Ensure User model is imported
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response; // Import Response

final class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            // Ensure you have a route named 'login'. If your admin login is different, adjust accordingly.
            return redirect()->route('loginadmin');
        }

        /** @var User $user */
        $user = Auth::user();

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // If user doesn't have any of the required roles
        // You can customize this response. For example, redirect back with an error or show a specific view.
        abort(403, 'UNAUTHORIZED ACTION.');
    }
} 