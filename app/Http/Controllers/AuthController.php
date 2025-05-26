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
            return redirect('/dashboard');
        } else {
            return redirect('/')->with(['warning' => 'Email / Password Salah']);
        }
    }

    public function proseslogout(): RedirectResponse
    {
        if (Auth::guard('karyawan')->check()) {
            Auth::guard('karyawan')->logout();
            return redirect('/');
        }
        return redirect('/');
    }
    
    public function proseslogoutadmin(): RedirectResponse
    {
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
            return redirect('/panel');
        }
        return redirect('/panel');
    }

    public function prosesloginadmin(Request $request): RedirectResponse
    {
        if(Auth::guard('user')->attempt(['email'=> $request->email,'password'=> $request->password])){
            return redirect('/panel/dashboardadmin');
        }else{
            return redirect('/panel')->with(['warning'=>'Email atau Password Salah']);
        }
    }
}
