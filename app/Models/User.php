<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

final class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_DIREKTUR = 'Direktur';
    public const ROLE_OPERASIONAL_DIREKTUR = 'Operasional Direktur';
    public const ROLE_HRD = 'HRD';
    public const ROLE_ADMIN = 'Admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    public function hasRole(string $role): bool
    {    
        return $this->role === $role;
    }

    public function isDirektur(): bool
    {
        return $this->hasRole(self::ROLE_DIREKTUR);
    }

    public function isOperasionalDirektur(): bool
    {
        return $this->hasRole(self::ROLE_OPERASIONAL_DIREKTUR);
    }

    public function isHrd(): bool
    {
        return $this->hasRole(self::ROLE_HRD);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }
}
