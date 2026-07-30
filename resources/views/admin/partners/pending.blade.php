<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Menunggu Persetujuan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white p-8 rounded-3xl border border-slate-100 shadow-xl text-center space-y-5">
        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mx-auto border border-amber-100">
            <svg class="w-8 h-8 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <div>
            <h1 class="text-xl font-bold text-slate-900">Pendaftaran Dalam Peninjauan</h1>
            <p class="text-slate-500 text-sm mt-2">
                Terima kasih telah mendaftar! Akun organisasi kamu sedang ditinjau oleh <span class="font-bold text-slate-700">Superadmin</span>.
            </p>
        </div>

        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs text-slate-500">
            Silakan cek secara berkala atau hubungi tim pengelola platform jika pendaftaran belum diproses dalam 1x24 jam.
        </div>

        <div class="pt-2">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                    Keluar / Logout
                </button>
            </form>
        </div>
    </div>

</body>
</html>