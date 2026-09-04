@extends('admin.layouts.app')

@section('title', 'Kelola Konten: ' . $categoryTitle)
@section('page-title', 'Kelola Konten: ' . $categoryTitle)

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb & Back Navigation --}}
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pages') }}"
               class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-slate-600 hover:text-emerald-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Modul Profil
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-xs sm:text-sm font-bold text-slate-800">{{ $categoryTitle }}</span>
        </div>

        <a href="{{ route('admin.pages.create', ['category' => $category]) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold transition-colors shadow-md shadow-emerald-600/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Konten {{ $categoryTitle }} Baru
        </a>
    </div>

    {{-- Category Header Card --}}
    <div class="rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider border border-emerald-500/30">
                    Modul Profil Sekolah
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-white/10 text-white text-xs font-bold">
                    {{ $pages->count() }} Item Terdaftar
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight">{{ $categoryTitle }}</h1>
            <p class="text-xs sm:text-sm text-slate-300">Kelola seluruh rincian informasi, teks, dan foto untuk kategori {{ $categoryTitle }}.</p>
        </div>

        <a href="/profil/{{ $category }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-bold transition-colors self-start sm:self-center shrink-0">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Pratinjau Halaman Website
        </a>
    </div>

    {{-- Data Table Section --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <h3 class="text-sm sm:text-base font-bold text-slate-800">Daftar Konten {{ $categoryTitle }}</h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5 w-16">Media</th>
                        <th class="px-5 py-3.5">Judul Konten</th>
                        <th class="px-5 py-3.5">Slug (URL)</th>
                        <th class="px-5 py-3.5 w-24 text-center">Urutan</th>
                        <th class="px-5 py-3.5 w-28 text-center">Status</th>
                        <th class="px-5 py-3.5 w-44 text-right">Aksi & Pengelolaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($pages as $page)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-5 py-3.5">
                            @if($page->gambar)
                                @php
                                    $imgs = json_decode($page->gambar, true);
                                    $thumb = is_array($imgs) && count($imgs) > 0 ? $imgs[0] : $page->gambar;
                                @endphp
                                <img src="{{ asset($thumb) }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 shadow-sm">
                            @else
                                <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 font-bold text-xs">
                                    📄
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-slate-800 text-sm">{{ $page->judul }}</div>
                            <div class="text-[10px] text-slate-400">Terakhir diperbarui: {{ $page->updated_at->translatedFormat('d M Y H:i') }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <code class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-mono border border-slate-200/60">
                                /profil/{{ $page->slug }}
                            </code>
                        </td>
                        <td class="px-5 py-3.5 text-center font-bold text-slate-600">
                            {{ $page->urutan }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($page->aktif)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="/profil/{{ $page->slug }}" target="_blank" title="Lihat di Website"
                                   class="p-1.5 rounded-lg bg-slate-100 hover:bg-blue-50 text-slate-600 hover:text-blue-700 transition-colors border border-slate-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.pages.edit', $page) }}" title="Edit Konten"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 text-xs font-semibold transition-colors border border-slate-200">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.pages.delete', $page) }}" method="POST"
                                      onsubmit="return confirm('Hapus konten {{ $page->judul }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Hapus Konten"
                                            class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors border border-red-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-sm font-semibold text-slate-600">Belum ada konten untuk kategori {{ $categoryTitle }}.</p>
                            <p class="text-xs text-slate-400 mt-1">Klik tombol di bawah ini untuk menambahkan konten baru.</p>
                            <a href="{{ route('admin.pages.create', ['category' => $category]) }}" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold shadow-md shadow-emerald-600/20 hover:bg-emerald-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                                + Tambah Konten {{ $categoryTitle }}
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
