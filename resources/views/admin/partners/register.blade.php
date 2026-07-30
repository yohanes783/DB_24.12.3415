<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Sebagai Penyelenggara Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex items-center justify-center p-4 py-12">

    <div class="max-w-xl w-full bg-white p-8 rounded-3xl border border-slate-100 shadow-xl">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar Sebagai Penyelenggara Event (Partner / HIMA)</h1>
        <p class="text-slate-500 text-sm mt-1 mb-6">Isi formulir di bawah ini untuk mengajukan organisasi/kepanitiaan kamu.</p>

        {{-- Pesan Error Validasi --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl text-xs text-rose-700">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('partner.register.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- 1. JIKA BELUM LOGIN: Wajib Tampilkan Input Akun Pengguna --}}
            @guest
                <div class="space-y-4 border-b border-slate-100 pb-5">
                    <h2 class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Informasi Akun Pemilik</h2>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap Penanggung Jawab</label>
                        <input type="text" name="user_name" value="{{ old('user_name') }}" required 
                               placeholder="Contoh: Budi Santoso"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               placeholder="budi@gmail.com"
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                            <input type="password" name="password" required placeholder="••••••••"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required placeholder="••••••••"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-indigo-50 p-3 rounded-xl text-xs text-indigo-800 font-medium">
                    Mendaftar sebagai: <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})
                </div>
            @endguest

            {{-- 2. Input Data Organisasi / Partner --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Organisasi / HIMA / Komunitas</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           placeholder="Sarumaka Coffee"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat Organisasi</label>
                    <textarea name="description" rows="4" required 
                              placeholder="Coffeshop dengan banyak rasa..."
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">{{ old('description') }}</textarea>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition shadow-md">
                Kirim Pengajuan Partner
            </button>
        </form>
    </div>

</body>
</html>