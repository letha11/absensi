<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Providers\RouteServiceProvider;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return string|null
     */
    protected function redirectTo($request)
    {
       if(!$request->expectsJson()){
        if(request()->is('panel/*')){
            return route('loginadmin');
        } else{
            return route('login');
        }
       } 
    }
}