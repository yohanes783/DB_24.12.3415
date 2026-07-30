<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Temukan Event Seru!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between">

    <!-- Navigation -->
    <nav class="glass sticky top-6 z-40 mx-4 md:mx-8 mt-4 px-6 py-4 rounded-2xl border border-white/40 shadow-lg shadow-slate-200/50 flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md shadow-indigo-200">
                AH
            </div>
            <span class="text-xl font-bold tracking-tight text-slate-800">AmikomEventHub</span>
        </a>

        <!-- Menu Tengah -->
        <div class="hidden md:flex gap-8 font-medium text-slate-600">
            <a href="{{ route('home') }}" class="text-indigo-600 font-semibold hover:text-indigo-700 transition">Jelajahi</a>
            <a href="#events" class="hover:text-indigo-600 transition">Event</a>
            <a href="#kategori" class="hover:text-indigo-600 transition">Kategori</a>
        </div>

        <!-- Area User / Login / Logout -->
        <div class="flex items-center gap-3">
            @auth
                {{-- TOMBOL DASHBOARD PARTNER: Muncul Khusus Akun Partner yang Approved --}}
                @if(Auth::user()->role === 'partner' && Auth::user()->partner?->status === 'approved')
                    <a href="{{ route('partner.dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl font-bold text-xs md:text-sm transition shadow-md shadow-indigo-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard Partner
                    </a>
                @endif

                <!-- Tiket Saya -->
                <a href="{{ route('ticket') }}" class="font-bold text-slate-700 hover:text-indigo-600 transition text-sm md:text-base px-2">
                    Tiket Saya
                </a>

                <!-- Nama User -->
                <div class="hidden sm:flex items-center gap-2 bg-slate-100 text-slate-700 px-4 py-2 rounded-xl font-bold text-sm">
                    <span>{{ Auth::user()->name }}</span>
                </div>

                <!-- Tombol Logout -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-xl font-bold text-sm transition">
                        Logout
                    </button>
                </form>
            @else
                <!-- Tampilan Jika User BELUM LOGIN -->
                <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold transition shadow-md shadow-indigo-200">
                    Masuk
                </a>
            @endauth
        </div>
    </nav>

    <!-- Content Utama -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-indigo-950 text-indigo-100 py-16 px-6 mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="space-y-4 col-span-1">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-950 font-bold text-xl">
                        AH
                    </div>
                    <span class="text-2xl font-bold text-white">AmikomEventHub</span>
                </div>
                <p class="max-w-xs text-indigo-300/80 text-sm leading-relaxed">
                    Platform reservasi tiket event online terbaik untuk mahasiswa dan penyelenggara profesional.
                </p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Kategori</h4>
                <ul class="space-y-3 text-sm text-indigo-200/80">
                    @if(isset($categories))
                        @foreach($categories as $cat)
                            <li><a href="/?category={{ $cat->slug }}" class="hover:text-white transition">{{ $cat->name }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Navigasi</h4>
                <ul class="space-y-3 text-sm text-indigo-200/80">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                    <li><a href="{{ route('home') }}#events" class="hover:text-white transition">Semua Event</a></li>
                    <li><a href="{{ route('partner.register') }}" class="hover:text-white transition">Daftar Partner</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
                <ul class="space-y-3 text-sm text-indigo-200/80">
                    <li>support@amikomeventhub.com</li>
                    <li>+62 812 3456 7890</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-8 mt-12 border-t border-indigo-900/60 text-center text-indigo-400/60 text-xs">
            &copy; 2026 AmikomEventHub. Built with Laravel & Tailwind CSS.
        </div>
    </footer>

</body>
</html>