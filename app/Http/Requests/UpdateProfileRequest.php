<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('karyawan')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:15'], // Adjust max length as needed
            'password' => ['nullable', 'confirmed', Password::min(8)->sometimes()],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Max 2MB
        ];
    }
}
