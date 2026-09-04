@extends('admin.layouts.app')

@section('title', 'Daftar Permission')
@section('page-title', 'Permission Management')

@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Hak Akses Sistem (Permission)</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar izin baku sistem yang terhubung langsung dengan keamanan middleware aplikasi.</p>
        </div>
        <div class="px-3.5 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold flex items-center gap-1.5">
            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            <span>Permission Bawaan Sistem (Fixed)</span>
        </div>
    </div>

    <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-xs leading-relaxed space-y-1">
        <strong class="font-bold text-blue-900 block">💡 Informasi Pengaturan Hak Akses:</strong>
        <p>Permission pada sistem ini bersifat **tetap (fixed)** karena terhubung langsung dengan kode keamanan backend Laravel (`EnsurePermission`). Untuk mengatur hak akses pengguna/staf sekolah, silakan centang atau atur pengelompokan permission ini pada menu **<a href="{{ route('admin.roles') }}" class="underline font-bold hover:text-blue-900">Manajemen Role</a>**.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Permission</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Middleware Protection</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Cakupan Akses & Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($permissions as $permission)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                {{ $permission->name }}
                            </span>
                        </td>
                        <td class="px-5 py-4 font-mono text-xs text-slate-600">
                            <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">permission:{{ $permission->name }}</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600 text-xs leading-relaxed">
                            {{ $permission->description ?? 'Akses fitur sistem terproteksi' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-5 py-10 text-center text-slate-400 text-sm">Belum ada permission terdaftar. Silakan jalankan seeder.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
