@extends('public.layouts.app')

@section('title', $page->judul . ' - ' . $categoryLabel . ' - ' . ($websiteSetting->website_name ?? 'MA Al Ikhlas'))

@section('content')
<div class="mx-auto max-w-4xl px-6 py-10 space-y-6 w-full flex-1">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="/" class="hover:text-amber-600">Beranda</a>
        <span>/</span>
        <span class="text-slate-400">Profil</span>
        <span>/</span>
        <a href="/profil/{{ $baseSlug }}" class="hover:text-amber-600">{{ $categoryLabel }}</a>
        <span>/</span>
        <span class="text-amber-600 font-bold truncate max-w-xs">{{ $page->judul }}</span>
    </div>

    {{-- Profile Item Content Card --}}
    <article class="bg-white rounded-[5px] border border-slate-200 p-8 sm:p-12 shadow-sm space-y-6">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="px-3 py-1 rounded-full bg-amber-500 text-slate-950 text-xs font-black uppercase tracking-wider shadow-sm">
                {{ $categoryLabel }}
            </span>
            <span class="text-xs text-slate-400 font-medium ml-auto">
                Diperbarui: {{ $page->updated_at ? $page->updated_at->format('d M Y') : '—' }}
            </span>
        </div>

        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 tracking-tight leading-tight break-words">
            {{ $page->judul }}
        </h1>

        {{-- Photos Gallery --}}
        @if($page->gambar)
            @php
                $imgs = json_decode($page->gambar, true);
                $imgs = is_array($imgs) ? array_values(array_filter($imgs)) : array_values(array_filter([$page->gambar]));
                $imgCount = count($imgs);
            @endphp
            @if($imgCount > 0)
                @php
                    if ($imgCount === 1) {
                        $gridClass = 'grid-cols-1';
                        $imgClass = 'w-full h-64 sm:h-96 max-h-[500px] object-cover rounded-[5px] border border-slate-200';
                    } elseif ($imgCount === 2) {
                        $gridClass = 'grid-cols-1 sm:grid-cols-2';
                        $imgClass = 'w-full h-56 sm:h-72 object-cover rounded-[5px] border border-slate-200';
                    } elseif ($imgCount === 3) {
                        $gridClass = 'grid-cols-1 sm:grid-cols-3';
                        $imgClass = 'w-full h-48 sm:h-64 object-cover rounded-[5px] border border-slate-200';
                    } elseif ($imgCount === 4) {
                        $gridClass = 'grid-cols-2 sm:grid-cols-4';
                        $imgClass = 'w-full h-44 sm:h-56 object-cover rounded-[5px] border border-slate-200';
                    } else {
                        $gridClass = 'grid-cols-2 sm:grid-cols-3 md:grid-cols-4';
                        $imgClass = 'w-full h-40 sm:h-48 object-cover rounded-[5px] border border-slate-200';
                    }
                @endphp
                <div class="grid {{ $gridClass }} gap-4">
                    @foreach($imgs as $idx => $imgUrl)
                        <div class="relative group overflow-hidden rounded-[5px] border border-slate-200 shadow-sm cursor-zoom-in">
                            <img src="{{ asset($imgUrl) }}" alt="{{ $page->judul }} Foto {{ $idx + 1 }}" 
                                 class="{{ $imgClass }} group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.src='https://placehold.co/800x600?text=Foto+Profil';">
                            <div class="absolute inset-0 bg-slate-950/25 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center pointer-events-none">
                                <span class="p-2.5 rounded-[5px] bg-white/90 backdrop-blur-md text-slate-800 shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        {{-- Main Article Content --}}
        @if($page->konten)
            <div class="prose prose-slate max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4 pt-2">
                {!! $page->konten !!}
            </div>
        @else
            <div class="text-slate-400 italic text-sm py-4">
                Belum ada konten penjelasan yang ditambahkan untuk halaman ini.
            </div>
        @endif
    </article>

    {{-- Bottom Back Button --}}
    <div class="text-center pt-4">
        <a href="/profil/{{ $baseSlug }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-[5px] bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold transition-all shadow-md uppercase tracking-wider">
            &larr; Kembali ke Daftar {{ $categoryLabel }}
        </a>
    </div>
</div>
@endsection
