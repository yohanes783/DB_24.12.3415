<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-slate-50 to-indigo-100 min-h-screen flex items-center justify-center p-4 sm:p-6">

    <!-- Container Card (Sudut Sangat Melengkung & Border Tipis Ungu) -->
    <div x-data="{ tab: '{{ old('name') || $errors->has('name') ? 'register' : 'login' }}' }"
         class="max-w-md w-full bg-slate-50/70 backdrop-blur-xl border border-indigo-200/60 rounded-[2.5rem] p-6 sm:p-8 shadow-2xl shadow-indigo-100 relative overflow-hidden">

        <!-- Header & Logo -->
        <div class="text-center mb-6">
            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl mx-auto mb-3 shadow-md shadow-indigo-200">
                <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Welcome Back</h1>
            <span class="inline-block mt-1 px-4 py-1 bg-indigo-100/70 text-indigo-700 rounded-full text-[11px] font-bold tracking-wider uppercase">
                AMIKOMEVENTHUB
            </span>
        </div>

        <!-- Session Alert -->
        @if(session('error'))
            <div class="mb-4 p-3.5 bg-red-50 border border-red-200 text-red-600 text-xs font-semibold rounded-2xl text-center">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3.5 bg-red-50 border border-red-200 text-red-600 text-xs font-semibold rounded-2xl">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- TAB SWITCHER (Login / Register) -->
        <div class="p-1.5 bg-slate-200/50 rounded-2xl flex items-center mb-6 border border-slate-200/60">
            <button @click="tab = 'login'"
                    :class="tab === 'login' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                    class="w-1/2 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 text-center">
                Login
            </button>
            <button @click="tab = 'register'"
                    :class="tab === 'register' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                    class="w-1/2 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 text-center">
                Register
            </button>
        </div>

        <!-- FORM LOGIN -->
        <form x-show="tab === 'login'" action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">EMAIL ADDRESS</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email kamu"
                       class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none transition font-medium" required>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">PASSWORD</label>
                <input type="password" name="password" placeholder="••••••••"
                       class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none transition font-medium" required>
            </div>

            <!-- Google Login Button (Di atas ATAU) -->
            <a href="{{ route('auth.google') }}" class="w-full py-3.5 px-4 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-sm rounded-2xl flex items-center justify-center gap-3 transition shadow-sm mt-2">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Continue with Google</span>
            </a>

            <!-- Divider ATAU -->
            <div class="relative my-4 text-center">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                <span class="relative bg-slate-50/90 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ATAU</span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-base shadow-lg shadow-indigo-500/25 transition duration-200">
                Masuk
            </button>
        </form>

        <!-- FORM REGISTER -->
        <form x-show="tab === 'register'" action="{{ route('register') }}" method="POST" class="space-y-4" style="display: none;">
            @csrf
            <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">NAMA LENGKAP</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama kamu"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none transition font-medium" required>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">EMAIL ADDRESS</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none transition font-medium" required>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">PASSWORD</label>
                <input type="password" name="password" placeholder="••••••••"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none transition font-medium" required>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-400 mb-1.5 uppercase tracking-wider">KONFIRMASI PASSWORD</label>
                <input type="password" name="password_confirmation" placeholder="••••••••"
                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-800 placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none transition font-medium" required>
            </div>

            <!-- Google Register Button -->
            <a href="{{ route('auth.google') }}" class="w-full py-3 px-4 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-sm rounded-2xl flex items-center justify-center gap-3 transition shadow-sm mt-1">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Daftar dengan Google</span>
            </a>

            <!-- Divider -->
            <div class="relative my-3 text-center">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                <span class="relative bg-slate-50/90 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ATAU MANUAL</span>
            </div>

            <!-- Register Button -->
            <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-base shadow-lg shadow-indigo-500/25 transition duration-200">
                Daftar Akun
            </button>
        </form>

    </div>

</body>
</html>
