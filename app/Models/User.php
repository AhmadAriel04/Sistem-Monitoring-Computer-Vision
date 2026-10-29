<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Daftar role yang tersedia dalam sistem.
     * Harus sinkron dengan enum kolom 'role' pada migrasi users table.
     */
    public const ROLES = ['admin', 'bosm', 'manager'];

    /**
     * Role yang memiliki akses penuh (create/update/delete).
     * Role lain (bosm, manager) bersifat view-only.
     */
    public const FULL_ACCESS_ROLES = ['admin'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
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
        ];
    }

    /**
     * Apakah user ini memiliki akses penuh (create/update/delete)?
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, self::FULL_ACCESS_ROLES, true);
    }

    /**
     * Apakah user ini hanya boleh melihat data (view-only)?
     */
    public function isViewOnly(): bool
    {
        return ! $this->isAdmin();
    }

    public function isBosm(): bool
    {
        return $this->role === 'bosm';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    /**
     * Label role yang enak dibaca untuk ditampilkan di UI.
     */
    public function roleLabel(): string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'bosm' => 'BOSM',
            'manager' => 'Manager',
            default => ucfirst((string) $this->role),
        };
    }
}
