@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">

    <!-- SISI KIRI: Form Tambah Kategori -->
    <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 mb-1">Tambah Kategori Baru</h3>
        <p class="text-xs text-slate-400 mb-6">Buat klasifikasi jenis event baru di platform.</p>

        <form action="/admin/category" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Kategori</label>
                <input type="text" name="name" id="name" required
                       placeholder="Contoh: Webinar, Workshop, Konser"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition @error('name') border-rose-500 @enderror">
                @error('name')
                    <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition text-sm shadow-sm">
                Simpan Kategori
            </button>
        </form>
    </div>

    <!-- SISI KANAN: Pencarian & Daftar Tabel -->
    <div class="xl:col-span-2 space-y-4">

        <!-- Filter & Search Bar -->
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm flex flex-col sm:flex-row gap-3 justify-between items-center">
            <form action="{{ request()->url() }}" method="GET" class="w-full sm:max-w-md relative">
                <input type="text" name="search" value="{{ $search ?? request('search') }}"
                       placeholder="Cari kategori berdasarkan nama..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                <div class="absolute left-3 top-3 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </form>
            @if(request('search'))
                <a href="{{ request()->url() }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">Reset Pencarian</a>
            @endif
        </div>

        <!-- Tabel Data Kategori -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold uppercase tracking-widest text-slate-400">
                            <th class="py-4 px-6">ID</th>
                            <th class="py-4 px-6">Nama Kategori</th>
                            <th class="py-4 px-6">Dibuat Pada</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/40 transition">
                                <td class="py-4 px-6 font-bold text-indigo-600">#CAT-{{ $category->id }}</td>
                                <td class="py-4 px-6 font-bold text-slate-900">{{ $category->name }}</td>
                                <td class="py-4 px-6 text-slate-400 text-xs font-normal">
                                    {{ $category->created_at ? $category->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Tombol Pemicu Modal Edit -->
                                        <button type="button" onclick="openEditModal('{{ $category->id }}', '{{ $category->name }}')"
                                                class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-xl text-xs font-bold transition">
                                            Edit
                                        </button>

                                        <!-- Form Hapus -->
                                        <form action="/admin/category/{{ $category->id }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400 font-medium">
                                    @if(request('search'))
                                        Tidak ada kategori yang cocok dengan pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data kategori di database.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT DATA (Tailwind Native) -->
<div id="tailwindEditModal" class="fixed inset-0 z-50 hidden bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100 transform transition-all">
        <div class="flex justify-between items-center mb-5">
            <h4 class="text-lg font-bold text-slate-900">Edit Nama Kategori</h4>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="editCategoryForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="modal_category_name" class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Kategori</label>
                <input type="text" name="name" id="modal_category_name" required
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2.5 text-slate-500 hover:bg-slate-50 font-bold rounded-xl text-sm transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition shadow-sm">
                    Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, currentName) {
        const modal = document.getElementById('tailwindEditModal');
        const form = document.getElementById('editCategoryForm');
        const input = document.getElementById('modal_category_name');

        form.action = '/admin/category/' + id;
        input.value = currentName;
        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        const modal = document.getElementById('tailwindEditModal');
        modal.classList.add('hidden');
    }
</script>
@endsection
