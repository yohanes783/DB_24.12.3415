<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Review;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua kategori
        $categories = Category::all();

        // 2. Ambil partner yang berstatus 'approved'
        $partners = Partner::where('status', 'approved')->latest()->get();

        // 3. Ambil 6 ulasan terbaru
        $reviews = Review::with(['user', 'event'])->latest()->take(6)->get();

        // 4. Query Event: Tampilkan event buatan Admin (partner_id null) ATAU buatan Partner Approved
        $query = Event::with(['category', 'partner'])
                      ->where(function ($q) {
                          $q->whereNull('partner_id') // Event dari Superadmin/Admin
                            ->orWhereHas('partner', function ($p) {
                                $p->where('status', 'approved'); // Event dari Partner Approved
                            });
                      })
                      ->whereDate('date', '>=', Carbon::today()) // Hanya event hari ini & akan datang
                      ->orderBy('date', 'asc');

        // 5. Filter berdasarkan Kategori jika ada parameter (?category=slug)
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 6. Eksekusi Query
        $events = $query->get();

        // 7. Render view 'welcome'
        return view('welcome', compact('events', 'categories', 'partners', 'reviews'));
    }
}