@extends('admin.layouts.app')

@section('title', 'Rekap Hasil Nilai Ujian')
@section('page-title', 'Rekap Nilai: ' . $exam->judul)

@section('content')
<div class="space-y-6">
    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.exams.index') }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-600">← Kembali ke Ujian</a>
                <span class="text-slate-300">/</span>
                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold">{{ $exam->mata_pelajaran }}</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800 mt-1">Hasil Ujian & Nilai Siswa</h1>
            <p class="text-xs text-slate-500 mt-0.5">{{ $exam->judul }} | Total Peserta: <strong class="text-slate-700">{{ $attempts->total() }} Siswa</strong></p>
        </div>
    </div>

    {{-- Filter & Action Export Bar --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <form action="{{ route('admin.exams.results', $exam) }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIS..."
                   class="px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:bg-white focus:border-emerald-400 outline-none w-full sm:w-56">

            <select name="kelas" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:bg-white focus:border-emerald-400 outline-none">
                <option value="">Semua Kelas</option>
                @foreach($kelases as $k)
                    <option value="{{ $k }}" {{ request('kelas') === $k ? 'selected' : '' }}>Kelas {{ $k }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 text-white text-xs font-bold hover:bg-slate-900 transition-colors">
                Filter
            </button>
        </form>

        <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 justify-end">
            <a href="{{ route('admin.exams.export.csv', $exam) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Excel / CSV
            </a>
            <a href="{{ route('admin.exams.export.print', $exam) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Export PDF
            </a>
        </div>
    </div>

    {{-- Results Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-5">No</th>
                        <th class="py-3.5 px-5">Nama Peserta</th>
                        <th class="py-3.5 px-5">NIS / Email</th>
                        <th class="py-3.5 px-5">Kelas</th>
                        <th class="py-3.5 px-5">Waktu Pengerjaan</th>
                        <th class="py-3.5 px-5 text-center">Skor Akhir</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($attempts as $index => $attempt)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-5 text-xs text-slate-400 font-bold">
                                {{ $attempts->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-5 font-bold text-slate-800">
                                {{ $attempt->nama_peserta }}
                            </td>
                            <td class="py-4 px-5 text-xs text-slate-600">
                                {{ $attempt->nis_email }}
                            </td>
                            <td class="py-4 px-5 text-xs">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-bold text-slate-700">
                                    {{ $attempt->kelas }}
                                </span>
                            </td>
                            <td class="py-4 px-5 text-xs text-slate-500">
                                <div>Mulai: {{ $attempt->waktu_mulai ? $attempt->waktu_mulai->format('H:i:s') : '-' }}</div>
                                <div>Selesai: {{ $attempt->waktu_selesai ? $attempt->waktu_selesai->format('H:i:s') : '-' }}</div>
                            </td>
                            <td class="py-4 px-5 text-center">
                                @php
                                    $score = $attempt->skor_akhir;
                                    $bg = $score >= 75 ? 'bg-emerald-100 text-emerald-800' : ($score >= 60 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800');
                                @endphp
                                <span class="px-3 py-1 rounded-full font-black text-xs {{ $bg }}">
                                    {{ number_format($score, 1) }} / 100
                                </span>
                            </td>
                            <td class="py-4 px-5 text-right">
                                <a href="{{ route('admin.exams.results.detail', $attempt) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                                    👁️ Detail Jawaban
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-sm">
                                Belum ada siswa yang menyelesaikan ujian ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attempts->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $attempts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
