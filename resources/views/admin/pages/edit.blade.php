@extends('admin.layouts.app')

@section('title', 'Edit Halaman Profil')
@section('page-title', 'Edit Halaman Profil')

@section('content')
<div class="max-w-3xl space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.pages') }}"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-sm text-slate-700 font-semibold">Edit Halaman</span>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Halaman <span class="text-red-500">*</span></label>
                <input name="judul" required value="{{ old('judul', $page->judul) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                @error('judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Slug (URL)</label>
                <input name="slug" value="{{ old('slug', $page->slug) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                <p class="mt-1 text-xs text-slate-500">Slug akan otomatis dijaga keunikannya jika terjadi duplikasi.</p>
                @error('slug')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Foto Saat Ini & Upload Baru (Bisa pilih 1 atau Lebih)</label>
                    @if($page->gambar)
                        @php
                            $imgs = json_decode($page->gambar, true);
                            $imgs = is_array($imgs) ? $imgs : [$page->gambar];
                        @endphp
                        <div class="mb-3.5 p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                            <div class="flex flex-wrap gap-2">
                                @foreach($imgs as $img)
                                    <img src="{{ asset($img) }}" class="w-14 h-14 rounded-lg object-cover border border-slate-300 shadow-sm">
                                @endforeach
                            </div>
                            <div class="flex items-center gap-2 pt-1 border-t border-slate-200/80">
                                <input type="checkbox" name="keep_existing_gambar" value="1" id="keep_existing_gambar" checked
                                       class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                                <label for="keep_existing_gambar" class="text-xs font-semibold text-slate-700 cursor-pointer">
                                    Simpan & gabungkan foto lama dengan foto baru
                                </label>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="gambar[]" accept="image/*" multiple
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                    <p class="mt-1 text-[11px] text-slate-400">Atau gunakan URL baru: <input name="gambar_url" value="{{ old('gambar_url') }}" placeholder="https://..." class="mt-1 w-full px-3 py-1 rounded-lg border border-slate-200 text-xs bg-slate-50"></p>
                    @error('gambar')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Urutan Tampil</label>
                    <input type="number" name="urutan" value="{{ old('urutan', $page->urutan) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                    @error('urutan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konten Halaman</label>
                <textarea name="konten" rows="10"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all resize-y">{{ old('konten', $page->konten) }}</textarea>
                @error('konten')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="aktif" value="1" id="aktif" {{ old('aktif', $page->aktif) ? 'checked' : '' }}
                       class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                <label for="aktif" class="text-sm font-medium text-slate-700 cursor-pointer">Publikasikan Halaman Ini</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-md shadow-emerald-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Perbarui Halaman
                </button>
                <a href="{{ route('admin.pages') }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
