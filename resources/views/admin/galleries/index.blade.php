@extends('admin.layouts.app')

@section('title', 'Galeri Foto')
@section('page-title', 'Galeri Foto Sekolah')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800 leading-tight break-words">Galeri Foto</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola album dan foto kegiatan sekolah.</p>
        </div>
        <a href="{{ route('admin.galleries.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold transition-colors shadow-md shadow-emerald-600/20 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Foto
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse($galleries as $gallery)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col group">
            <div class="h-48 bg-slate-100 relative overflow-hidden">
                <img src="{{ $gallery->gambar }}" alt="{{ $gallery->judul }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     onerror="this.src='https://placehold.co/600x400?text=Galeri+Sekolah';">
                <div class="absolute top-3 right-3">
                    @if($gallery->aktif)
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/90 text-white backdrop-blur">Aktif</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-500/90 text-white backdrop-blur">Draft</span>
                    @endif
                </div>
            </div>
            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm line-clamp-1">{{ $gallery->judul }}</h3>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $gallery->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                    <span class="text-slate-400 font-medium">{{ $gallery->tanggal ? $gallery->tanggal->format('d M Y') : '—' }}</span>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.galleries.edit', $gallery) }}" class="text-emerald-600 hover:underline font-semibold">Edit</a>
                        <form action="{{ route('admin.galleries.delete', $gallery) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline font-semibold">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="sm:col-span-2 md:col-span-3 lg:col-span-4 bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Belum ada foto galeri. Klik "Tambah Foto" untuk mengunggah.
        </div>
        @endforelse
    </div>
</div>
@endsection
