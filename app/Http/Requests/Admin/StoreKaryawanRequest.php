<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreKaryawanRequest extends FormRequest
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
        return [
            'nik' => 'required|string|unique:karyawan,nik|max:10',
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|unique:karyawan,email',
            'jabatan' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ];
    }
} 