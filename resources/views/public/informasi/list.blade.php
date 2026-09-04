@extends('public.layouts.app')

@section('title', 'Daftar ' . $label . ' - ' . ($websiteSetting->website_name ?? 'MA Al Ikhlas'))

@section('content')
<div class="mx-auto max-w-6xl px-6 py-10 space-y-8 w-full flex-1">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="/" class="hover:text-amber-600">Beranda</a>
        <span>/</span>
        <span class="text-slate-400">Informasi</span>
        <span>/</span>
        <span class="text-amber-600 font-bold">{{ $label }}</span>
    </div>

    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight break-words">Daftar {{ $label }}</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Kumpulan {{ strtolower($label) }} terbaru dari {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($posts as $post)
        <a href="{{ route('public.post.show', $post->slug) }}" class="bg-white rounded-[5px] border border-slate-200 overflow-hidden shadow-sm hover:border-amber-400 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
            <div>
                @if($post->thumbnail)
                    @php
                        $thumb = json_decode($post->thumbnail, true);
                        $thumbUrl = is_array($thumb) && count($thumb) > 0 ? $thumb[0] : $post->thumbnail;
                    @endphp
                    <div class="h-48 bg-slate-100 overflow-hidden relative">
                        <img src="{{ asset($thumbUrl) }}" alt="{{ $post->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-amber-500 text-slate-950 text-[10px] font-black uppercase tracking-wider shadow-md">
                            {{ $post->tipe }}
                        </span>
                    </div>
                @endif
                <div class="p-6 space-y-3">
                    <h2 class="font-extrabold text-slate-900 text-base group-hover:text-amber-600 transition-colors line-clamp-2">{{ $post->judul }}</h2>
                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">{{ Str::limit(strip_tags($post->isi ?? ''), 120) }}</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400 font-medium">
                <span>{{ $post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d M Y') : '—' }}</span>
                <span class="text-amber-600 font-bold group-hover:translate-x-1 transition-transform">Baca Selengkapnya &rarr;</span>
            </div>
        </a>
        @empty
        <div class="col-span-full bg-white rounded-[5px] border border-slate-200 p-12 text-center text-slate-400 text-sm">
            Belum ada {{ strtolower($label) }} yang diterbitkan.
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>
@endsection
