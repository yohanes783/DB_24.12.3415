<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partner_id',
        'event_id',
        'order_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_price',
        'status',
        'snap_token',
    ];

    // ==========================================
    // RELASI MODEL
    // ==========================================

    /**
     * Relasi balik ke Pembeli (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi balik ke Penyelenggara Event (Partner)
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Relasi balik ke Event yang dibeli
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
