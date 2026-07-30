<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User; // Tambahkan Model User
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Menjumlahkan semua nominal total_price dari kolom Transaksi Lunas
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success'])->sum('total_price');
        
        // 2. Menghitung Berapa orang tamu yang tiketnya sudah Lunas
        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success'])->count();
        
        // 3. Menghitung Jumlah Acara Mendatang yang aktif diselenggarakan
        $activeEvents = Event::where('date', '>=', now())->count();
        
        // 4. Menghitung Transaksi Ngadat (Status belum dibayar pelanggan / Expired)
        $pendingOrders = Transaction::where('status', 'pending')->count();
        
        // 5. Menyertakan 5 daftar riwayat pesanan (History) paling mutakhir di panel
        $recentTransactions = Transaction::with('event')->latest()->take(5)->get();

        // =========================================================================
        // PENGEMBANGAN FITUR GRAFIK (SESUAI SOAL)
        // =========================================================================

        // 6. Query Pertumbuhan Pengguna Baru Per Bulan (Tahun Ini)
        $userGrowthQuery = User::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // 7. Query Pertumbuhan Penyelenggaraan Event Per Bulan (Tahun Ini)
        $eventGrowthQuery = Event::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // Mapping Data Bulan (Januari - Desember)
        $chartMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $userGrowthData = [];
        $eventGrowthData = [];

        for ($i = 1; $i <= 12; $i++) {
            $userGrowthData[] = $userGrowthQuery[$i] ?? 0;
            $eventGrowthData[] = $eventGrowthQuery[$i] ?? 0;
        }

        // Return ke view beserta variabel grafik baru
        return view('admin.dashboard', compact(
            'totalRevenue', 
            'ticketsSold', 
            'activeEvents', 
            'pendingOrders', 
            'recentTransactions',
            'chartMonths',
            'userGrowthData',
            'eventGrowthData'
        ));
    }
}