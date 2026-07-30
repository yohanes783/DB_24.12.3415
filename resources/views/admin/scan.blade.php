@extends('layouts.admin')

@section('page_title', 'Penjaga Pintu (Check-in Scanner)')
@section('page_subtitle', 'Scan QR Code tiket peserta di lokasi event untuk verifikasi otomatis')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <!-- Card Scanner utama -->
    <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100">
        
        <!-- Live Status Badge -->
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Kamera Aktif</span>
            </div>
            <span class="text-xs bg-indigo-50 text-indigo-600 font-bold px-3 py-1 rounded-full border border-indigo-100">
                Anti Double-Entry
            </span>
        </div>

        <!-- Frame Kamera Video Scanner -->
        <div id="reader" class="w-full rounded-2xl overflow-hidden bg-slate-900 min-h-[280px]"></div>

        <!-- Section Input Manual (Jika Kamera Bermasalah) -->
        <div class="mt-6 pt-6 border-t border-slate-100">
            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">
                Atau Ketik Order ID Secara Manual:
            </label>
            <div class="flex gap-2">
                <input type="text" id="manualCode" placeholder="Contoh: TRX-123456" 
                    class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 font-bold focus:outline-none focus:border-indigo-600 uppercase">
                <button onclick="processManualInput()" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl text-sm transition shadow-md">
                    Cek Tiket
                </button>
            </div>
        </div>
    </div>

    <!-- Informasi / Petunjuk Panitia -->
    <div class="bg-slate-100 rounded-2xl p-4 text-xs text-slate-600 space-y-2 border border-slate-200">
        <p class="font-bold text-slate-800">📌 Panduan Panitia Penjaga Pintu:</p>
        <ul class="list-disc list-inside space-y-1">
            <li>Arahkan kamera HP / Laptop tepat ke kode QR di layar HP peserta.</li>
            <li>Sistem akan menolak secara otomatis jika tiket <b>sudah pernah di-scan</b> sebelumnya.</li>
            <li>Gunakan fitur input manual jika layar HP peserta gelap/retak.</li>
        </ul>
    </div>
</div>

<!-- Library QR Scanner & SweetAlert2 -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let isProcessing = false;
    const csrfToken = "{{ csrf_token() }}";

    // Fungsi utama ketika QR Code terdeteksi oleh kamera
    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return; // Cegah ganda saat proses AJAX berlangsung
        
        isProcessing = true;
        playBeepSound();

        fetch("{{ route('admin.scan.process') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify({ qr_code: decodedText })
        })
        .then(async (response) => {
            const data = await response.json();
            return { status: response.status, body: data };
        })
        .then(res => {
            const data = res.body;

            if (res.status === 200) {
                // TIKET VALID & BELUM DIPAKAI
                Swal.fire({
                    icon: 'success',
                    title: '✅ SILAKAN MASUK',
                    html: `
                        <div class="text-left bg-slate-50 p-4 rounded-xl mt-3 text-slate-800 text-sm space-y-1 border border-slate-200">
                            <p><strong>Nama:</strong> ${data.data.name}</p>
                            <p><strong>Event:</strong> ${data.data.event}</p>
                            <p><strong>Order ID:</strong> ${data.data.order}</p>
                            <p><strong>Waktu Masuk:</strong> ${data.data.time}</p>
                        </div>
                    `,
                    confirmButtonText: 'Scan Berikutnya',
                    confirmButtonColor: '#16a34a',
                    allowOutsideClick: false
                }).then(() => { isProcessing = false; });

            } else if (res.status === 422) {
                // TIKET DOUBLE ENTRY / SUDAH DIPAKAI
                Swal.fire({
                    icon: 'error',
                    title: '🚫 AKSES DITOLAK!',
                    html: `
                        <p class="text-red-600 font-bold mb-2">${data.message}</p>
                        <div class="text-left bg-red-50 p-4 rounded-xl text-slate-800 text-sm space-y-1 border border-red-200">
                            <p><strong>Pemilik Tiket:</strong> ${data.data?.name ?? '-'}</p>
                            <p><strong>Event:</strong> ${data.data?.event ?? '-'}</p>
                            <p><strong>Di-scan Pada:</strong> ${data.data?.used_at ?? '-'}</p>
                        </div>
                    `,
                    confirmButtonText: 'Tolak Peserta Ini',
                    confirmButtonColor: '#dc2626',
                    allowOutsideClick: false
                }).then(() => { isProcessing = false; });

            } else {
                // KODE TIDAK VALID / BELUM LUNAS
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ TIKET TIDAK VALID',
                    text: data.message || 'Kode QR tidak terdaftar dalam sistem.',
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#f59e0b',
                    allowOutsideClick: false
                }).then(() => { isProcessing = false; });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Koneksi Terganggu',
                text: 'Pastikan koneksi internet aktif.',
            }).then(() => { isProcessing = false; });
        });
    }

    // Input Manual Handler
    function processManualInput() {
        const input = document.getElementById('manualCode');
        if (input.value.trim() !== '') {
            onScanSuccess(input.value.trim(), null);
            input.value = '';
        }
    }

    // Efek Suara Beep
    function playBeepSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(800, ctx.currentTime);
            osc.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.15);
        } catch(e){}
    }

    // Inisialisasi Scanner Kamera Belakang
    document.addEventListener("DOMContentLoaded", function () {
        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { 
                fps: 10, 
                qrbox: { width: 220, height: 220 },
                facingMode: "environment"
            },
            false
        );
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>
@endsection