@extends('admin.layouts.app')

@section('title', 'Tambah Halaman Profil')
@section('page-title', 'Tambah Halaman Profil Baru')

@section('content')
<div class="max-w-3xl space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.pages', $presetCategory ? ['category' => $presetCategory] : []) }}"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-sm text-slate-700 font-semibold">Tambah Halaman</span>
    </div>

    @if($presetTitle)
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shrink-0">
                +
            </div>
            <div>
                <h4 class="text-sm font-bold text-emerald-900">Menambahkan Konten Baru: {{ $presetTitle }}</h4>
                <p class="text-xs text-emerald-700 mt-0.5">Judul & slug telah diisi otomatis. Anda dapat mengubahnya atau langsung melengkapi foto dan deskripsi.</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('admin.pages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Halaman <span class="text-red-500">*</span></label>
                <input name="judul" required value="{{ old('judul', $presetTitle) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                       placeholder="Misal: Sejarah Sekolah, Fasilitas Olahraga, dll.">
                @error('judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Slug (URL)</label>
                <input name="slug" value="{{ old('slug', $presetSlug) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                       placeholder="Kosongkan untuk otomatis buat slug unik (bebas duplikat)">
                <p class="mt-1.5 text-xs text-slate-500">✨ <strong>Bisa menambahkan judul/fasilitas sebanyak apapun!</strong> Slug akan otomatis disesuaikan (misal: <code>fasilitas-1</code>, <code>fasilitas-2</code>) tanpa error duplikat.</p>
                <p class="mt-1 text-xs text-slate-400">Pilihan slug profil standar: 
                    @foreach($profilSlugs as $s => $l)
                        <button type="button" onclick="document.querySelector('[name=slug]').value='{{ $s }}'; document.querySelector('[name=judul]').value='{{ $l }}';" class="underline text-emerald-600 mr-2 cursor-pointer">{{ $s }}</button>
                    @endforeach
                </p>
                @error('slug')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Upload Gambar (Bisa pilih 1 atau Lebih Foto sekaligus)</label>
                    <input type="file" name="gambar[]" accept="image/*" multiple
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                    <p class="mt-1 text-[11px] text-slate-400">Anda dapat memblok/memilih banyak foto sekaligus (Multiple upload). Atau gunakan URL: <input name="gambar_url" value="{{ old('gambar_url') }}" placeholder="https://..." class="mt-1 w-full px-3 py-1 rounded-lg border border-slate-200 text-xs bg-slate-50"></p>
                    @error('gambar')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Urutan Tampil</label>
                    <input type="number" name="urutan" value="{{ old('urutan', 0) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                    @error('urutan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konten Halaman</label>
                <textarea name="konten" rows="10"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all resize-y"
                          placeholder="Tulis rincian profil / deskripsi fasilitas di sini...">{{ old('konten') }}</textarea>
                @error('konten')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="aktif" value="1" id="aktif" {{ old('aktif', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                <label for="aktif" class="text-sm font-medium text-slate-700 cursor-pointer">Publikasikan Halaman Ini</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-md shadow-emerald-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Halaman
                </button>
                <a href="{{ route('admin.pages', $presetCategory ? ['category' => $presetCategory] : []) }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
