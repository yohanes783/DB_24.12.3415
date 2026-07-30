@extends('layouts.admin')

@section('page_title', 'Penjaga Pintu (Check-in Scanner Ultra)')
@section('page_subtitle', 'Scan QR Code super cepat menggunakan Engine Bawaan Browser')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <!-- Card Scanner utama -->
    <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100">
        
        <!-- Header & Status -->
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span id="status-icon" class="w-3 h-3 rounded-full bg-slate-400"></span>
                <span id="status-text" class="text-xs font-bold text-slate-700 uppercase tracking-wider">Menyiapkan Kamera...</span>
            </div>
            <span class="text-xs bg-indigo-50 text-indigo-600 font-bold px-3 py-1 rounded-full border border-indigo-100">
                Ultra Scan Engine
            </span>
        </div>

        <!-- Container Video & Overlay -->
        <div class="relative w-full rounded-2xl overflow-hidden bg-slate-900 border-4 border-slate-900 shadow-inner" style="aspect-ratio: 4/3;">
            <!-- Element Video Mentah -->
            <video id="video" class="w-full h-full object-cover"></video>
            
            <!-- Overlay Bingkai Scan (CSS Pure) -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="relative w-3/5 h-3/5 border-2 border-dashed border-white/50 rounded-2xl shadow-[0_0_0_400px_rgba(0,0,0,0.5)]">
                    <!-- Sudut-sudut ikonik -->
                    <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-emerald-500 rounded-tl-lg"></div>
                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-emerald-500 rounded-tr-lg"></div>
                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-emerald-500 rounded-bl-lg"></div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-emerald-500 rounded-br-lg"></div>
                    
                    <!-- Garis Laser Scanning -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-emerald-500 shadow-[0_0_10px_2px_#10b981] animate-scan-laser"></div>
                </div>
            </div>
        </div>

        <!-- Tombol Kontrol (Opsional, muncul jika ada banyak kamera) -->
        <div id="camera-controls" class="mt-4 hidden flex justify-center">
            <button id="switch-camera" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold px-4 py-2 rounded-full border border-slate-200 flex items-center gap-2">
                🔄 Ganti Kamera
            </button>
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
</div>

<!-- Tambahkan CSS untuk Animasi Laser -->
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

<!-- Hanya butuh SweetAlert2, library QR dibuang -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const video = document.getElementById('video');
    const statusIcon = document.getElementById('status-icon');
    const statusText = document.getElementById('status-text');
    const csrfToken = "{{ csrf_token() }}";
    
    let isProcessing = false;
    let scannerInterval = null;
    let currentStream = null;
    let barcodeDetector = null;

    // --- JURUS PAMUNGKAS: Cek Dukungan BarcodeDetector API ---
    async function checkBarcodeDetectorSupport() {
        if (!('BarcodeDetector' in window)) {
            Swal.fire({
                icon: 'error',
                title: 'Browser Tidak Mendukung',
                text: 'Browser Anda tidak mendukung Ultra Scan Engine. Gunakan Google Chrome versi terbaru.',
            });
            updateStatus('error', 'Browser Tidak Support');
            return false;
        }

        // Cek apakah format QR Code didukung oleh OS/Browser
        const formats = await BarcodeDetector.getSupportedFormats();
        if (!formats.includes('qr_code')) {
            updateStatus('error', 'Format QR Tidak Didukung OS');
            return false;
        }

        // Inisialisasi Detector murni untuk QR Code
        barcodeDetector = new BarcodeDetector({ formats: ['qr_code'] });
        return true;
    }

    // --- Fungsi Mulai Kamera Belakang (High Res) ---
    async function startCamera() {
        if(currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }

        updateStatus('loading', 'Membuka Kamera...');

        const constraints = {
            video: {
                facingMode: 'environment', // Wajib Kamera Belakang
                width: { ideal: 1280 },    // Minta Resolusi HD
                height: { ideal: 720 }
            }
        };

        try {
            currentStream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = currentStream;
            video.setAttribute('playsinline', true); // Penting untuk iOS
            await video.play();
            
            updateStatus('active', 'Kamera Aktif - Siap Scan');
            startScanningLoop(); // Mulai loop pemindaian
        } catch (err) {
            console.error("Gagal akses kamera:", err);
            updateStatus('error', 'Gagal Akses Kamera. Cek Izin Browser.');
        }
    }

    // --- JURUS PAMUNGKAS: LOOP PEMINDAIAN ULTRA CEPAT ---
    function startScanningLoop() {
        if (scannerInterval) clearInterval(scannerInterval);

        // Jalankan deteksi setiap 100ms (10 kali per detik) - Sangat responsif
        scannerInterval = setInterval(async () => {
            if (isProcessing || !barcodeDetector || video.readyState !== video.HAVE_ENOUGH_DATA) return;

            try {
                // Deteksi Barcode/QR langsung dari element Video murni
                const barcodes = await barcodeDetector.detect(video);
                
                if (barcodes.length > 0) {
                    const qrData = barcodes[0].rawValue; // Ambil teks mentah QR
                    if (qrData) {
                        onScanSuccess(qrData); // Kirim ke Backend
                    }
                }
            } catch (err) {
                // Abaikan error deteksi sementara
            }
        }, 100);
    }

    // --- Fungsi Kirim ke Backend (Sama seperti dulu) ---
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
                showResult('success', '✅ SILAKAN MASUK', data.data);
            } else if (res.status === 422) {
                showResult('error', '🚫 AKSES DITOLAK!', data.data, data.message);
            } else {
                showResult('warning', '⚠️ TIKET TIDAK VALID', null, data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showResult('error', 'Koneksi Terganggu', null, 'Pastikan internet aktif.');
        });
    }

    // --- Helper UI ---
    function updateStatus(type, text) {
        statusText.innerText = text;
        statusIcon.className = 'w-3 h-3 rounded-full ';
        if (type === 'active') statusIcon.classList.add('bg-emerald-500', 'animate-ping');
        else if (type === 'error') statusIcon.classList.add('bg-red-500');
        else if (type === 'loading') statusIcon.classList.add('bg-amber-500', 'animate-pulse');
        else statusIcon.classList.add('bg-slate-400');
    }

    function showResult(icon, title, data, message = '') {
        let htmlContent = '';
        if (data) {
            htmlContent = `
                <div class="text-left bg-slate-50 p-4 rounded-xl mt-3 text-slate-800 text-sm space-y-1 border border-slate-200">
                    <p><strong>Nama:</strong> ${data.name || '-'}</p>
                    <p><strong>Event:</strong> ${data.event || '-'}</p>
                    <p><strong>Order ID:</strong> ${data.order || data.order_id || '-'}</p>
                    ${data.time ? `<p><strong>Waktu:</strong> ${data.time}</p>` : ''}
                    ${data.used_at ? `<p class="text-red-600"><strong>Di-scan Pada:</strong> ${data.used_at}</p>` : ''}
                </div>`;
        }
        if (message && icon !== 'success') htmlContent = `<p class="text-red-600 font-bold mb-2">${message}</p>` + htmlContent;

        Swal.fire({
            icon: icon,
            title: title,
            html: htmlContent,
            confirmButtonText: 'Scan Berikutnya',
            confirmButtonColor: '#4f46e5',
            allowOutsideClick: false
            
        }).then(() => {
            isProcessing = false; // Buka kunci proses setelah pop-up ditutup
        });
    }

    function processManualInput() {
        const input = document.getElementById('manualCode');
        const code = input.value.trim();
        if (code !== '') {
            input.value = '';
            onScanSuccess(code);
        }
    }

    function playBeepSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            osc.frequency.setValueAtTime(800, ctx.currentTime);
            osc.connect(ctx.destination);
            osc.start(); osc.stop(ctx.currentTime + 0.1);
        } catch(e){}
    }

    // --- Jalankan Jurus Pamungkas saat halaman siap ---
    document.addEventListener("DOMContentLoaded", async function () {
        const supported = await checkBarcodeDetectorSupport();
        if (supported) {
            startCamera();
        }
    });
</script>
@endsection