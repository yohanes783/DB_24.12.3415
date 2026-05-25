<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Partner; // Sudah terimpor dengan benar

class HomeController extends Controller
{
    public function index(Request $request)
{
    // 1. Ambil semua jenis kategori untuk tampilan filter tab button
    $categories = Category::all();

    // 2. Ambil semua partner
    $partners = Partner::latest()->get();

    // 3. Buat kueri dasar untuk mengambil event (HAPUS SELEKSI DATE)
    $query = Event::with('category')
                  ->orderBy('date', 'asc'); // <-- Dibuat polosan tanpa filter '>= now()'

    // 4. Filter query jika url memiliki parameter pencarian spesifik ?category=...
    if ($request->has('category') && $request->category != '') {
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('slug', $request->category);
        });
    }

    // 5. Eksekusi query
    $events = $query->get();

    return view('welcome', compact('events', 'categories', 'partners'));
}

}
