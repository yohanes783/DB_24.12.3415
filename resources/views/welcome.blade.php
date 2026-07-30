@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="max-w-7xl mx-auto px-6 py-16 md:py-20 flex flex-col md:flex-row items-center gap-12">
    <div class="flex-1 space-y-8">
        <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">
            #1 Event Platform Yogyakarta
        </span>
        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight text-slate-900">
            Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu.
        </h1>
        <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
            Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan Midtrans.
        </p>
        <div class="flex gap-4">
            <a href="#events" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">
                Mulai Jelajah
            </a>
            <a href="#" class="px-8 py-4 border-2 border-slate-200 rounded-2xl font-bold text-lg hover:border-indigo-600 hover:text-indigo-600 transition">
                Cara Pesan
            </a>
        </div>
    </div>

    <div class="flex-1 relative w-full">
        <div class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

        <!-- Hero Event Image Banner -->
        <div class="w-full aspect-[16/9] md:aspect-[4/3] rounded-[2.5rem] overflow-hidden shadow-2xl border-8 border-white bg-slate-100 relative">
            @if($events->count() > 0 && $events->first()->poster_path)
                <img src="{{ asset('storage/' . $events->first()->poster_path) }}"
                     alt="{{ $events->first()->title }}"
                     class="w-full h-full object-cover">
            @else
                <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=1200&q=80"
                     alt="Default Event Banner"
                     class="w-full h-full object-cover">
            @endif
        </div>

        <div class="absolute -bottom-6 -left-6 glass p-6 rounded-2xl shadow-xl z-20 border border-white bg-white/80 backdrop-blur-md">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase">Terverifikasi</p>
                    <p class="font-bold text-slate-800">Pembayaran Aman via Midtrans</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Events Grid Section -->
<section id="events" class="max-w-7xl mx-auto px-6 py-12 md:py-16">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6">
        <div>
            <h2 class="text-3xl font-extrabold mb-2 text-slate-800">Event Terdekat</h2>
            <p class="text-slate-500 font-medium">Jangan sampai ketinggalan acara seru mendatang!</p>
        </div>

        <!-- Filter Tab Kategori -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ url()->current() }}"
               class="px-4 py-2 rounded-xl text-sm font-bold transition {{ !request('category') ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Kategori
            </a>
            @foreach($categories as $cat)
                <a href="?category={{ $cat->slug }}"
                   class="px-4 py-2 rounded-xl text-sm font-bold transition {{ request('category') == $cat->slug ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Grid Event List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
        @forelse($events as $event)
            <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="relative overflow-hidden aspect-[4/3] bg-slate-100">
                        <img src="{{ $event->poster_path ? asset('storage/' . $event->poster_path) : 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=600&q=80' }}"
                             alt="{{ $event->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600 shadow-sm">
                            {{ $event->category->name ?? 'Umum' }}
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-slate-800 group-hover:text-indigo-600 transition line-clamp-2">
                            {{ $event->title }}
                        </h3>
                        <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }} • {{ $event->time }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-6 pt-0 border-t border-slate-50 mt-auto">
                    <div class="flex justify-between items-center pt-4">
                        <div>
                            <span class="text-xs text-slate-400 block font-semibold">Harga Tiket</span>
                            <span class="text-xl font-black text-indigo-600">
                                {{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('events.show', $event->id) }}" class="px-5 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition text-sm">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Event Tersedia</h3>
                <p class="text-slate-400 text-sm">Belum ada event untuk kategori ini. Silakan cek kembali nanti!</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Section Testimoni Peserta -->
<section class="border-t border-slate-100 bg-white py-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-xs font-bold uppercase tracking-widest text-indigo-600 mb-2">Ulasan Peserta</h2>
            <p class="text-3xl font-black text-slate-800">Apa Kata Mereka Tentang Event Kami?</p>
        </div>

        @if(!isset($reviews) || $reviews->isEmpty())
            <div class="text-center py-8">
                <p class="text-sm text-slate-400 font-medium">Belum ada ulasan peserta yang ditampilkan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reviews as $review)
                    <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="text-amber-400 text-base mb-3 flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        ★
                                    @else
                                        <span class="text-slate-200">★</span>
                                    @endif
                                @endfor
                            </div>

                            <p class="text-slate-600 text-sm leading-relaxed italic mb-6">
                                "{{ $review->comment }}"
                            </p>
                        </div>

                        <div class="border-t border-slate-200/60 pt-4 flex items-center justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">{{ $review->user->name ?? 'Peserta' }}</h4>
                                <p class="text-[11px] text-indigo-600 font-semibold truncate max-w-[180px]">
                                    {{ $review->event->title ?? 'Event' }}
                                </p>
                            </div>
                            <span class="text-[10px] text-slate-400 font-medium">
                                {{ $review->created_at ? $review->created_at->diffForHumans() : '' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- Section Partner & Sponsor -->
<section class="py-16 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-xs font-bold text-indigo-600 tracking-wider uppercase mb-1">DIDUKUNG OLEH</p>
        <h2 class="text-2xl font-extrabold text-slate-800 mb-8">Partner Resmi AmikomEventHub</h2>

        <div class="flex flex-wrap items-center justify-center gap-4">
            @foreach($partners as $partner)
                <div class="w-44 h-24 bg-white border border-slate-200/60 rounded-2xl p-4 shadow-sm flex items-center justify-center hover:shadow-md transition" title="{{ $partner->name }}">
                    @if($partner->logo)
                        <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" class="max-h-12 object-contain">
                    @else
                        <span class="text-sm font-bold text-slate-600">{{ $partner->name }}</span>
                    @endif
                </div>
            @endforeach

            <!-- Tombol Daftar Partner Baru -->
            <a href="{{ route('partner.register') }}"
               class="w-44 h-24 bg-white hover:bg-indigo-50/50 border-2 border-dashed border-indigo-200 hover:border-indigo-500 rounded-2xl p-4 flex flex-col items-center justify-center gap-1.5 transition group cursor-pointer">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-lg group-hover:scale-110 transition">
                    +
                </div>
                <span class="text-xs font-bold text-slate-700 group-hover:text-indigo-600 transition">
                    Daftar Partner
                </span>
            </a>
        </div>
    </div>
</section>

@endsection
