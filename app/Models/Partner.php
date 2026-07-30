<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'logo',
        'description',
        'status', // pending, approved, rejected
    ];

    /**
     * Boot Method: Otomatis membuat/mengupdate slug saat Partner disimpan
     */
    protected static function booted()
    {
        static::saving(function ($partner) {
            // Jika slug belum ada atau nama partner diubah, regenerasi slug-nya
            if (empty($partner->slug) || $partner->isDirty('name')) {
                $partner->slug = Str::slug($partner->name);
            }
        });
    }

    /**
     * Relasi ke User pemilik akun partner
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Event yang dimiliki oleh partner ini
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Relasi ke Transaksi event milik partner ini
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}