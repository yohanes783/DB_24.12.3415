<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-indigo-600 text-white min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full space-y-6">
        <!-- Success Banner -->
        <div class="text-center">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black">Pembayaran Berhasil!</h1>
            <p class="text-indigo-100 mt-2">Tiket Anda telah terbit dan siap digunakan.</p>
        </div>

        <!-- Alert Notifikasi Flash Session -->
        @if(session('success'))
            <div class="bg-emerald-500 text-white p-4 rounded-2xl text-center font-bold text-sm shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-2xl text-center font-bold text-sm shadow-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Ticket Card -->
        <div class="bg-white text-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl relative">
            <!-- Ticket Header -->
            <div class="p-8 bg-indigo-50 border-b-4 border-dashed border-indigo-100 text-center relative">
                <p class="text-indigo-600 font-bold uppercase tracking-widest text-xs mb-2">E-Ticket Resmi</p>
                <h2 class="text-2xl font-black leading-tight">{{ $transaction->event->title ?? 'Nama Acara' }}</h2>

                <!-- Ticket Side Cuts -->
                <div class="absolute -left-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
                <div class="absolute -right-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
            </div>

            <!-- Ticket Body -->
            <div class="p-8 space-y-8">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Nama Pembeli</p>
                        <p class="font-bold text-lg">{{ $transaction->customer_name ?? 'Pelanggan' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Tanggal & Waktu</p>
                        <p class="font-bold text-lg">
                            {{ isset($transaction->event->date) ? \Carbon\Carbon::parse($transaction->event->date)->format('d M Y, H:i') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Order ID</p>
                        <p class="font-bold">{{ $transaction->order_id }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase mb-1">Lokasi</p>
                        <p class="font-bold">{{ $transaction->event->location ?? '-' }}</p>
                    </div>
                </div>

                <!-- SEKSI QR CODE ASLI (INTEGRASI SCANNER CHECK-IN) -->
                <div class="bg-slate-100 p-6 rounded-3xl flex flex-col items-center">
                    <p class="text-slate-400 text-xs font-bold uppercase mb-4">Scan QR untuk Check-in</p>
                    
                    @if(($transaction->is_used ?? false) || ($transaction->status_checkin ?? '') === 'used')
                        <!-- Tampilan Jika Tiket Sudah Di-scan / Used -->
                        <div class="w-full bg-red-100 border-2 border-red-300 text-red-700 p-6 rounded-2xl text-center space-y-1">
                            <span class="text-3xl">🚫</span>
                            <p class="font-black text-base uppercase tracking-wider">Tiket Sudah Digunakan</p>
                            <p class="text-xs text-red-500 font-medium">Di-check-in pada: {{ $transaction->updated_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    @else
                        <!-- QR Code Real Terintegrasi dengan Order ID -->
                        <div class="p-3 bg-white rounded-2xl shadow-md border border-slate-200">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($transaction->order_id) }}" 
                                 alt="QR Code Tiket {{ $transaction->order_id }}" 
                                 class="w-48 h-48 object-contain">
                        </div>
                        <p class="mt-4 font-mono font-bold text-slate-800 tracking-wider text-sm">{{ $transaction->order_id }}</p>
                    @endif
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="px-8 pb-8">
                <button onclick="window.print()" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition">
                    Cetak / Simpan PDF
                </button>
                <a href="{{ route('home') }}" class="block text-center mt-4 text-slate-500 font-bold hover:text-indigo-600">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <!-- ========================================================== -->
        <!-- SISTEM ULASAN DAN PENILAIAN BINTANG (RATING & REVIEW)     -->
        <!-- ========================================================== -->
        @if(isset($transaction->event))
            @php
                // Cek apakah tanggal acara sudah berlalu (H+1)
                $isEventFinished = now()->gt(\Carbon\Carbon::parse($transaction->event->date)->addDay());

                // Cek ulasan eksis
                $existingReview = null;
                if(Auth::check()) {
                    $existingReview = \App\Models\Review::where('user_id', Auth::id())
                                        ->where('event_id', $transaction->event->id)
                                        ->first();
                }
            @endphp

            @if($isEventFinished)
                <div class="bg-white text-slate-900 rounded-[2rem] p-6 shadow-2xl">
                    @if($existingReview)
                        <!-- Tampilan Jika User SUDAH Memberi Ulasan -->
                        <div class="text-center">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">
                                Ulasan Anda Terkirim
                            </span>
                            <div class="flex justify-center items-center gap-1 text-amber-400 text-2xl my-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $existingReview->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <p class="text-slate-600 italic text-sm">"{{ $existingReview->comment }}"</p>
                        </div>
                    @else
                        <!-- Form Isi Ulasan Bintang jika BELUM Memberi Ulasan -->
                        <form action="{{ route('review.store', $transaction->event->id) }}" method="POST">
                            @csrf
                            <h3 class="font-bold text-lg text-slate-800 text-center mb-1">Bagaimana Acaranya?</h3>
                            <p class="text-xs text-slate-500 text-center mb-4">Berikan penilaian dan testimoni Anda untuk acara ini.</p>

                            <!-- Rating Radio Button -->
                            <div class="flex justify-center items-center gap-2 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                        <span class="text-3xl text-slate-300 peer-checked:text-amber-400 hover:text-amber-400 transition">★</span>
                                    </label>
                                @endfor
                            </div>

                            <!-- Comment Input -->
                            <textarea name="comment" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-600 mb-3" placeholder="Tulis testimoni Anda tentang penyelenggara..."></textarea>

                            <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-sm transition shadow-md">
                                Kirim Ulasan
                            </button>
                        </form>
                    @endif
                </div>
            @else
                <!-- Catatan jika acara belum selesai -->
                <p class="text-center text-xs text-indigo-200 italic">
                    * Form ulasan akan terbuka otomatis sehari setelah acara selesai.
                </p>
            @endif
        @endif

    </div>

</body>

</html>