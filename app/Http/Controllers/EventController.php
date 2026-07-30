<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction; // <-- 1. TAMBAHKAN BARIS INI!
use Illuminate\Support\Facades\Auth; // WAJIB: Impor model Event agar database bisa diakses

class EventController extends Controller
{
    // PERBAIKAN: Menambahkan parameter $id untuk menangkap ID event dari URL rute
    public function show($id)
    {
        // 1. Cari data event berdasarkan ID beserta relasi kategori, jika tidak ada tampilkan error 404
        $event = Event::with('category')->findOrFail($id);

        // 2. Kirim data variabel $event tunggal tersebut ke template blade detail
        return view('event-detail', compact('event'));
    }

    public function checkout()
    {
        return view('checkout');
    }

    public function ticket()
{
    // Mengambil transaksi berdasarkan ID user yang login ATAU email yang cocok
    $transactions = Transaction::with('event')
        ->where(function ($query) {
            $query->where('user_id', Auth::id())
                  ->orWhere('customer_email', Auth::user()->email);
        })
        ->latest()
        ->get();

    $categories = \App\Models\Category::all();

    return view('my-ticket', compact('transactions', 'categories'));
}
}
