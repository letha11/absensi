<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

final class StoreIzinRequest extends FormRequest
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
            'tgl_izin' => ['required', 'date_format:Y-m-d'], // Or 'date' if you allow more flexible formats and parse later
            'status' => ['required', 'string', Rule::in(['i', 's'])], // 'i' for izin, 's' for sakit
            'keterangan' => ['required', 'string', 'max:1000'],
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
            'tgl_izin.required' => 'Tanggal izin wajib diisi.',
            'tgl_izin.date_format' => 'Format tanggal izin harus YYYY-MM-DD.',
            'status.required' => 'Status izin wajib dipilih.',
            'status.in' => 'Status izin tidak valid.',
            'keterangan.required' => 'Keterangan wajib diisi.',
        ];
    }
}
