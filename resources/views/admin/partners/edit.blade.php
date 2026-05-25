@extends('layouts.admin')

@section('page_title', 'Edit Partner')
@section('page_subtitle', 'Perbarui informasi partner atau sponsor event AmikomEventHub')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Tombol Kembali Minimalis -->
    <div class="mb-6">
        <a href="{{ route('admin.partners.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Partner
        </a>
    </div>

    <!-- Kotak Utama Formulir (Grid 2 Kolom untuk Efisiensi Ruang) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden grid grid-cols-1 md:grid-cols-3">

        <!-- SISI KIRI: Formulir Input (Lebih Luas) -->
        <div class="p-8 md:col-span-2 border-b md:border-b-0 md:border-r border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Informasi Partner</h3>
            <p class="text-xs text-slate-400 mb-6">Ubah data identitas dan tautan aset visual partner.</p>

            <form action="{{ route('admin.partners.update', $partner->id ?? 1) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Input Nama Partner -->
                <div>
                    <label for="name" class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Partner</label>
                    <input type="text" name="name" id="name" required
                           value="{{ $partner->name ?? 'aws gowes' }}"
                           placeholder="Contoh: Amazon Web Services"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>

                <!-- Input URL Logo -->
                <div>
                    <label for="logo_url" class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">URL Logo Partner</label>
                    <input type="url" name="logo_url" id="logo_url" required
                           value="{{ $partner->logo_url ?? 'https://wikimedia.org' }}"
                           placeholder="https://example.com"
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition"
                           oninput="updatePreview(this.value)">
                    <p class="text-[11px] text-slate-400 mt-1.5">Gunakan URL gambar/logo partner yang valid dari internet.</p>
                </div>

                <!-- Tombol Aksi Akhir -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-50">
                    <a href="{{ route('admin.partners.index') }}"
                       class="px-5 py-3 text-slate-500 hover:bg-slate-50 font-bold rounded-xl text-sm transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-indigo-100">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- SISI KANAN: Real-time Box Preview Logo (Lebih Elegan) -->
        <div class="p-8 bg-slate-50/50 flex flex-col items-center justify-center text-center">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-4 block">Preview Logo</span>

            <!-- Frame Logo Bulat/Kotak dengan Bayangan Halus -->
            <div class="w-40 h-40 bg-white border border-slate-100 rounded-2xl p-4 shadow-sm flex items-center justify-center transition-all duration-300 transform hover:scale-105">
                <img id="logo-preview-img"
                     src="{{ $partner->logo_url ?? 'https://wikimedia.org' }}"
                     class="max-w-full max-h-full object-contain rounded-xl"
                     onerror="this.src='https://placehold.co'">
            </div>

            <p class="text-xs text-slate-400 mt-4 max-w-[180px]">Logo akan otomatis menyesuaikan aspek rasio bingkai.</p>
        </div>

    </div>
</div>

<!-- JavaScript Ringan untuk Pembaruan Preview Gambar Otomatis -->
<script>
    function updatePreview(url) {
        const previewImg = document.getElementById('logo-preview-img');
        if(url.trim() !== "") {
            previewImg.src = url;
        } else {
            previewImg.src = 'https://placehold.co';
        }
    }
</script>
@endsection
