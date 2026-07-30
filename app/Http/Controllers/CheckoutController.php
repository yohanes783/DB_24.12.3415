<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        $categories = Category::all();

        // Cek jika stok tiket sudah habis
        if ($event->stock <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok tiket untuk event ini sudah habis.');
        }

        return view('checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, $eventId)
    {
        // 1. Validasi Input Form (customer_email tidak perlu divalidasi ketat karena di-override dari Auth)
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // 2. Ambil data event beserta partner pemiliknya
        $event = Event::findOrFail($eventId);

        // Cek stok kembali sebelum memproses
        if ($event->stock <= 0) {
            return redirect()->back()->with('error', 'Maaf, stok tiket untuk event ini baru saja habis.');
        }

        // 3. Paksa email menggunakan email akun yang sedang login (Opsi A)
        $user = Auth::user();
        $customerEmail = $user->email;

        // 4. Hitung total harga
        $adminFee = 5000;
        $totalPrice = $event->price + $adminFee;
        $orderId = 'TRX-' . mt_rand(10000, 99999);

        // 5. Simpan transaksi awal ke database
        $transaction = Transaction::create([
            'user_id'        => $user->id,
            'event_id'       => $event->id,
            'partner_id'     => $event->partner_id, // Autolink ke tenant/organisasi (null jika admin)
            'order_id'       => $orderId,
            'customer_name'  => $request->customer_name,
            'customer_email' => $customerEmail, // Aman & Menggunakan Email Auth Login
            'customer_phone' => $request->customer_phone,
            'total_price'    => $totalPrice,
            'status'         => 'Pending',
        ]);

        // --- INTEGRASI SNAP MIDTRANS ---
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false; // Mode Sandbox
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => $customerEmail,
                'phone'      => $request->customer_phone,
            ],
        ];

        try {
            // Ambil Snap Token dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan snap_token ke transaksi
            $transaction->update(['snap_token' => $snapToken]);

            // Redirect ke halaman pembayaran
            return redirect()->route('checkout.payment', $transaction->order_id);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke Midtrans: ' . $e->getMessage());
        }
    }

    public function payment($order_id)
    {
        $categories = Category::all();

        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        return view('checkout.payment', compact('transaction', 'categories'));
    }

    public function success($order_id)
    {
        $categories = Category::all();

        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        // Validasi status pembayaran dari Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;

        try {
            $midtransStatus = \Midtrans\Transaction::status($order_id);

            // Jika status pembayaran lunas di Midtrans
            if (in_array($midtransStatus->transaction_status, ['capture', 'settlement'])) {
                if ($transaction->status !== 'success') {
                    $transaction->update(['status' => 'success']);

                    // Kurangi stok tiket event secara otomatis
                    if ($transaction->event) {
                        $transaction->event->decrement('stock');
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
        }

        // Tampilkan halaman e-ticket
        return view('ticket', compact('transaction', 'categories'));
    }
}
