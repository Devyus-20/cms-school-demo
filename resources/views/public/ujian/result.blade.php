@extends('public.layouts.exam')

@section('title', 'Hasil Nilai Ujian - ' . $attempt->exam->judul)

@section('content')
<main class="mx-auto max-w-2xl px-6 py-12">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-sm text-center space-y-6">
        <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-600 border border-amber-200 font-black text-3xl flex items-center justify-center mx-auto shadow-sm">
            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>

        <div class="space-y-1">
            <span class="px-3.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold uppercase tracking-wider">Ujian Selesai</span>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight pt-2">{{ $attempt->exam->judul }}</h1>
            <p class="text-xs text-slate-500 font-semibold">{{ $attempt->exam->mata_pelajaran }}</p>
        </div>

        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200 space-y-4">
            <div class="grid grid-cols-2 gap-4 text-left text-xs border-b border-slate-200 pb-4">
                <div>
                    <span class="text-slate-400 font-medium block">Nama Peserta:</span>
                    <strong class="text-slate-900 text-sm block truncate">{{ $attempt->nama_peserta }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">Kelas:</span>
                    <strong class="text-slate-900 text-sm block">{{ $attempt->kelas }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">NIS / Email:</span>
                    <strong class="text-slate-900 text-sm block">{{ $attempt->nis_email }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">Waktu Selesai:</span>
                    <strong class="text-slate-900 text-sm block">{{ $attempt->waktu_selesai ? $attempt->waktu_selesai->format('d M Y, H:i') : '-' }}</strong>
                </div>
            </div>

            @if($attempt->exam->tampilkan_nilai)
                <div class="pt-2">
                    <div class="text-xs font-extrabold text-slate-500 uppercase tracking-widest">Skor Akhir Perolehan</div>
                    <div class="text-5xl font-black text-amber-600 my-2">
                        {{ number_format($attempt->skor_akhir, 1) }}
                    </div>
                    <div class="text-xs text-slate-500 font-medium">Dari Skala 100 Poin</div>
                </div>
            @else
                <div class="py-4 text-xs font-semibold text-slate-500">
                    Nilai ujian Anda telah tersimpan. Pengumuman nilai akhir akan disampaikan oleh guru/pengawas.
                </div>
            @endif
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('siswa.dashboard') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs transition-all shadow-sm uppercase tracking-wider">
                &larr; Kembali ke Dashboard Siswa
            </a>
            <a href="{{ route('public.ujian.index') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs transition-all shadow-sm uppercase tracking-wider">
                Daftar Ujian Online
            </a>
        </div>
    </div>
</main>
@endsection
