@extends('siswa.layouts.app')

@section('title', 'Rekapitulasi Nilai & Perankingan Saya')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Rekapitulasi Nilai & Hasil Perankingan</h2>
            <p class="text-xs text-slate-500 mt-1">Rincian nilai hasil belajar Anda di kelas {{ $siswa->kelas }}.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('siswa.nilai.cetak') }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-[5px] bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/20 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Transkrip Nilai Saya (A4)
            </a>
            <a href="{{ route('siswa.dashboard') }}" class="px-3.5 py-2.5 rounded-[5px] bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-colors border border-slate-200">
                &larr; Kembali
            </a>
        </div>
    </div>

    {{-- Final Score Overview Card --}}
    <div class="bg-white p-6 sm:p-8 rounded-[5px] border border-slate-200 text-slate-900 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs uppercase tracking-wider font-extrabold text-amber-600">Nilai Akhir Kumulatif</span>
                <span class="px-3 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-extrabold uppercase">
                    Peringkat #{{ $rankingPos ?? '-' }} dari {{ $totalSiswaKelas ?? '-' }} Siswa
                </span>
            </div>
            <div class="text-4xl sm:text-5xl font-black text-slate-900 mt-1">{{ number_format($nilaiAkhir, 2) }}</div>
            <p class="text-xs text-slate-500 mt-2">
                Rumus: (Nilai Tugas + Ulangan Harian + UTS + UAS) / 4
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center w-full sm:w-auto">
            <div class="bg-slate-50 p-3.5 rounded-[5px] border border-slate-200">
                <span class="text-[10px] text-amber-700 uppercase font-bold block">Tugas</span>
                <span class="text-lg font-black text-slate-900 mt-0.5 block">{{ $avgTugas }}</span>
            </div>

            <div class="bg-slate-50 p-3.5 rounded-[5px] border border-slate-200">
                <span class="text-[10px] text-amber-700 uppercase font-bold block">UH</span>
                <span class="text-lg font-black text-slate-900 mt-0.5 block">{{ $nilaiUH }}</span>
            </div>

            <div class="bg-slate-50 p-3.5 rounded-[5px] border border-slate-200">
                <span class="text-[10px] text-amber-700 uppercase font-bold block">UTS</span>
                <span class="text-lg font-black text-slate-900 mt-0.5 block">{{ $nilaiUTS }}</span>
            </div>

            <div class="bg-slate-50 p-3.5 rounded-[5px] border border-slate-200">
                <span class="text-[10px] text-amber-700 uppercase font-bold block">UAS</span>
                <span class="text-lg font-black text-slate-900 mt-0.5 block">{{ $nilaiUAS }}</span>
            </div>
        </div>
    </div>

    {{-- Detailed Grades Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Nilai Tugas --}}
        <div class="bg-white border border-slate-200 rounded-[5px] overflow-hidden shadow-sm">
            <div class="p-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="text-sm font-extrabold text-slate-900">Rincian Nilai Tugas</h3>
                <span class="text-xs font-semibold text-slate-500">Total: {{ $pengumpulanTugas->count() }} Tugas</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-slate-700">
                    <thead class="bg-slate-100 border-b border-slate-200 uppercase text-[10px] font-bold text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Mata Pelajaran & Judul</th>
                            <th class="px-4 py-3 text-center">Tanggal Kumpul</th>
                            <th class="px-4 py-3 text-center">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pengumpulanTugas as $pt)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="text-[10px] text-amber-700 font-bold block">{{ $pt->tugas?->mata_pelajaran }}</span>
                                <span class="font-bold text-slate-900">{{ $pt->tugas?->judul }}</span>
                            </td>
                            <td class="px-4 py-3 text-center text-slate-500">
                                {{ $pt->tanggal_kumpul->isoFormat('D MMM Y') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if(!is_null($pt->nilai))
                                    <span class="inline-block px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">
                                        {{ $pt->nilai }}
                                    </span>
                                @else
                                    <span class="text-amber-600 font-semibold italic">Belum Dinilai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-400">Belum ada nilai tugas yang terkumpul.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Nilai Ujian Online --}}
        <div class="bg-white border border-slate-200 rounded-[5px] overflow-hidden shadow-sm">
            <div class="p-5 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="text-sm font-extrabold text-slate-900">Rincian Nilai Ujian Online</h3>
                <span class="text-xs font-semibold text-slate-500">Total: {{ $examAttempts->count() }} Ujian</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left text-slate-700">
                    <thead class="bg-slate-100 border-b border-slate-200 uppercase text-[10px] font-bold text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Judul Ujian</th>
                            <th class="px-4 py-3 text-center">Mulai / Selesai</th>
                            <th class="px-4 py-3 text-center">Skor Ujian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($examAttempts as $ea)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 font-bold text-slate-900">
                                {{ $ea->exam?->judul ?? $ea->exam?->title }}
                            </td>
                            <td class="px-4 py-3 text-center text-slate-500">
                                {{ $ea->created_at->isoFormat('D MMM Y, HH:mm') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold border border-blue-200">
                                    {{ number_format($ea->skor_akhir ?? $ea->final_score ?? 0, 1) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-400">Belum ada ujian online yang diselesaikan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
