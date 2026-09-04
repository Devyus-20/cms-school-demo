@extends('public.layouts.exam')

@section('title', 'Ujian & Ulangan Online - ' . ($websiteSetting->website_name ?? 'MA Al Ikhlas'))

@section('content')
<main class="mx-auto max-w-6xl px-6 py-10 space-y-8">
    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
        <div class="flex items-center gap-2">
            <a href="{{ route('siswa.dashboard') }}" class="hover:text-amber-600 font-bold text-amber-600 flex items-center gap-1">
                &larr; Dashboard Siswa
            </a>
            <span>/</span>
            <span class="text-slate-800 font-bold">Ujian Online</span>
        </div>
    </div>

    {{-- Page Header --}}
    <div class="bg-white border border-slate-200 p-8 sm:p-10 rounded-[5px] text-slate-900 shadow-sm relative overflow-hidden">
        <div class="relative z-10 space-y-3 max-w-2xl">
            <span class="px-3.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-extrabold uppercase tracking-wider border border-amber-200">Computer Based Test</span>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 tracking-tight leading-tight break-words">Ujian & Ulangan Online</h1>
            <p class="text-sm text-slate-600 leading-relaxed font-normal">
                Portal ujian digital siswa. Pilih ujian yang sedang aktif di bawah ini, isi data peserta, dan kerjakan dengan jujur.
            </p>
        </div>
    </div>

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    {{-- Exam List Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($exams as $exam)
            @php
                $isStarted = !$exam->waktu_mulai || $now->gte($exam->waktu_mulai);
                $isEnded   = $exam->waktu_selesai && $now->gt($exam->waktu_selesai);
                $isOpen    = $isStarted && !$isEnded;
            @endphp
            <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:border-amber-400 transition-all duration-300 flex flex-col justify-between space-y-5 group">
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="px-3 py-1 rounded-xl bg-amber-50 text-amber-700 font-bold text-xs border border-amber-200">
                            {{ $exam->mata_pelajaran }}
                        </span>
                        
                        @if($isOpen)
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Sedang Berlangsung
                            </span>
                        @elseif(!$isStarted)
                            <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[11px] font-bold border border-amber-200">
                                Akan Datang
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-500 text-[11px] font-bold border border-slate-200">
                                Telah Berakhir
                            </span>
                        @endif
                    </div>

                    <h2 class="text-lg font-extrabold text-slate-900 group-hover:text-amber-600 transition-colors leading-snug">
                        {{ $exam->judul }}
                    </h2>

                    @if($exam->deskripsi)
                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                            {{ $exam->deskripsi }}
                        </p>
                    @endif

                    <div class="text-xs text-slate-500 font-medium space-y-1 pt-2 border-t border-slate-100">
                        <div>Durasi: <strong class="text-amber-600 font-bold">{{ $exam->durasi_menit }} Menit</strong></div>
                        @if($exam->waktu_mulai)
                            <div>Mulai: {{ $exam->waktu_mulai->format('d M Y, H:i') }} WIB</div>
                        @endif
                        @if($exam->waktu_selesai)
                            <div>Selesai: {{ $exam->waktu_selesai->format('d M Y, H:i') }} WIB</div>
                        @endif
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <div class="text-slate-500 font-medium">
                        @if($exam->token)
                            <span class="inline-flex items-center gap-1 text-amber-700 font-bold">Perlu Token</span>
                        @else
                            <span class="text-emerald-600 font-semibold">Tanpa Token</span>
                        @endif
                    </div>

                    @if($isOpen)
                        <a href="{{ route('public.ujian.confirm', $exam) }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs transition-all shadow-sm group-hover:scale-[1.02] uppercase tracking-wider">
                            Ikuti Ujian &rarr;
                        </a>
                    @elseif(!$isStarted)
                        <button disabled class="px-4 py-2 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed border border-slate-200">
                            Belum Mulai
                        </button>
                    @else
                        <button disabled class="px-4 py-2 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed border border-slate-200">
                            Ujian Berakhir
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl border border-slate-200 p-12 text-center text-slate-400 space-y-3 shadow-sm">
                <div class="text-base font-bold text-slate-800">Belum Ada Ujian Online Aktif</div>
                <p class="text-xs text-slate-500 max-w-md mx-auto">Saat ini belum ada ujian atau ulangan online yang dijadwalkan. Silakan cek kembali nanti.</p>
            </div>
        @endforelse
    </div>
</main>
@endsection
