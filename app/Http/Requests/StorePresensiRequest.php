<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

final class StorePresensiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Assuming only authenticated Karyawan can make this request
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
            'lokasi' => [
                'required',
                'string',
                // Regex to ensure "latitude,longitude" format with valid float numbers
                'regex:/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/'
            ],
            'image' => ['required', 'string'], // Further validation might be needed if it should be a specific base64 image format
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'lokasi.required' => 'Lokasi wajib diisi.',
            'lokasi.regex' => 'Format lokasi tidak valid.',
            'image.required' => 'Gambar wajib diunggah.',
        ];
    }
}
