<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk / Daftar - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex items-center justify-center p-4 md:p-6">

    <div class="max-w-md w-full bg-white rounded-3xl p-8 shadow-2xl border border-slate-100">

        <!-- Header Logo -->
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-200">
                    AH
                </div>
            </a>
            <h2 class="text-2xl font-extrabold text-slate-900 mt-3">AmikomEventHub</h2>
            <p class="text-xs text-slate-400 mt-1">Akses semua event seru dalam satu akun</p>
        </div>

        <!-- Tab Switcher (Login / Register) -->
        <div class="flex bg-slate-100 p-1.5 rounded-2xl mb-6" id="authTabs">
            <button type="button" onclick="switchTab('login')" id="tab-login"
                class="flex-1 py-2.5 text-sm font-bold rounded-xl transition-all duration-200 bg-white text-indigo-600 shadow-sm">
                Masuk
            </button>
            <button type="button" onclick="switchTab('register')" id="tab-register"
                class="flex-1 py-2.5 text-sm font-bold rounded-xl transition-all duration-200 text-slate-500 hover:text-slate-800">
                Daftar Akun
            </button>
        </div>

        <!-- Alert Notification -->
        @if(session('error'))
            <div class="bg-red-50 text-red-600 p-3.5 rounded-xl mb-5 font-semibold text-xs text-center border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-3.5 rounded-xl mb-5 text-xs border border-red-100">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ==================== FORM 1: LOGIN ==================== -->
        <form id="form-login" action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:bg-white outline-none transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:bg-white outline-none transition text-sm">
            </div>

            <div class="flex items-center justify-between text-xs py-1">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition text-sm">
                Masuk
            </button>
        </form>

        <!-- ==================== FORM 2: REGISTER ==================== -->
        <form id="form-register" action="{{ route('register') }}" method="POST" class="space-y-4 hidden">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="John Doe"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:bg-white outline-none transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:bg-white outline-none transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Password</label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:bg-white outline-none transition text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wider">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-600 focus:bg-white outline-none transition text-sm">
            </div>

            <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition text-sm">
                Buat Akun Baru
            </button>
        </form>

        <!-- Garis Pembatas (Atau) -->
        <div class="relative flex py-4 items-center my-1">
            <div class="flex-grow border-t border-slate-100"></div>
            <span class="flex-shrink mx-3 text-slate-400 text-[10px] font-bold uppercase tracking-wider">atau</span>
            <div class="flex-grow border-t border-slate-100"></div>
        </div>

        <!-- Tombol Google -->
        <a href="{{ route('auth.google') }}" class="w-full py-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 rounded-xl font-bold text-xs flex items-center justify-center gap-2.5 transition">
            <svg class="w-4 h-4" viewBox="0 0 24 24">
                <path fill="#EA4335" d="M12 5.04c1.67 0 3.2.58 4.38 1.69l3.27-3.27C17.68 1.54 14.98 1 12 1 7.35 1 3.37 3.68 1.42 7.6l3.87 3C6.21 7.62 8.89 5.04 12 5.04z"/>
                <path fill="#4285F4" d="M23.49 12.27c0-.81-.07-1.59-.2-2.33H12v4.42h6.45c-.28 1.47-1.11 2.71-2.36 3.55l3.66 2.84c2.14-1.97 3.38-4.88 3.38-8.48z"/>
                <path fill="#FBBC05" d="M5.29 14.6c-.24-.72-.38-1.5-.38-2.3s.14-1.58.38-2.3L1.42 7.6C.51 9.42 0 11.45 0 12.6s.51 3.18 1.42 5l3.87-3z"/>
                <path fill="#34A853" d="M12 23c3.24 0 5.97-1.07 7.96-2.92l-3.66-2.84c-1.11.75-2.54 1.19-4.3 1.19-3.11 0-5.79-2.58-6.71-5.56l-3.87 3C3.37 20.32 7.35 23 12 23z"/>
            </svg>
            Lanjutkan dengan Google
        </a>

        <!-- Footer Link Partner -->
        <div class="mt-6 pt-4 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-500">
                Penyelenggara Event / HIMA / UKM?
                <a href="{{ route('partner.register') }}" class="text-indigo-600 font-bold hover:underline">Daftar Partner</a>
            </p>
        </div>

    </div>

    <!-- Script Switcher Tab Login / Register -->
    <script>
        function switchTab(type) {
            const formLogin = document.getElementById('form-login');
            const formRegister = document.getElementById('form-register');
            const tabLogin = document.getElementById('tab-login');
            const tabRegister = document.getElementById('tab-register');

            if (type === 'login') {
                formLogin.classList.remove('hidden');
                formRegister.classList.add('hidden');

                tabLogin.className = "flex-1 py-2.5 text-sm font-bold rounded-xl transition-all duration-200 bg-white text-indigo-600 shadow-sm";
                tabRegister.className = "flex-1 py-2.5 text-sm font-bold rounded-xl transition-all duration-200 text-slate-500 hover:text-slate-800";
            } else {
                formLogin.classList.add('hidden');
                formRegister.classList.remove('hidden');

                tabRegister.className = "flex-1 py-2.5 text-sm font-bold rounded-xl transition-all duration-200 bg-white text-indigo-600 shadow-sm";
                tabLogin.className = "flex-1 py-2.5 text-sm font-bold rounded-xl transition-all duration-200 text-slate-500 hover:text-slate-800";
            }
        }
    </script>
</body>
</html>
