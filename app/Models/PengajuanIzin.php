<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PengajuanIzin extends Model
{
    use HasFactory;

    // Define table name if not plural of model name (e.g., pengajuan_izin)
    protected $table = 'pengajuan_izin'; // Assuming this is the table name

    // Define fillable properties
    protected $fillable = [
        'nik',
        'tgl_izin',
        'status', // e.g., 'i' for izin, 's' for sakit
        'keterangan',
        // 'status_approved' // Example: 0 = pending, 1 = approved, 2 = rejected
    ];

    // Define casts for attributes
    protected function casts(): array
    {
        return [
            'tgl_izin' => 'date',
        ];
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }
}
