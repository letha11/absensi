<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

final class Karyawan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "karyawan";
    protected $primaryKey = "email";
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'jabatan',
        'email',
        'password',
        'no_hp',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName(): string
    {
        return 'email';
    }

    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class, 'karyawan_email', 'email');
    }

    public function pengajuanIzin(): HasMany
    {
        return $this->hasMany(PengajuanIzin::class, 'karyawan_email', 'email');
    }
}
