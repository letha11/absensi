<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Presensi extends Model
{
    use HasFactory;

    // Define table name if not plural of model name
    // protected $table = 'presensi';

    // Define fillable properties
    protected $fillable = [
        'nik',
        'tgl_presensi',
        'jam_in',
        'jam_out',
        'foto_in',
        'foto_out',
        'lokasi_in',
        'lokasi_out',
    ];

    // Define casts for attributes
    protected function casts(): array
    {
        return [
            'tgl_presensi' => 'date',
            'jam_in' => 'datetime:H:i:s', // Or just 'datetime' if you store date too
            'jam_out' => 'datetime:H:i:s', // Or just 'datetime'
        ];
    }

    // Add relationships here, e.g., to Karyawan
    // public function karyawan()
    // {
    //    return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    // }
}
