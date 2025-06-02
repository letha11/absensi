<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash; // Hash facade is not directly used here
use Illuminate\Http\RedirectResponse;

final class AuthController extends Controller
{
    public function proseslogin(Request $request): RedirectResponse
    {
        if (Auth::guard('karyawan')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('karyawan.dashboard');
        } else {
            return redirect()->route('login')->with(['warning' => 'Email / Password Salah']);
        }
    }

    public function proseslogout(): RedirectResponse
    {
        if (Auth::guard('karyawan')->check()) {
            Auth::guard('karyawan')->logout();
            return redirect()->route('login');
        }
        return redirect()->route('login');
    }
    
    public function proseslogoutadmin(): RedirectResponse
    {
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
            return redirect()->route('loginadmin');
        }
        return redirect()->route('loginadmin');
    }

    public function prosesloginadmin(Request $request): RedirectResponse
    {
        if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::guard('user')->user();
            // Check if the user has the 'admin' role or any other specific role if needed
            // For now, any authenticated user is redirected to the admin dashboard
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('loginadmin')->with(['warning'=>'Email atau Password Salah']);
        }
    }
}
