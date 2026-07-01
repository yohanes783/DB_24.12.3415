<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event; // WAJIB: Impor model Event agar database bisa diakses

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
        return view('ticket');
    }
}
