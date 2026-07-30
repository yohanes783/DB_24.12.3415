<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'category_id',
        'title',
        'description',
        'date',
        'location',
        'price',
        'stock',
        'poster_path',
    ];

    /**
     * Casting tipe data otomatis
     */
    protected $casts = [
        'date' => 'datetime',
        'price' => 'integer',
        'stock' => 'integer',
    ];

    // ==========================================
    // RELASI MODEL (RELATIONSHIPS)
    // ==========================================

    /**
     * Relasi ke Partner/Organisasi (Pemilik/Penyelenggara Event)
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Relasi ke Kategori Event
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke seluruh Transaksi tiket dari Event ini
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Relasi ke seluruh Ulasan dari Event ini
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ==========================================
    // HELPER & ACCESSOR (OPSIONAL UTK VIEW)
    // ==========================================

    /**
     * Helper untuk mengecek apakah tiket masih tersedia
     */
    public function isAvailable()
    {
        return $this->stock > 0;
    }
}