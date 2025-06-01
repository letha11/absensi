<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

final class ProfileController extends Controller
{
    public function showChangePasswordForm(): View
    {
        return view('admin.profile.change-password');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.admin.password.change')
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::guard('user')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('admin.admin.password.change')
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])
                ->withInput();
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('admin.admin.password.change')->with('success', 'Password berhasil diubah.');
    }
} 