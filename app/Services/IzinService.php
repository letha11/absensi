<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PengajuanIzin;
use Carbon\Carbon;

final class IzinService
{
    /**
     * Store a new izin (leave/permit) request.
     *
     * @param string $email Karyawan's email
     * @param string $tglIzin Date of leave/permit
     * @param string $status Status of leave (e.g., 'i' for izin, 's' for sakit)
     * @param string $keterangan Description/reason for leave
     * @return PengajuanIzin|null The created PengajuanIzin record or null on failure
     */
    public function storeIzin(string $email, string $tglIzin, string $status, string $keterangan): ?PengajuanIzin
    {
        // Consider adding validation for tglIzin format if not already handled by FormRequest
        // Or ensure it's a Carbon instance if type-hinted differently
        
        return PengajuanIzin::create([
            'karyawan_email' => $email,
            'tgl_izin' => Carbon::parse($tglIzin)->toDateString(), // Ensure correct date format
            'status' => $status,
            'keterangan' => $keterangan,
            // 'status_approved' => 0, // Default to pending if this field exists and is used
        ]);
    }
} 