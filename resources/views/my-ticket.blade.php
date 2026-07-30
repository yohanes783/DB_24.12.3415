<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Saya - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen pb-12">

    <!-- Header Ringkas -->
    <div class="bg-indigo-600 text-white py-8 px-6 mb-8 shadow-md">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black">Tiket Saya</h1>
                <p class="text-indigo-200 text-xs mt-1">Daftar riwayat pembelian tiket event kamu</p>
            </div>
            <a href="{{ route('home') }}" class="text-xs bg-white/20 hover:bg-white/30 text-white font-bold px-4 py-2 rounded-xl transition">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-6">

        <!-- Notifikasi Pesan Alert -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-100 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-rose-100 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold flex items-center gap-2">
                <span>⚠️</span> {{ session('error') }}
            </div>
        @endif

        @if($transactions->isEmpty())
            <!-- Tampilan Jika Belum Memiliki Tiket -->
            <div class="bg-white rounded-3xl p-10 text-center border border-slate-200 shadow-sm">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 font-bold text-2xl">
                    🎟️
                </div>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Tiket</h3>
                <p class="text-xs text-slate-500 mt-1 mb-6">Kamu belum pernah melakukan transaksi tiket event apapun.</p>
                <a href="{{ route('home') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-6 py-3 rounded-xl transition">
                    Jelajahi Event Sekarang
                </a>
            </div>
        @else
            <!-- Daftar Tiket User -->
            <div class="space-y-4">
                @foreach($transactions as $trx)
                    <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:border-indigo-200 transition">
                        <div>
                            <!-- Badge Status -->
                            <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full {{ $trx->status == 'success' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $trx->status }}
                            </span>

                            <h3 class="text-lg font-bold text-slate-800 mt-2">
                                {{ $trx->event->title ?? 'Nama Event Tidak Ditemukan' }}
                            </h3>
                            <p class="text-xs text-slate-400 font-mono mt-0.5">Order ID: {{ $trx->order_id }}</p>
                            @if(isset($trx->event->date))
                                <p class="text-[11px] text-slate-500 mt-1 font-semibold">
                                    📅 Tanggal Acara: {{ \Carbon\Carbon::parse($trx->event->date)->translatedFormat('d F Y') }}
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 pt-4 md:pt-0 border-slate-100">
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold">Total Bayar</p>
                                <p class="text-sm font-black text-indigo-600">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                            </div>

                            {{-- LOGIKA PEMBAGIAN TOMBOL: ULASAN vs E-TICKET vs BAYAR --}}
                            @if($trx->status == 'success')
                                {{-- Jika Acara Sudah Lewat (H+1), Tampilkan Tombol Ulasan --}}
                                @if(isset($trx->event->date) && \Carbon\Carbon::parse($trx->event->date)->addDay()->isPast())
                                    <button onclick="openReviewModal('{{ route('review.store', $trx->event->id) }}', '{{ $trx->event->title }}')"
                                            class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
                                        <span>⭐</span> Beri Ulasan
                                    </button>
                                @else
                                    {{-- Jika Acara Belum Lewat, Tampilkan E-Ticket --}}
                                    <a href="{{ route('checkout.success', $trx->order_id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-sm">
                                        Lihat E-Ticket
                                    </a>
                                @endif
                            @else
                                {{-- Jika Status Masih Pending --}}
                                <a href="{{ route('checkout.payment', $trx->order_id) }}" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-sm">
                                    Bayar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- MODAL POP-UP ULASAN -->
    <div id="review-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl relative">
            <button onclick="closeReviewModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 font-bold text-xl">&times;</button>

            <h3 class="text-xl font-extrabold text-slate-800">Beri Ulasan</h3>
            <p id="modal-event-title" class="text-xs text-indigo-600 font-bold mt-1 mb-6"></p>

            <form id="review-form" action="" method="POST" class="space-y-4">
                @csrf

                <!-- Rating Bintang Interaktif -->
                <div class="text-center py-2">
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Pilih Rating</label>
                    <div class="flex justify-center gap-2 flex-row-reverse text-3xl cursor-pointer star-rating">
                        <input type="radio" id="star5" name="rating" value="5" class="hidden" required /><label for="star5" class="text-slate-300 transition hover:text-amber-400">★</label>
                        <input type="radio" id="star4" name="rating" value="4" class="hidden" /><label for="star4" class="text-slate-300 transition hover:text-amber-400">★</label>
                        <input type="radio" id="star3" name="rating" value="3" class="hidden" /><label for="star3" class="text-slate-300 transition hover:text-amber-400">★</label>
                        <input type="radio" id="star2" name="rating" value="2" class="hidden" /><label for="star2" class="text-slate-300 transition hover:text-amber-400">★</label>
                        <input type="radio" id="star1" name="rating" value="1" class="hidden" /><label for="star1" class="text-slate-300 transition hover:text-amber-400">★</label>
                    </div>
                </div>

                <!-- Input Komentar -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Ulasan / Pesan</label>
                    <textarea name="comment" rows="3" maxlength="500" placeholder="Bagaimana keseruan event yang telah kamu ikuti?" class="w-full p-4 border-2 border-slate-100 rounded-2xl focus:border-indigo-600 outline-none text-sm font-medium transition" required></textarea>
                </div>

                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-200 transition text-sm">
                    Kirim Ulasan
                </button>
            </form>
        </div>
    </div>

    <!-- Style Bintang Hover & Active -->
    <style>
        .star-rating input:checked ~ label { color: #f59e0b; }
        .star-rating label:hover,
        .star-rating label:hover ~ label { color: #fbbf24; }
    </style>

    <!-- Script Kontrol Modal -->
    <script>
        function openReviewModal(actionUrl, eventTitle) {
            document.getElementById('review-form').action = actionUrl;
            document.getElementById('modal-event-title').innerText = eventTitle;
            document.getElementById('review-modal').classList.remove('hidden');
        }

        function closeReviewModal() {
            document.getElementById('review-modal').classList.add('hidden');
        }
    </script>

</body>
</html>
