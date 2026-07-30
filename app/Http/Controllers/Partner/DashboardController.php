<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data Partner yang sedang login
        $partner = Auth::user()->partner;

        // Keamanan: Jika user tidak memiliki profil partner, redirect
        if (!$partner) {
            return redirect()->route('home')->with('error', 'Profil Partner tidak ditemukan.');
        }

        // 2. Ambil statistik data khusus milik Partner ini
        $totalEvents = Event::where('partner_id', $partner->id)->count();

        // Total pendapatan dari transaksi yang berhasil/lunas
        $totalRevenue = Transaction::where('partner_id', $partner->id)
            ->whereIn('status', ['success', 'settlement', 'capture'])
            ->sum('total_price');

        // Total tiket terpesan/terjual
        $totalTicketsSold = Transaction::where('partner_id', $partner->id)
            ->whereIn('status', ['success', 'settlement', 'capture'])
            ->count();

        // 3. Ambil 5 Transaksi Terbaru milik Event Partner ini
        $recentTransactions = Transaction::with('event')
            ->where('partner_id', $partner->id)
            ->latest()
            ->take(5)
            ->get();

        // 4. Ambil daftar event milik partner untuk ditampilkan singkat
        $events = Event::where('partner_id', $partner->id)
            ->latest()
            ->take(5)
            ->get();

        return view('partner.dashboard', compact(
            'partner',
            'totalEvents',
            'totalRevenue',
            'totalTicketsSold',
            'recentTransactions',
            'events'
        ));
    }
}