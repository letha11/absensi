<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class KonfigurasiLokasi extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_lokasi';

    protected $fillable = [
        'lokasi_kantor',
        'latitude',
        'longitude',
        'radius',
    ];
}
