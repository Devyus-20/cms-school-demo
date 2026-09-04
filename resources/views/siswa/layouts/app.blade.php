<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Siswa') - {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}</title>
    @if(isset($websiteSetting->favicon) && $websiteSetting->favicon)
        <link rel="icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans min-h-screen selection:bg-amber-400 selection:text-slate-950 flex flex-col justify-between">

    {{-- Top Navbar for Students Zilom White Concept --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 text-slate-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if(isset($websiteSetting->logo) && $websiteSetting->logo)
                    <img src="{{ \Illuminate\Support\Str::startsWith($websiteSetting->logo, ['http://', 'https://']) ? $websiteSetting->logo : asset($websiteSetting->logo) }}" alt="Logo {{ $websiteSetting->website_name ?? 'Sekolah' }}" class="w-10 h-10 object-contain shrink-0">
                @else
                    <img src="{{ asset('images/default-logo.png') }}" alt="Logo {{ $websiteSetting->website_name ?? 'Sekolah' }}" class="w-10 h-10 object-contain shrink-0">
                @endif
                <div>
                    <span class="font-extrabold text-base tracking-wide block leading-none text-slate-900">Portal Siswa</span>
                    <span class="text-[10px] text-amber-600 font-bold uppercase tracking-wider">{{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}</span>
                </div>
            </div>

            {{-- Nav Links --}}
            <nav class="hidden md:flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-slate-600">
                <a href="{{ route('siswa.dashboard') }}"
                   class="px-3.5 py-2 rounded-[5px] transition-colors {{ request()->routeIs('siswa.dashboard') ? 'bg-amber-500 text-slate-950 shadow-md font-extrabold' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    Dashboard
                </a>
                <a href="{{ route('siswa.presensi') }}"
                   class="px-3.5 py-2 rounded-[5px] transition-colors {{ request()->routeIs('siswa.presensi') ? 'bg-amber-500 text-slate-950 shadow-md font-extrabold' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    Presensi Saya
                </a>
                <a href="{{ route('siswa.tugas') }}"
                   class="px-3.5 py-2 rounded-[5px] transition-colors {{ request()->routeIs('siswa.tugas') ? 'bg-amber-500 text-slate-950 shadow-md font-extrabold' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    Tugas Saya
                </a>
                <a href="{{ route('siswa.nilai') }}"
                   class="px-3.5 py-2 rounded-[5px] transition-colors {{ request()->routeIs('siswa.nilai') ? 'bg-amber-500 text-slate-950 shadow-md font-extrabold' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    Nilai & Ranking
                </a>
                <a href="{{ route('siswa.password') }}"
                   class="px-3.5 py-2 rounded-[5px] transition-colors {{ request()->routeIs('siswa.password*') ? 'bg-amber-500 text-slate-950 shadow-md font-extrabold' : 'hover:bg-slate-100 hover:text-slate-900' }}">
                    Ubah Password
                </a>
                <a href="{{ route('public.ujian.index') }}" target="_blank"
                   class="px-3.5 py-2 rounded-[5px] hover:bg-slate-100 hover:text-slate-900 transition-colors text-amber-600 font-bold flex items-center gap-1">
                    <span>Ujian Online CBT</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </nav>

            {{-- Profile & Logout --}}
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <span class="block text-xs font-bold text-slate-900">{{ auth()->user()->name }}</span>
                    <span class="block text-[10px] text-amber-600 font-bold uppercase tracking-wider">Peserta Didik</span>
                </div>
                <a href="{{ route('siswa.password') }}" title="Ubah Password Akun Mandiri"
                   class="px-3 py-1.5 rounded-[5px] bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 transition-colors flex items-center gap-1.5 border border-slate-200">
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    <span class="hidden lg:inline">Ubah Password</span>
                </a>
                <a href="{{ route('logout') }}" class="px-3 py-1.5 rounded-[5px] bg-red-50 border border-red-200 hover:bg-red-600 hover:text-white text-xs font-bold text-red-600 transition-colors">
                    Keluar
                </a>
            </div>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 text-center py-6 text-xs text-slate-500 bg-white mt-auto">
        &copy; 2026 {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}. Portal Akademik Siswa.
    </footer>

</body>
</html>
