<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}</title>
    @if(isset($websiteSetting->favicon) && $websiteSetting->favicon)
        <link rel="icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans h-screen overflow-hidden">
<div class="h-screen flex overflow-hidden relative">

    {{-- Backdrop for Mobile Sidebar --}}
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 hidden md:hidden transition-opacity"></div>

    {{-- ======================== SIDEBAR ======================== --}}
    <aside id="sidebar" class="fixed md:sticky top-0 inset-y-0 left-0 w-64 bg-slate-900 text-slate-300 h-screen flex flex-col justify-between shrink-0 z-40 md:z-20 -translate-x-full md:translate-x-0 transition-transform duration-300">
        <div class="flex flex-col overflow-y-auto flex-1 min-h-0">
            {{-- Brand --}}
            <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800 shrink-0">
                <div class="flex items-center space-x-2 sidebar-label">
                    @if(isset($websiteSetting->logo) && $websiteSetting->logo)
                        <img src="{{ \Illuminate\Support\Str::startsWith($websiteSetting->logo, ['http://', 'https://']) ? $websiteSetting->logo : asset($websiteSetting->logo) }}" alt="Logo" class="w-8 h-8 rounded-[5px] object-contain bg-white/10 p-0.5 shrink-0">
                    @else
                        <img src="{{ asset('images/default-logo.png') }}" alt="Logo" class="w-8 h-8 rounded-[5px] object-contain bg-white/10 p-0.5 shrink-0">
                    @endif
                    <div>
                        <span class="font-bold text-white text-base tracking-wide block leading-none">{{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}</span>
                        <span class="text-[10px] text-emerald-400 font-semibold uppercase tracking-wider">CMS Admin</span>
                    </div>
                </div>
                <button id="sidebar-toggle" type="button" class="p-2 rounded-[5px] hover:bg-slate-800 text-slate-400 hover:text-white transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <nav class="p-3 space-y-0.5">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('dashboard') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="sidebar-label">Dashboard</span>
                </a>

                {{-- ---- AKADEMIK & SISWA ---- --}}
                @if(auth()->user()->hasPermission('Kelola Akademik') || auth()->user()->hasPermission('Kelola User') || auth()->user()->hasPermission('Kelola Website'))
                <p class="sidebar-label px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Akademik & Siswa</p>

                @if(auth()->user()->hasPermission('Kelola Akademik') || auth()->user()->hasPermission('Kelola User'))
                {{-- Data Siswa (Whitelist Email) --}}
                <a href="{{ route('admin.siswa.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.siswa*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                    <span class="sidebar-label">Data Siswa</span>
                </a>

                {{-- Presensi Siswa --}}
                <a href="{{ route('admin.presensi.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.presensi*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="sidebar-label">Presensi Siswa</span>
                </a>

                {{-- Tugas Siswa --}}
                <a href="{{ route('admin.tugas.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.tugas*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="sidebar-label">Tugas Siswa</span>
                </a>

                {{-- Rekap & Perankingan --}}
                <a href="{{ route('admin.rekap.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.rekap*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="sidebar-label">Rekap & Perankingan</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('Kelola Website') || auth()->user()->hasPermission('Kelola Akademik') || auth()->user()->hasPermission('Kelola User'))
                {{-- Ujian Online --}}
                <a href="{{ route('admin.exams.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.exams*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="sidebar-label">Ujian Online</span>
                </a>
                @endif
                @endif

                {{-- ---- PUSAT LAPORAN (DEDICATED SECTION) ---- --}}
                <p class="sidebar-label px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pusat Laporan</p>

                {{-- Dashboard Pusat Laporan --}}
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.reports*') && !request()->has('type') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="sidebar-label">Pusat Laporan</span>
                </a>

                {{-- Sub Laporan Siswa --}}
                <a href="{{ route('admin.reports.index', ['type' => 'siswa']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.reports*') && request('type') === 'siswa' ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="sidebar-label">Lap. Data Siswa</span>
                </a>

                {{-- Sub Laporan Nilai --}}
                <a href="{{ route('admin.reports.index', ['type' => 'nilai']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.reports*') && request('type') === 'nilai' ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="sidebar-label">Lap. Nilai & Ranking</span>
                </a>

                {{-- Sub Laporan Presensi --}}
                <a href="{{ route('admin.reports.index', ['type' => 'presensi']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.reports*') && request('type') === 'presensi' ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="sidebar-label">Lap. Presensi Siswa</span>
                </a>

                {{-- Sub Laporan Ujian --}}
                <a href="{{ route('admin.reports.index', ['type' => 'ujian']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.reports*') && request('type') === 'ujian' ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="sidebar-label">Lap. Hasil Ujian (CBT)</span>
                </a>

                {{-- Sub Laporan PPDB --}}
                <a href="{{ route('admin.reports.index', ['type' => 'ppdb']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.reports*') && request('type') === 'ppdb' ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span class="sidebar-label">Lap. Pendaftar PPDB</span>
                </a>

                {{-- ---- KONTEN WEBSITE ---- --}}
                @if(auth()->user()->hasPermission('Kelola Website') || auth()->user()->hasPermission('Tambah Berita'))
                <p class="sidebar-label px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Konten Website</p>

                @if(auth()->user()->hasPermission('Kelola Website'))
                {{-- Halaman Profil --}}
                <a href="{{ route('admin.pages') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.pages*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="sidebar-label">Halaman Profil</span>
                </a>

                {{-- Galeri --}}
                <a href="{{ route('admin.galleries') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.galleries*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="sidebar-label">Galeri</span>
                </a>
                @endif

                @if(auth()->user()->hasPermission('Tambah Berita'))
                {{-- Artikel --}}
                <a href="{{ route('admin.posts', ['tipe' => 'artikel']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.posts*') && request()->query('tipe') === 'artikel' ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span class="sidebar-label">Artikel</span>
                </a>

                {{-- Berita --}}
                <a href="{{ route('admin.posts', ['tipe' => 'berita']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.posts*') && request()->query('tipe') === 'berita' ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : (request()->routeIs('admin.posts*') && !request()->query('tipe') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80') }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <span class="sidebar-label">Berita</span>
                </a>

                {{-- Pengumuman --}}
                <a href="{{ route('admin.posts', ['tipe' => 'pengumuman']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.posts*') && request()->query('tipe') === 'pengumuman' ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span class="sidebar-label">Pengumuman</span>
                </a>
                @endif
                @endif

                {{-- ---- PPDB & PENGATURAN ---- --}}
                @if(auth()->user()->hasPermission('Kelola Settings'))
                <p class="sidebar-label px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">PPDB & Pengaturan</p>

                {{-- Pendaftar PPDB --}}
                <a href="{{ route('admin.ppdb.index') }}"
                   class="flex items-center justify-between px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.ppdb*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span class="sidebar-label">Pendaftar PPDB</span>
                    </div>
                    @php $pendingCount = \App\Models\PpdbRegistration::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="sidebar-label px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-slate-900 shrink-0">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                @if(auth()->user()->role?->name === 'Admin')
                {{-- Settings --}}
                <a href="{{ route('admin.settings') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.settings*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="sidebar-label">Settings</span>
                </a>
                @endif
                @endif

                {{-- ---- TAKSONOMI ---- --}}
                @if(auth()->user()->hasPermission('Kelola Website'))
                <p class="sidebar-label px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Taksonomi</p>

                <a href="{{ route('admin.categories') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.categories*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span class="sidebar-label">Kategori</span>
                </a>

                <a href="{{ route('admin.tags') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.tags*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span class="sidebar-label">Tag</span>
                </a>
                @endif

                {{-- ---- MANAJEMEN USER ---- --}}
                @if(auth()->user()->hasPermission('Kelola User'))
                <p class="sidebar-label px-3 pt-4 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Manajemen User</p>

                <a href="{{ route('admin.users') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.users*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="sidebar-label">User</span>
                </a>

                <a href="{{ route('admin.roles') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.roles*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="sidebar-label">Role</span>
                </a>

                <a href="{{ route('admin.permissions') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.permissions*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <span class="sidebar-label">Permission</span>
                </a>

                <a href="{{ route('admin.activity-logs.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-medium transition-all
                   {{ request()->routeIs('admin.activity-logs*') ? 'bg-emerald-400 text-slate-950 font-bold shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800/80' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="sidebar-label">Activity Log</span>
                </a>
                @endif

            </nav>
        </div>

        {{-- Logout --}}
        <div class="p-3 border-t border-slate-800 shrink-0">
            <a href="{{ route('logout') }}"
               class="w-full flex items-center gap-3 px-3 py-2.5 rounded-[5px] text-sm font-semibold text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="sidebar-label">Logout</span>
            </a>
        </div>
    </aside>

    {{-- ======================== MAIN AREA ======================== --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-3 sm:px-6 z-10 shrink-0 gap-2">
            <div class="flex items-center min-w-0 gap-2">
                <button id="mobile-sidebar-open" type="button" class="md:hidden p-2 rounded-[5px] text-slate-600 hover:bg-slate-100 shrink-0 focus:outline-none" aria-label="Buka Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-base sm:text-lg md:text-xl font-bold text-slate-800 truncate leading-tight">
                    @yield('page-title', 'Admin Panel')
                </h1>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                @if(auth()->user()->hasPermission('Kelola User'))
                <a href="{{ route('admin.activity-logs.index') }}" title="Activity Log"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 sm:px-3.5 py-2 rounded-[5px] bg-purple-50 text-purple-700 hover:bg-purple-100 transition-colors border border-purple-200">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="hidden sm:inline">Activity Log</span>
                </a>
                @endif
                @if(auth()->user()->hasPermission('Kelola Website') || auth()->user()->hasPermission('Kelola Akademik') || auth()->user()->hasPermission('Kelola User'))
                <a href="{{ route('admin.exams.index') }}" title="Kelola Ujian Online"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 sm:px-3.5 py-2 rounded-[5px] bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors border border-blue-200">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="hidden sm:inline">Ujian Online</span>
                </a>
                @endif
                <a href="/" target="_blank" title="Lihat Website"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 sm:px-3.5 py-2 rounded-[5px] bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors border border-emerald-200">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span class="hidden sm:inline">Lihat Website</span>
                </a>

                <div class="flex items-center gap-2 sm:gap-3 border-l border-slate-200 pl-2 sm:pl-4">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-gradient-to-tr from-emerald-700 to-teal-500 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-md shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->username ?? 'A', 0, 1)) }}
                    </div>
                    <div class="hidden sm:block">
                        <div class="text-xs sm:text-sm font-semibold text-slate-800 leading-none">{{ auth()->user()->name ?? auth()->user()->username }}</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">{{ auth()->user()->role?->name ?? 'Admin' }}</div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-4 sm:p-6 overflow-y-auto">
            @if(session('success'))
                <div class="mb-5 flex items-center gap-2 p-4 rounded-[5px] bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold">
                    <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 flex items-center gap-2 p-4 rounded-[5px] bg-red-50 border border-red-200 text-red-800 text-sm font-semibold">
                    <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</div>
<script>
(function () {
    var sidebar = document.getElementById('sidebar');
    var toggleBtn = document.getElementById('sidebar-toggle');
    var mobileOpenBtn = document.getElementById('mobile-sidebar-open');
    var backdrop = document.getElementById('sidebar-backdrop');
    var labels = document.querySelectorAll('.sidebar-label');

    function applyState(open) {
        if (window.innerWidth >= 768) {
            if (open) {
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                labels.forEach(function (el) { el.classList.remove('hidden'); });
            } else {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                labels.forEach(function (el) { el.classList.add('hidden'); });
            }
        }
    }

    function toggleMobileSidebar(show) {
        if (show) {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0', 'w-64');
            labels.forEach(function (el) { el.classList.remove('hidden'); });
            backdrop.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            backdrop.classList.add('hidden');
        }
    }

    var stored = localStorage.getItem('sidebarOpen');
    applyState(stored === null ? true : stored === 'true');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            if (window.innerWidth < 768) {
                toggleMobileSidebar(false);
            } else {
                var next = !sidebar.classList.contains('w-64');
                localStorage.setItem('sidebarOpen', next);
                applyState(next);
            }
        });
    }

    if (mobileOpenBtn) {
        mobileOpenBtn.addEventListener('click', function () {
            toggleMobileSidebar(true);
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', function () {
            toggleMobileSidebar(false);
        });
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            backdrop.classList.add('hidden');
            sidebar.classList.remove('-translate-x-full');
            applyState(localStorage.getItem('sidebarOpen') === 'true');
        } else {
            sidebar.classList.add('-translate-x-full');
        }
    });
})();
</script>
</body>
</html>