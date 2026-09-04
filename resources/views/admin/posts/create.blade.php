@extends('admin.layouts.app')

@section('title', 'Tambah Konten')
@section('page-title', 'Tambah Konten Baru')

@section('content')
<div class="max-w-3xl space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.posts', ['tipe' => $tipe]) }}"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-sm text-slate-700 font-semibold">Tambah Konten</span>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        @if ($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm space-y-1">
            <div class="font-bold flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Gagal menyimpan data! Silakan periksa kembali:</span>
            </div>
            <ul class="list-disc list-inside text-xs pl-2 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tipe Konten <span class="text-red-500">*</span></label>
                    <select name="tipe" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                        @foreach($tipes as $k => $l)
                            <option value="{{ $k }}" {{ old('tipe', $tipe) == $k ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                    @error('tipe')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori</label>
                    <select name="category_id"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id_category }}" {{ old('category_id') == $category->id_category ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul <span class="text-red-500">*</span></label>
                <input name="judul" required value="{{ old('judul') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                       placeholder="Judul...">
                @error('judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Slug (URL)</label>
                <input name="slug" value="{{ old('slug') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                       placeholder="judul-konten (otomatis jika kosong)">
                @error('slug')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status Publikasi</label>
                    <select name="status"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Upload Gambar Thumbnail <span class="text-xs text-slate-400 font-normal">(Bisa pilih lebih dari 1 gambar sekaligus)</span></label>
                    <input type="file" name="thumbnail[]" accept="image/*" multiple
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white text-xs text-slate-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                    <p class="mt-1 text-[11px] text-slate-400">Atau gunakan URL: <input name="thumbnail_url" value="{{ old('thumbnail_url') }}" placeholder="https://..." class="mt-1 w-full px-3 py-1 rounded-lg border border-slate-200 text-xs bg-slate-50"></p>
                    @error('thumbnail')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Isi Konten</label>
                <textarea name="isi" rows="8"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all resize-y"
                          placeholder="Tulis isi di sini...">{{ old('isi') }}</textarea>
                @error('isi')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tags</label>
                <div class="flex flex-wrap gap-2 p-4 rounded-xl border border-slate-200 bg-slate-50">
                    @foreach($tags as $tag)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id_tag }}"
                               class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        <span class="text-sm text-slate-700 group-hover:text-emerald-700 transition-colors">{{ $tag->nama }}</span>
                    </label>
                    @endforeach
                </div>
                @error('tags')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-md shadow-emerald-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Konten
                </button>
                <a href="{{ route('admin.posts', ['tipe' => $tipe]) }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
