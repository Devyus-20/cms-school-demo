@extends('admin.layouts.app')

@section('title', 'Kelola Tag')
@section('page-title', 'Kelola Tag')

@section('content')
<div class="space-y-6">
    {{-- Form Tambah --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-base font-bold text-slate-800 mb-5">Tambah Tag Baru</h3>
        <form action="{{ route('admin.tags.store') }}" method="POST">
            @csrf
            <div class="max-w-sm">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Tag <span class="text-red-500">*</span></label>
                <input name="nama" required value="{{ old('nama') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                       placeholder="Contoh: Prestasi">
                @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="mt-5">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-md shadow-emerald-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Simpan Tag
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Tag --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Daftar Tag</h3>
        </div>
        <div class="p-5 flex flex-wrap gap-2">
            @forelse($tags as $tag)
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                {{ $tag->nama }}
            </span>
            @empty
            <p class="text-sm text-slate-400">Belum ada tag ditambahkan.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
