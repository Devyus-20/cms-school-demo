@extends('admin.layouts.app')

@section('title', 'Halaman Profil Sekolah')
@section('page-title', 'Halaman Profil Sekolah')

@section('content')
<div class="space-y-6">
    {{-- Top Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800 leading-tight break-words">Modul Halaman Profil Sekolah</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pilih modul di bawah ini untuk membuka halaman pengelolaan data atau menambahkan konten baru.</p>
        </div>
    </div>

    {{-- ======================== 6 MODUL KARTU KATEGORI ======================== --}}
    @php
        $cardDefinitions = [
            'sejarah' => [
                'title' => 'SEJARAH',
                'sub'   => 'Sejarah & Latar Belakang Sekolah',
                'color' => 'from-emerald-600 to-teal-600',
                'bg'    => 'bg-emerald-50/50 border-emerald-200/80 hover:border-emerald-500 hover:shadow-lg hover:-translate-y-0.5',
                'icon'  => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                'badge' => 'bg-emerald-100 text-emerald-800'
            ],
            'visi-dan-misi' => [
                'title' => 'VISI & MISI',
                'sub'   => 'Visi, Misi & Tujuan Pendidikan',
                'color' => 'from-blue-600 to-indigo-600',
                'bg'    => 'bg-blue-50/50 border-blue-200/80 hover:border-blue-500 hover:shadow-lg hover:-translate-y-0.5',
                'icon'  => 'M13 10V3L4 14h7v7l9-11h-7z',
                'badge' => 'bg-blue-100 text-blue-800'
            ],
            'guru-dan-staff' => [
                'title' => 'GURU & STAFF PENGAJAR',
                'sub'   => 'Daftar Tenaga Pendidik & Staf',
                'color' => 'from-purple-600 to-violet-600',
                'bg'    => 'bg-purple-50/50 border-purple-200/80 hover:border-purple-500 hover:shadow-lg hover:-translate-y-0.5',
                'icon'  => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                'badge' => 'bg-purple-100 text-purple-800'
            ],
            'prestasi' => [
                'title' => 'PRESTASI SEKOLAH & SISWA',
                'sub'   => 'Penghargaan & Capaian Prestasi',
                'color' => 'from-amber-600 to-orange-600',
                'bg'    => 'bg-amber-50/50 border-amber-200/80 hover:border-amber-500 hover:shadow-lg hover:-translate-y-0.5',
                'icon'  => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
                'badge' => 'bg-amber-100 text-amber-800'
            ],
            'ekstrakurikuler' => [
                'title' => 'EKSTRAKURIKULER',
                'sub'   => 'Klub, Olahraga & Seni Siswa',
                'color' => 'from-rose-600 to-pink-600',
                'bg'    => 'bg-rose-50/50 border-rose-200/80 hover:border-rose-500 hover:shadow-lg hover:-translate-y-0.5',
                'icon'  => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664zM21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'badge' => 'bg-rose-100 text-rose-800'
            ],
            'fasilitas' => [
                'title' => 'FASILITAS SEKOLAH',
                'sub'   => 'Ruang Kelas, Lab & Sarana',
                'color' => 'from-cyan-600 to-teal-600',
                'bg'    => 'bg-cyan-50/50 border-cyan-200/80 hover:border-cyan-500 hover:shadow-lg hover:-translate-y-0.5',
                'icon'  => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0v-5a2 2 0 012-2h2a2 2 0 012 2v5m-4 0h4',
                'badge' => 'bg-cyan-100 text-cyan-800'
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-2">
        @foreach($cardDefinitions as $slug => $def)
            @php
                $count = $categoryCounts[$slug] ?? 0;
            @endphp
            <div class="relative rounded-3xl border transition-all duration-300 overflow-hidden shadow-sm flex flex-col justify-between p-6 {{ $def['bg'] }} group cursor-pointer">
                {{-- Card Click Area -> Direct to Dedicated Section Page --}}
                <a href="{{ route('admin.pages.section', ['category' => $slug]) }}" class="block space-y-4 flex-1">
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr {{ $def['color'] }} text-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $def['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold tracking-wider {{ $def['badge'] }}">
                            {{ $count }} Konten
                        </span>
                    </div>

                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800 tracking-wide group-hover:text-emerald-700 transition-colors leading-tight">
                            {{ $def['title'] }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                            {{ $def['sub'] }}
                        </p>
                    </div>
                </a>

                {{-- Card Footer & Direct PLUS (+) Add Button --}}
                <div class="mt-6 pt-4 border-t border-slate-200/80 flex items-center justify-between">
                    <a href="{{ route('admin.pages.section', ['category' => $slug]) }}"
                       class="text-xs font-bold text-slate-700 hover:text-emerald-700 flex items-center gap-1.5 group-hover:translate-x-1 transition-transform">
                        <span>Buka Halaman Data</span>
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>

                    {{-- Dedicated PLUS (+) Button for Quick Create --}}
                    <a href="{{ route('admin.pages.create', ['category' => $slug]) }}"
                       title="Tambah Konten Baru untuk {{ $def['title'] }}"
                       class="w-11 h-11 rounded-2xl bg-slate-900 hover:bg-emerald-600 text-white flex items-center justify-center shadow-md transition-all transform hover:scale-110 active:scale-95 group/btn">
                        <svg class="w-6 h-6 transition-transform group-hover/btn:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
