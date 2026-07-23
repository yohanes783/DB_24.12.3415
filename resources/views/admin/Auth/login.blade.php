<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-indigo-900 text-white min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white text-slate-900 rounded-[2rem] p-8 shadow-2xl">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">AH</div>
            <h1 class="text-2xl font-black">Admin Login</h1>
            <p class="text-slate-500">AmikomEventHub Dashboard</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 text-red-600 p-4 rounded-xl mb-6 font-bold text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Email</label>
                <input type="email" name="email" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Password</label>
                <input type="password" name="password" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium" required>
            </div>
            <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">Masuk</button>
        </form>

        <!-- Garis Pembatas (Atau) -->
        <div class="relative flex py-5 items-center my-2">
            <div class="flex-grow border-t border-slate-200"></div>
            <span class="flex-shrink mx-4 text-slate-400 text-sm font-bold uppercase">atau</span>
            <div class="flex-grow border-t border-slate-200"></div>
        </div>

        <!-- Tombol Login Google -->
        <a href="{{ route('auth.google') }}" class="w-full py-4 bg-white border-2 border-slate-200 text-slate-700 rounded-2xl font-bold text-md flex items-center justify-center gap-3 shadow-md hover:bg-slate-50 hover:border-slate-300 transition-all duration-200">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#EA4335" d="M12 5.04c1.67 0 3.2.58 4.38 1.69l3.27-3.27C17.68 1.54 14.98 1 12 1 7.35 1 3.37 3.68 1.42 7.6l3.87 3C6.21 7.62 8.89 5.04 12 5.04z"/>
                <path fill="#4285F4" d="M23.49 12.27c0-.81-.07-1.59-.2-2.33H12v4.42h6.45c-.28 1.47-1.11 2.71-2.36 3.55l3.66 2.84c2.14-1.97 3.38-4.88 3.38-8.48z"/>
                <path fill="#FBBC05" d="M5.29 14.6c-.24-.72-.38-1.5-.38-2.3s.14-1.58.38-2.3L1.42 7.6C.51 9.42 0 11.45 0 12.6s.51 3.18 1.42 5l3.87-3z"/>
                <path fill="#34A853" d="M12 23c3.24 0 5.97-1.07 7.96-2.92l-3.66-2.84c-1.11.75-2.54 1.19-4.3 1.19-3.11 0-5.79-2.58-6.71-5.56l-3.87 3C3.37 20.32 7.35 23 12 23z"/>
            </svg>
            Masuk dengan Google
        </a>
    </div>
</body>
</html>
