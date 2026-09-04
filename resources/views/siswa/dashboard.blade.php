@extends('siswa.layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="space-y-6">
    {{-- Hero Welcome Banner --}}
    <div class="relative overflow-hidden rounded-[5px] bg-white p-6 sm:p-8 text-slate-900 shadow-sm border border-slate-200">
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-3.5 py-1 bg-amber-50 text-amber-700 font-bold text-xs rounded-full uppercase tracking-wider mb-2 border border-amber-200">
                    Kelas {{ $siswa->kelas }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Selamat Datang, {{ $siswa->nama_lengkap }}</h1>
                <p class="mt-2 text-xs sm:text-sm text-slate-600 max-w-2xl leading-relaxed">
                    Pantau presensi kehadiran, tugas sekolah, ujian online, dan hasil rekapitulasi nilai Anda di portal akademik siswa ini.
                </p>
            </div>
            <div class="bg-slate-50 border border-slate-200 p-4.5 rounded-[5px] shrink-0 text-center shadow-sm">
                <div class="text-[11px] uppercase font-extrabold text-amber-600 tracking-wider">Peringkat Kelas</div>
                <div class="text-3xl font-black text-slate-900 mt-1">#{{ $rankingPos }}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">Dari {{ $temanSekelas->count() }} Siswa</div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Presensi Stat --}}
        <div class="bg-white border border-slate-200 p-5 rounded-[5px] text-slate-900 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Persentase Kehadiran</span>
                <div class="text-2xl font-black text-emerald-600 mt-1">{{ $persenKehadiran }}%</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Rekap seluruh kehadiran</p>
            </div>
            <div class="w-12 h-12 rounded-[5px] bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>

        {{-- Tugas Stat --}}
        <div class="bg-white border border-slate-200 p-5 rounded-[5px] text-slate-900 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tugas Aktif</span>
                <div class="text-2xl font-black text-amber-600 mt-1">{{ $tugasList->count() }} Tugas</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Batas waktu terdekat</p>
            </div>
            <div class="w-12 h-12 rounded-[5px] bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>

        {{-- Ujian Online Stat --}}
        <div class="bg-white border border-slate-200 p-5 rounded-[5px] text-slate-900 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ujian Online</span>
                <div class="text-2xl font-black text-blue-600 mt-1">{{ $exams->count() }} Ujian</div>
                <p class="text-[11px] text-slate-400 mt-0.5">Siap dikerjakan</p>
            </div>
            <div class="w-12 h-12 rounded-[5px] bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Active Assignments --}}
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-[5px] p-6 text-slate-900 space-y-4 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Tugas Mendatang (Kelas {{ $siswa->kelas }})</span>
                </h3>
                <a href="{{ route('siswa.tugas') }}" class="text-xs text-amber-600 font-bold hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($tugasList as $tugas)
                @php $sudah = in_array($tugas->id_tugas, $tugasKumpulIds); @endphp
                <div class="p-4.5 rounded-[5px] bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <span class="inline-block text-[10px] uppercase font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded border border-amber-200 mb-1">
                            {{ $tugas->mata_pelajaran }}
                        </span>
                        <h4 class="text-sm font-bold text-slate-900">{{ $tugas->judul }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Deadline: {{ $tugas->deadline->isoFormat('D MMMM Y, HH:mm') }} WIB</p>
                    </div>
                    <div>
                        @if($sudah)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                ✓ Sudah Dikumpulkan
                            </span>
                        @else
                            <a href="{{ route('siswa.tugas') }}" class="inline-flex items-center gap-1 px-4 py-2 rounded-[5px] bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold transition-colors shadow-sm">
                                Kerjakan Tugas
                            </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400 text-xs font-medium">
                    Tidak ada tugas mendatang.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Right: CBT Exam List --}}
        <div class="bg-white border border-slate-200 rounded-[5px] p-6 text-slate-900 space-y-4 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Ujian Online CBT</span>
                </h3>
                <a href="{{ route('public.ujian.index') }}" target="_blank" class="text-xs text-blue-600 font-bold hover:underline">
                    Portal Ujian &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($exams as $exam)
                <div class="p-4 rounded-[5px] bg-slate-50 border border-slate-200 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-bold text-slate-900">{{ $exam->mata_pelajaran }}</span>
                        <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Aktif</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-800 line-clamp-1">{{ $exam->judul }}</h4>
                    <div class="text-[11px] text-slate-500 flex items-center justify-between pt-1">
                        <span>Durasi: {{ $exam->durasi_menit }} Menit</span>
                        <a href="{{ route('public.ujian.confirm', $exam) }}" target="_blank" class="text-amber-600 font-bold hover:underline">
                            Ikuti &rarr;
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400 text-xs font-medium">
                    Belum ada ujian online aktif.
                </div>
                @endforelse
            </div>
            
            <div class="pt-4 border-t border-slate-100">
                <a href="{{ route('siswa.nilai') }}" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-[5px] text-xs flex items-center justify-center gap-2 transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Lihat Rekapitulasi Nilai Saya</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
