@extends('admin.layouts.app')

@section('title', 'Manajemen Tugas & Assignments')
@section('page-title', 'Tugas Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800">Manajemen Tugas & Assignments</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Buat tugas per mata pelajaran & periksa pengumpulan tugas siswa.</p>
        </div>
        <a href="{{ route('admin.tugas.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/20 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Tugas Baru
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-[5px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 uppercase text-[11px] font-bold text-slate-500">
                    <tr>
                        <th class="px-5 py-3.5">Mata Pelajaran & Judul</th>
                        <th class="px-5 py-3.5">Target Kelas</th>
                        <th class="px-5 py-3.5">Batas Pengumpulan</th>
                        <th class="px-5 py-3.5 text-center">Pengumpulan</th>
                        <th class="px-5 py-3.5">Pembuat</th>
                        <th class="px-5 py-3.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tugas as $t)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-5 py-4">
                            <span class="inline-block text-[10px] uppercase font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md mb-1">{{ $t->mata_pelajaran }}</span>
                            <div class="font-bold text-slate-800 text-sm sm:text-base">{{ $t->judul }}</div>
                        </td>
                        <td class="px-5 py-4 font-bold text-slate-700">{{ $t->kelas }}</td>
                        <td class="px-5 py-4 text-xs font-semibold text-slate-600">
                            {{ $t->deadline->isoFormat('D MMMM Y, HH:mm') }} WIB
                            @if($t->deadline->isPast())
                                <span class="block text-[10px] text-red-500 font-bold">Sudah Berakhir</span>
                            @else
                                <span class="block text-[10px] text-emerald-600 font-bold">Aktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700">
                                {{ $t->pengumpulan_count }} Siswa
                            </span>
                        </td>
                        <td class="px-5 py-4 text-xs text-slate-500">{{ $t->creator?->name ?? 'Admin' }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.tugas.show', $t->id_tugas) }}"
                                   class="px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-700 font-semibold hover:bg-emerald-200 transition-colors">
                                    Periksa Jawaban
                                </a>
                                <form action="{{ route('admin.tugas.delete', $t->id_tugas) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini beserta seluruh pengumpulannya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-semibold transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada tugas yang dibuat. Silakan klik "Buat Tugas Baru".</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tugas->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $tugas->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
