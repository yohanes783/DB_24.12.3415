<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
        'google_id',
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

    // ==========================================
    // RELASI MODEL
    // ==========================================

    /**
     * Relasi ke Model Partner (1 User bisa mengelola 1 Profil Partner/HIMA)
     */
    public function partner()
    {
        return $this->hasOne(Partner::class, 'user_id');
    }

    // ==========================================
    // HELPER / CHECKER METHODS
    // ==========================================

    /**
     * Mengecek apakah user adalah Partner / Organisasi
     */
    public function isPartner(): bool
    {
        return $this->role === 'partner';
    }

    /**
     * Mengecek apakah user adalah Superadmin
     */
    // Tambahkan method ini di dalam class User
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin()
    {
        // Mengembalikan true jika rolenya admin ATAU superadmin
        return in_array($this->role, ['admin', 'superadmin']);
    }

}