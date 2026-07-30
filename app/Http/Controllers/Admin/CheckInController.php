<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckInController extends Controller
{
    /**
     * Menampilkan halaman QR Code Scanner untuk Admin/Panitia
     */
    public function index()
    {
        return view('admin.scan');
    }

    /**
     * Memproses QR Code / Order ID dari Scanner
     */
    public function process(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $code = trim($request->qr_code);

        // Cari transaksi berdasarkan Order ID / Token Tiket
        $transaction = Transaction::with(['user', 'event'])
            ->where('order_id', $code)
            ->first();

        // 1. Tiket Tidak Ditemukan
        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tiket tidak ditemukan! Pastikan Order ID benar.'
            ], 404);
        }

        // 2. Tiket Belum Lunas
        if ($transaction->status !== 'success' && $transaction->status !== 'settlement') {
            return response()->json([
                'status' => 'warning',
                'message' => 'Tiket ini belum lunas / pembayaran gagal!'
            ], 400);
        }

        // 3. Tiket Sudah Pernah Di-scan (Anti Double-Entry)
        if ($transaction->is_used) {
            $usedTime = $transaction->status_checkin 
                ? Carbon::parse($transaction->status_checkin)->format('d M Y - H:i') . ' WIB'
                : 'Sebelumnya';

            return response()->json([
                'status' => 'already_used',
                'message' => 'TIKET SUDAH DIGUNAKAN!',
                'data' => [
                    'name' => $transaction->user->name ?? 'Peserta',
                    'event' => $transaction->event->title ?? '-',
                    'used_at' => $usedTime
                ]
            ], 422);
        }

        // 4. Tiket Valid -> Tandai Sudah Di-scan
        $transaction->update([
            'is_used' => true,
            'status_checkin' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'CHECK-IN BERHASIL!',
            'data' => [
                'order' => $transaction->order_id,
                'name' => $transaction->user->name ?? 'Peserta',
                'event' => $transaction->event->title ?? '-',
                'time' => now()->format('d M Y - H:i') . ' WIB'
            ]
        ], 200);
    }
}