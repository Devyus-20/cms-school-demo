@extends('admin.layouts.app')

@section('title', 'Tambah Role')
@section('page-title', 'Tambah Role')

@section('content')
<div class="max-w-2xl space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.roles') }}"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-sm text-slate-700 font-semibold">Tambah Role Baru</span>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-base font-bold text-slate-800 mb-5">Informasi Role</h3>
        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Role <span class="text-red-500">*</span></label>
                <input name="name" required value="{{ old('name') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all"
                       placeholder="Contoh: Editor">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all resize-none"
                          placeholder="Deskripsi role ini...">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Daftar Hak Akses Sistem (Permissions)</label>
                <p class="text-xs text-slate-500 mb-3">Centang izin-izin bawaan sistem yang ingin diberikan kepada Role ini:</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($permissions as $permission)
                    <label class="flex items-start gap-3 p-3.5 rounded-2xl border border-slate-200 bg-slate-50 hover:bg-amber-50/50 hover:border-amber-300 transition-all cursor-pointer group">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id_permission }}"
                               class="mt-1 w-4 h-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500 cursor-pointer">
                        <div>
                            <span class="text-xs font-bold text-slate-900 group-hover:text-amber-800 transition-colors block">
                                {{ $permission->name }}
                            </span>
                            <span class="text-[11px] text-slate-500 leading-tight block mt-0.5">
                                {{ $permission->description ?? 'Akses fitur terproteksi sistem.' }}
                            </span>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('permissions')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-md shadow-emerald-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Role
                </button>
                <a href="{{ route('admin.roles') }}"
                   class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
