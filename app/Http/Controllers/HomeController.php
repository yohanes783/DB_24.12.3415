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

        // [TAMBAHAN SOAL 4]: Ambil sekumpulan data Partner dari database
        $partners = Partner::latest()->get();

        // 2. Buat kueri dasar untuk mengambil event:
        $query = Event::with('category')
                      ->where('date', '>=', now())
                      ->orderBy('date', 'asc');

        // 3. Filter query jika url memiliki parameter pencarian spesifik ?category=...
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. Eksekusi query dan kirim data hasilnya ke template Blade
        $events = $query->get();

        // DIUBAH: Ditambahkan 'partners' ke dalam fungsi compact()
        return view('welcome', compact('events', 'categories', 'partners'));
    }
}
