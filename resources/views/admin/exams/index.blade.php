@extends('admin.layouts.app')

@section('title', 'Manajemen Ujian Online')
@section('page-title', 'Ujian / Ulangan Online')

@section('content')
<div class="space-y-6">
    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-800 leading-tight break-words">Daftar Ujian / Ulangan Online</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola pembuatan ujian, bank soal, jadwal pelaksanaan, dan rekap nilai siswa.</p>
        </div>
        <a href="{{ route('admin.exams.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold transition-colors shadow-md shadow-emerald-600/20 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Ujian Baru
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Data Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-5">Judul Ujian & Mapel</th>
                        <th class="py-3.5 px-5">Durasi & Waktu</th>
                        <th class="py-3.5 px-5 text-center">Jumlah Soal</th>
                        <th class="py-3.5 px-5 text-center">Peserta Selesai</th>
                        <th class="py-3.5 px-5 text-center">Status</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($exams as $exam)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-800">{{ $exam->judul }}</div>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    @php
                                        $tipeLabel = match($exam->tipe_ujian ?? 'uh') {
                                            'uh' => 'UH',
                                            'uts' => 'UTS',
                                            'uas' => 'UAS',
                                            default => 'Kuis/Lainnya',
                                        };
                                        $tipeBadgeBg = match($exam->tipe_ujian ?? 'uh') {
                                            'uh' => 'bg-purple-100 text-purple-800',
                                            'uts' => 'bg-amber-100 text-amber-800',
                                            'uas' => 'bg-red-100 text-red-800',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-md {{ $tipeBadgeBg }} text-[10px] font-extrabold uppercase" title="Kategori Ujian">{{ $tipeLabel }}</span>
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-xs font-extrabold">{{ $exam->mata_pelajaran }}</span>
                                    @if($exam->token)
                                        <span class="text-xs text-slate-400 font-mono">Token: <strong class="text-slate-600">{{ $exam->token }}</strong></span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-5 text-xs">
                                <div class="font-semibold text-slate-700">⏱️ {{ $exam->durasi_menit }} Menit</div>
                                @if($exam->waktu_mulai)
                                    <div class="text-slate-400 mt-0.5">Mulai: {{ $exam->waktu_mulai->format('d M Y, H:i') }}</div>
                                @endif
                                @if($exam->waktu_selesai)
                                    <div class="text-slate-400">Selesai: {{ $exam->waktu_selesai->format('d M Y, H:i') }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-center">
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-bold text-xs">
                                    {{ $exam->questions_count }} Soal
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center">
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-xs">
                                    {{ $exam->attempts_count }} Siswa
                                </span>
                            </td>
                            <td class="py-4 px-5 text-center">
                                @if($exam->aktif)
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[11px] inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 font-bold text-[11px]">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-5 text-right space-x-1">
                                {{-- Kelola Soal --}}
                                <a href="{{ route('admin.exams.questions', $exam) }}" title="Kelola Soal"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition-colors">
                                    📝 Soal ({{ $exam->questions_count }})
                                </a>

                                {{-- Peserta Terdaftar (Whitelist) --}}
                                <a href="{{ route('admin.exams.participants', $exam) }}" title="Kelola Peserta Terdaftar"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold transition-colors">
                                    👥 Peserta ({{ $exam->participants_count }})
                                </a>

                                {{-- Rekap Nilai --}}
                                <a href="{{ route('admin.exams.results', $exam) }}" title="Rekap Hasil Nilai"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold transition-colors">
                                    📊 Nilai ({{ $exam->attempts_count }})
                                </a>

                                {{-- Download Rekap Excel / CSV --}}
                                <a href="{{ route('admin.exams.export.csv', $exam) }}" title="Download Rekap CSV/Excel"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold transition-colors">
                                    📥 CSV/Excel
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('admin.exams.edit', $exam) }}" title="Edit Ujian"
                                   class="inline-flex items-center p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('admin.exams.delete', $exam) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ujian ini beserta seluruh soal dan nilainya?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Ujian" class="p-1.5 rounded-lg text-red-500 hover:bg-red-50 hover:text-red-700 transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 text-sm">
                                Belum ada Ujian Online yang dibuat. Klik <strong>Tambah Ujian Baru</strong> untuk mulai membuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($exams->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $exams->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
