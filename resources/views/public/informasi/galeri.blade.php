@extends('public.layouts.app')

@section('title', 'Galeri Foto - ' . ($websiteSetting->website_name ?? 'MA Al Ikhlas'))

@section('content')
<div class="mx-auto max-w-6xl px-6 py-10 space-y-8 w-full flex-1">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="/" class="hover:text-amber-600">Beranda</a>
        <span>/</span>
        <span class="text-slate-400">Informasi</span>
        <span>/</span>
        <span class="text-amber-600 font-bold">Galeri Foto</span>
    </div>

    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight break-words">Galeri Foto Kegiatan</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Dokumentasi momen dan kegiatan siswa-siswi {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($galleries as $gallery)
        <div class="bg-white rounded-[5px] border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-amber-400 transition-all duration-300 group flex flex-col justify-between">
            <div>
                @php
                    $gImg = json_decode($gallery->gambar, true);
                    $gImgUrl = is_array($gImg) && count($gImg) > 0 ? $gImg[0] : $gallery->gambar;
                @endphp
                <div class="h-52 bg-slate-100 overflow-hidden relative group cursor-zoom-in">
                    <img src="{{ asset($gImgUrl) }}" alt="{{ $gallery->judul }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                         onerror="this.src='https://placehold.co/600x400?text=Galeri+Sekolah';">
                    <div class="absolute inset-0 bg-slate-950/25 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center pointer-events-none">
                        <span class="p-2.5 rounded-[5px] bg-white/90 backdrop-blur-md text-slate-800 shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-2">
                    <h3 class="font-extrabold text-slate-900 text-base group-hover:text-amber-600 transition-colors">{{ $gallery->judul }}</h3>
                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">{{ $gallery->deskripsi ?? 'Kegiatan sekolah' }}</p>
                </div>
            </div>
            <div class="p-6 pt-0 text-[11px] font-semibold text-slate-400">
                {{ $gallery->tanggal ? \Carbon\Carbon::parse($gallery->tanggal)->format('d M Y') : '' }}
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-[5px] border border-slate-200 p-12 text-center text-slate-400 text-sm">
            Belum ada foto galeri yang diunggah.
        </div>
        @endforelse
    </div>
</div>
@endsection
