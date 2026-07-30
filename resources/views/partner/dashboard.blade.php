@extends('layouts.partner')

@section('page_title', 'Dashboard Partner')
@section('page_subtitle', 'Selamat datang kembali! Berikut ringkasan penjualan tiket event kamu.')

@section('content')
<!-- Ringkasan Statistik -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <p class="text-sm font-semibold text-slate-500 mb-1">Total Event</p>
        <h3 class="text-3xl font-black text-slate-800">{{ $totalEvents ?? 0 }}</h3>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <p class="text-sm font-semibold text-slate-500 mb-1">Tiket Terjual</p>
        <h3 class="text-3xl font-black text-blue-600">{{ $ticketsSold ?? 0 }}</h3>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <p class="text-sm font-semibold text-slate-500 mb-1">Total Pendapatan</p>
        <h3 class="text-3xl font-black text-emerald-600">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
    </div>
</div>

<!-- Tabel Transaksi Terbaru -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-800">Transaksi Terbaru</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-bold uppercase text-slate-400">
                    <th class="p-4">Order ID</th>
                    <th class="p-4">Nama Pembeli</th>
                    <th class="p-4">Event</th>
                    <th class="p-4">Total</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($recentTransactions ?? [] as $transaction)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="p-4 font-bold text-slate-700">#{{ $transaction->order_id }}</td>
                    <td class="p-4 font-semibold text-slate-800">{{ $transaction->user->name ?? 'Guest' }}</td>
                    <td class="p-4 text-slate-600">{{ $transaction->event->title ?? '-' }}</td>
                    <td class="p-4 font-bold text-slate-800">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 bg-green-50 text-green-600 font-bold text-xs rounded-full">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-400 font-medium">
                        Belum ada transaksi masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
