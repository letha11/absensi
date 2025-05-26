<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateKaryawanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the user is authenticated and the current guard is 'user'
        return Auth::check() && Auth::guard()->name === 'user';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // NIK is obtained from the route parameter, not from the request body for validation purposes here
        $karyawanNik = $this->route('karyawan')->nik; 

        return [
            // 'nik' is not updatable via this form as it's a key, handled as read-only in view.
            'nama_lengkap' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('karyawan', 'email')->ignore($karyawanNik, 'nik'),
            ],
            'jabatan' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15',
            'password' => 'nullable|string|min:6', // Password is optional
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ];
    }
} 