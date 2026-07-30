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
                <span id="status-dot" class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></span>
                <span id="status-text" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Menyiapkan Kamera...</span>
            </div>
            <span class="text-xs bg-indigo-50 text-indigo-600 font-bold px-3 py-1 rounded-full border border-indigo-100">
                Anti Double-Entry
            </span>
        </div>

        <!-- Frame Kamera Video Scanner -->
        <div class="relative w-full rounded-2xl overflow-hidden bg-slate-900 border-4 border-slate-900 shadow-inner" style="aspect-ratio: 4/3;">
            <video id="video" class="w-full h-full object-cover" playsinline></video>
            <canvas id="canvas" class="hidden"></canvas>
            
            <!-- Overlay Bingkai Visual Scan -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="relative w-3/5 h-3/5 border-2 border-dashed border-white/50 rounded-2xl shadow-[0_0_0_400px_rgba(0,0,0,0.5)]">
                    <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-emerald-500 rounded-tl-lg"></div>
                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-emerald-500 rounded-tr-lg"></div>
                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-emerald-500 rounded-bl-lg"></div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-emerald-500 rounded-br-lg"></div>
                    <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500 shadow-[0_0_10px_2px_#10b981] animate-scan-laser"></div>
                </div>
            </div>
        </div>

        <!-- Section Input Manual -->
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
            <li>Arahkan kamera tepat ke QR Code tiket.</li>
            <li>Sistem akan menolak secara otomatis jika tiket <b>sudah pernah di-scan</b>.</li>
            <li>Gunakan fitur input manual jika layar HP peserta gelap/retak.</li>
        </ul>
    </div>
</div>

<style>
    @keyframes scan-laser {
        0% { top: 0%; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
    .animate-scan-laser {
        position: absolute;
        animation: scan-laser 2s linear infinite;
    }
</style>

<!-- Library jsQR (Paling universal) & SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    const statusDot = document.getElementById('status-dot');
    const statusText = document.getElementById('status-text');
    const csrfToken = "{{ csrf_token() }}";

    let isProcessing = false;

    // Awal pembukaan kamera
    navigator.mediaDevices.getUserMedia({ 
        video: { 
            facingMode: 'environment',
            width: { ideal: 1280 },
            height: { ideal: 720 }
        } 
    })
    .then(stream => {
        video.srcObject = stream;
        video.setAttribute("playsinline", true);
        video.play();
        requestAnimationFrame(tick);
        
        statusDot.className = "w-3 h-3 rounded-full bg-emerald-500 animate-ping";
        statusText.innerText = "Kamera Aktif - Siap Scan";
    })
    .catch(err => {
        console.error("Akses Kamera Gagal:", err);
        statusDot.className = "w-3 h-3 rounded-full bg-red-500";
        statusText.innerText = "Kamera Tidak Diizinkan / Tidak Ada";
    });

    // Loop Frame Decoder Ultra Fast
    function tick() {
        if (video.readyState === video.HAVE_ENOUGH_DATA && !isProcessing) {
            canvas.height = video.videoHeight;
            canvas.width = video.videoWidth;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: "dontInvert",
            });

            if (code && code.data && code.data.trim() !== "") {
                onScanSuccess(code.data.trim());
            }
        }
        requestAnimationFrame(tick);
    }

    // Mengirim Data ke Backend Laravel
    function onScanSuccess(decodedText) {
        if (isProcessing) return;
        
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

    function processManualInput() {
        const input = document.getElementById('manualCode');
        if (input.value.trim() !== '') {
            onScanSuccess(input.value.trim());
            input.value = '';
        }
    }

    function playBeepSound() {
        try {
            const ctxAudio = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctxAudio.createOscillator();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(800, ctxAudio.currentTime);
            osc.connect(ctxAudio.destination);
            osc.start();
            osc.stop(ctxAudio.currentTime + 0.15);
        } catch(e){}
    }
</script>
@endsection