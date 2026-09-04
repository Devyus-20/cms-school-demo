@extends('public.layouts.app')

@php
    $displayTitle = $mainTitle ?? ($pages->first()->judul ?? 'Profil Sekolah');
@endphp

@section('title', $displayTitle . ' - ' . ($websiteSetting->website_name ?? 'MA Al Ikhlas'))

@section('content')
<div class="mx-auto max-w-6xl px-6 py-10 space-y-8 w-full flex-1">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="/" class="hover:text-amber-600">Beranda</a>
        <span>/</span>
        <span class="text-slate-400">Profil</span>
        <span>/</span>
        <span class="text-amber-600 font-bold">{{ $displayTitle }}</span>
    </div>

    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight break-words">{{ $displayTitle }}</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Informasi dan dokumentasi {{ $displayTitle }} {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}.</p>
    </div>

    {{-- ======================== PROFILE ITEMS GRID (ZILOM / INFORMASI STYLE DENGAN BACA SELENGKAPNYA) ======================== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($pages as $item)
            <a href="{{ route('public.profil.detail', $item->slug ?: $item->id_page) }}" 
               class="bg-white rounded-[5px] border border-slate-200 overflow-hidden shadow-sm hover:border-amber-400 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                <div>
                    {{-- Visual Image Container --}}
                    @if($item->gambar)
                        @php
                            $imgs = json_decode($item->gambar, true);
                            $imgs = is_array($imgs) ? array_values(array_filter($imgs)) : array_values(array_filter([$item->gambar]));
                            $mainImg = count($imgs) > 0 ? $imgs[0] : $item->gambar;
                        @endphp
                        @if($mainImg)
                            <div class="h-52 bg-slate-100 overflow-hidden relative border-b border-slate-100">
                                <img src="{{ asset($mainImg) }}" alt="{{ $item->judul }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     onerror="this.src='https://placehold.co/600x400?text=Foto+Profil';">
                                
                                <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-amber-500 text-slate-950 text-[10px] font-black uppercase tracking-wider shadow-md">
                                    {{ $displayTitle }}
                                </span>

                                @if(count($imgs) > 1)
                                    <span class="absolute bottom-3 right-3 px-2 py-0.5 rounded-[5px] bg-slate-900/80 text-white text-[10px] font-bold backdrop-blur">
                                        📷 {{ count($imgs) }} Foto
                                    </span>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="h-44 bg-slate-50 flex items-center justify-center text-slate-300 border-b border-slate-100 relative">
                            <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-amber-500 text-slate-950 text-[10px] font-black uppercase tracking-wider shadow-md">
                                {{ $displayTitle }}
                            </span>
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif

                    {{-- Card Content --}}
                    <div class="p-6 space-y-2.5">
                        <h2 class="font-extrabold text-slate-900 text-base group-hover:text-amber-600 transition-colors line-clamp-2">
                            {{ $item->judul }}
                        </h2>
                        
                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                            {{ Str::limit(strip_tags($item->konten ?? ''), 130) ?: 'Klik baca selengkapnya untuk membaca dokumentasi & informasi lengkap...' }}
                        </p>
                    </div>
                </div>

                {{-- Card Footer Bar with "Baca Selengkapnya" --}}
                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
                    <span>{{ $item->updated_at ? $item->updated_at->format('d M Y') : '—' }}</span>
                    <span class="text-amber-600 font-bold group-hover:translate-x-1 transition-transform flex items-center gap-1">
                        <span>Baca Selengkapnya</span>
                        <span>&rarr;</span>
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-full bg-white rounded-[5px] border border-slate-200 p-12 text-center text-slate-400 text-sm">
                Belum ada data {{ $displayTitle }} yang diterbitkan.
            </div>
        @endforelse
    </div>

    {{-- Bottom Navigation --}}
    <div class="text-center pt-4">
        <a href="/" class="inline-flex items-center gap-2 px-6 py-3 rounded-[5px] bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold transition-all shadow-md uppercase tracking-wider">
            &larr; Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
