@extends('layouts.app')

@section('content')

<!-- CDN SweetAlert2 untuk Notifikasi Pop-up -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body class="bg-indigo-50/30 text-slate-900">

    <main class="max-w-3xl mx-auto px-6 py-20">
        <div class="mb-12">
            <a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 font-bold flex items-center gap-2 mb-6 hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Event
            </a>
            <h1 class="text-4xl font-extrabold">Checkout</h1>
            <p class="text-slate-500 mt-2">Lengkapi data Anda untuk mendapatkan tiket.</p>
        </div>

        <!-- Alert Notifikasi Jika Ada Error -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-bold rounded-r-2xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8">
            <!-- Summary Card -->
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <h3 class="text-xl font-bold mb-6 border-b pb-4">Pesanan Anda</h3>
                <div class="flex gap-6 items-start">
                    <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/concert.png') }}"
                         alt="{{ $event->title }}"
                         class="w-24 h-24 rounded-2xl object-cover border border-slate-100">
                    <div>
                        <h4 class="font-extrabold text-lg">{{ $event->title }}</h4>
                        <p class="text-slate-500 text-sm mt-1">
                            {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }} • {{ $event->location }}
                        </p>
                        <p class="text-indigo-600 font-bold mt-2">1 x Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t space-y-3">
                    <div class="flex justify-between text-slate-500">
                        <span>Harga Tiket</span>
                        <span>Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Biaya Layanan</span>
                        <span>Rp 5.000</span>
                    </div>
                    <div class="flex justify-between text-2xl font-black mt-4 pt-4 border-t">
                        <span>Total Bayar</span>
                        <span class="text-indigo-600">Rp {{ number_format($event->price + 5000, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <h3 class="text-xl font-bold mb-6 italic text-indigo-600 underline underline-offset-8">📦 Data Pemesan</h3>

                <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                            Nama Lengkap
                        </label>
                        <input type="text"
                               name="customer_name"
                               value="{{ old('customer_name', Auth::user()->name) }}"
                               placeholder="Masukkan nama sesuai identitas"
                               class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                               required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email Aktif (Terlock / Auto-fill) -->
                        <!-- Input Email Bebas Edit -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                                Email Aktif
                            </label>
                            <input type="email"
                                name="customer_email"
                                value="{{ old('customer_email', Auth::user()->email) }}"
                                placeholder="contoh@gmail.com"
                                class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                                required>
                            <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tighter">
                                *E-Ticket akan dikirim ke email ini
                            </p>
                        </div>
                        <!-- No. WhatsApp -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                                No. WhatsApp
                            </label>
                            <input type="tel"
                                   name="customer_phone"
                                   value="{{ old('customer_phone') }}"
                                   placeholder="08xxxxxxx"
                                   class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                                   required>
                        </div>
                    </div>

                    <!-- Tombol Submit Form -->
                    <button type="submit"
                            class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
                        Lanjut ke Pembayaran
                    </button>

                    <p class="text-center text-xs text-slate-400">
                        Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan kami.
                    </p>
                </form>
            </div>

        </div>
    </main>

    <!-- Script Pop-up Email Notice -->
    <script>
        function showEmailNotice() {
            Swal.fire({
                icon: 'info',
                title: 'Email Terkunci',
                text: 'Pemesanan tiket dikunci sesuai dengan email akun login Anda ({{ Auth::user()->email }}) agar E-Ticket dipastikan langsung masuk ke menu "Tiket Saya".',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Saya Mengerti'
            });
        }
    </script>

</body>

@endsection
