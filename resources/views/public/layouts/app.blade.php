<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $websiteSetting->website_name ?? 'MA Al Ikhlas')</title>
    <meta name="description" content="@yield('meta_description', $websiteSetting->website_description ?? 'CMS Sekolah Digital Terpadu')">
    @if(isset($websiteSetting->favicon) && $websiteSetting->favicon)
        <link rel="icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
    @endif
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#f8fafc] text-slate-800 font-sans min-h-screen flex flex-col justify-between antialiased selection:bg-amber-400 selection:text-slate-950">

    {{-- ======================== TOP CONTACT BAR ZILOM STYLE ======================== --}}
    <div class="bg-[#0f172a] text-slate-300 text-xs py-2.5 px-4 sm:px-8 border-b border-slate-800 hidden sm:block">
        <div class="mx-auto max-w-7xl flex justify-between items-center">
            <div class="flex items-center gap-6">
                @if(isset($websiteSetting->telepon) && $websiteSetting->telepon)
                    <a href="tel:{{ $websiteSetting->telepon }}" class="flex items-center gap-2 hover:text-amber-400 transition-colors">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1.01 1.01 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span class="font-medium">{{ $websiteSetting->telepon }}</span>
                    </a>
                @endif
                @if(isset($websiteSetting->email) && $websiteSetting->email)
                    <a href="mailto:{{ $websiteSetting->email }}" class="flex items-center gap-2 hover:text-amber-400 transition-colors">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="font-medium">{{ $websiteSetting->email }}</span>
                    </a>
                @endif
            </div>
            <div class="flex items-center gap-4 text-slate-300">
                <span class="text-[11px] font-semibold text-slate-400">Media Sosial:</span>
                @if(isset($websiteSetting->facebook) && $websiteSetting->facebook)
                    <a href="{{ $websiteSetting->facebook }}" target="_blank" rel="noopener noreferrer" title="Facebook" class="hover:text-amber-400 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                @endif
                @if(isset($websiteSetting->instagram) && $websiteSetting->instagram)
                    <a href="{{ $websiteSetting->instagram }}" target="_blank" rel="noopener noreferrer" title="Instagram" class="hover:text-amber-400 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                @endif
                @if(isset($websiteSetting->youtube) && $websiteSetting->youtube)
                    <a href="{{ $websiteSetting->youtube }}" target="_blank" rel="noopener noreferrer" title="YouTube" class="hover:text-amber-400 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                @endif

                <a href="/login/siswa" class="px-3.5 py-1.5 rounded-lg bg-amber-500/20 text-amber-300 border border-amber-400/30 text-[11px] font-bold hover:bg-amber-500 hover:text-slate-950 transition-all flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span>Portal Siswa</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ======================== PUBLIC NAVBAR ZILOM STYLE ======================== --}}
    <header class="border-b border-slate-800 bg-[#0f172a]/95 backdrop-blur sticky top-0 z-50 text-white shadow-lg">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 sm:px-8 py-3.5 gap-2">
            {{-- Logo & Brand --}}
            <a href="/" class="flex items-center gap-3.5 group min-w-0">
                @if(isset($websiteSetting->logo) && $websiteSetting->logo)
                    <img src="{{ \Illuminate\Support\Str::startsWith($websiteSetting->logo, ['http://', 'https://']) ? $websiteSetting->logo : asset($websiteSetting->logo) }}" alt="Logo" class="w-10 h-10 object-contain group-hover:scale-105 transition-transform shrink-0">
                @else
                    <img src="{{ asset('images/default-logo.png') }}" alt="Logo" class="w-10 h-10 object-contain group-hover:scale-105 transition-transform shrink-0">
                @endif
                <div class="min-w-0">
                    <div class="text-lg font-bold tracking-tight text-white leading-none group-hover:text-amber-400 transition-colors truncate">
                        {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}
                    </div>
                    <div class="text-[11px] font-medium text-slate-400 mt-1 uppercase tracking-wider truncate">
                        {{ $websiteSetting->website_description ?? 'Sekolah Digital Terpadu' }}
                    </div>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center gap-1 text-xs uppercase font-bold tracking-wider text-slate-300">
                {{-- Beranda --}}
                <a href="/" class="px-4 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-colors {{ request()->is('/') ? 'text-amber-400 bg-slate-800/80 border border-slate-700' : '' }}">
                    Beranda
                </a>

                {{-- Profil Dropdown --}}
                <div class="relative group">
                    <button class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-colors cursor-pointer {{ request()->is('profil*') ? 'text-amber-400 bg-slate-800/80 border border-slate-700' : '' }}">
                        <span>Profil</span>
                        <svg class="w-3.5 h-3.5 text-slate-500 group-hover:rotate-180 group-hover:text-amber-400 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute top-full left-0 w-56 pt-2 hidden group-hover:block z-50">
                        <div class="bg-[#0f172a] rounded-2xl border border-slate-700 shadow-2xl p-2 space-y-1">
                            @if(isset($profilNavItems) && count($profilNavItems) > 0)
                                @foreach($profilNavItems as $item)
                                    <a href="/profil/{{ $item['slug'] }}" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">
                                        {{ $item['judul'] }}
                                    </a>
                                @endforeach
                            @else
                                <a href="/profil/sejarah" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">Sejarah</a>
                                <a href="/profil/visi-dan-misi" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">Visi dan Misi</a>
                                <a href="/profil/prestasi" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">Prestasi</a>
                                <a href="/profil/ekstrakurikuler" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">Ekstrakurikuler</a>
                                <a href="/profil/guru-dan-staff" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">Guru dan Staff</a>
                                <a href="/profil/fasilitas" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">Fasilitas</a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Informasi Dropdown --}}
                <div class="relative group">
                    <button class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-colors cursor-pointer {{ request()->is('informasi*') || request()->is('galeri*') ? 'text-amber-400 bg-slate-800/80 border border-slate-700' : '' }}">
                        <span>Informasi</span>
                        <svg class="w-3.5 h-3.5 text-slate-500 group-hover:rotate-180 group-hover:text-amber-400 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute top-full left-0 w-48 pt-2 hidden group-hover:block z-50">
                        <div class="bg-[#0f172a] rounded-2xl border border-slate-700 shadow-2xl p-2 space-y-1">
                            <a href="/informasi/artikel" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">Artikel</a>
                            <a href="/informasi/berita" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">Berita</a>
                            <a href="/informasi/pengumuman" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">Pengumuman</a>
                            <a href="/galeri" class="block px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition-colors">Galeri Foto</a>
                        </div>
                    </div>
                </div>

                {{-- PPDB --}}
                <a href="/ppdb" class="px-4 py-2.5 rounded-xl text-amber-400 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 flex items-center gap-2 {{ request()->is('ppdb*') ? 'bg-amber-500/20 text-amber-300' : '' }}">
                    <span>PPDB</span>
                </a>

                {{-- Registrasi Siswa --}}
                <a href="/register" class="px-4 py-2.5 rounded-xl hover:bg-slate-800 hover:text-white transition-colors {{ request()->is('register*') ? 'text-amber-400 font-bold' : '' }}">
                    Registrasi
                </a>

                {{-- Login Siswa --}}
                @if(auth()->check() && auth()->user()->role?->name === 'Siswa')
                    <a href="{{ route('siswa.dashboard') }}" class="ml-3 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition-all shadow-md flex items-center gap-2 hover:scale-105 active:scale-95">
                        <span>PORTAL SISWA</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    <a href="/login/siswa" class="ml-3 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition-all shadow-md flex items-center gap-2 hover:scale-105 active:scale-95">
                        <span>PORTAL SISWA</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endif
            </nav>

            {{-- Mobile Toggle Button --}}
            <button id="public-mobile-toggle" type="button" class="md:hidden p-2 rounded-xl hover:bg-slate-100 text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Drawer --}}
        <div id="public-mobile-drawer" class="hidden md:hidden border-t border-slate-100 bg-white px-6 py-4 space-y-3">
            <a href="/" class="block py-1.5 font-bold text-slate-800">Beranda</a>
            <div class="space-y-1">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Profil</div>
                @if(isset($profilNavItems) && count($profilNavItems) > 0)
                    @foreach($profilNavItems as $item)
                        <a href="/profil/{{ $item['slug'] }}" class="block pl-3 py-1 text-sm font-semibold text-slate-600 hover:text-emerald-600">{{ $item['judul'] }}</a>
                    @endforeach
                @else
                    <a href="/profil/sejarah" class="block pl-3 py-1 text-sm font-semibold text-slate-600 hover:text-emerald-600">Sejarah</a>
                    <a href="/profil/visi-dan-misi" class="block pl-3 py-1 text-sm font-semibold text-slate-600 hover:text-emerald-600">Visi dan Misi</a>
                    <a href="/profil/prestasi" class="block pl-3 py-1 text-sm font-semibold text-slate-600 hover:text-emerald-600">Prestasi</a>
                    <a href="/profil/ekstrakurikuler" class="block pl-3 py-1 text-sm font-semibold text-slate-600 hover:text-emerald-600">Ekstrakurikuler</a>
                    <a href="/profil/guru-dan-staff" class="block pl-3 py-1 text-sm font-semibold text-slate-600 hover:text-emerald-600">Guru dan Staff</a>
                    <a href="/profil/fasilitas" class="block pl-3 py-1 text-sm font-semibold text-slate-600 hover:text-emerald-600">Fasilitas</a>
                @endif
            </div>
            <div class="space-y-1 pt-2">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Informasi</div>
                <a href="/informasi/artikel" class="block pl-3 py-1 text-sm font-semibold text-slate-600 hover:text-emerald-600">Artikel</a>
                <a href="/informasi/berita" class="block pl-3 py-1 text-sm font-semibold text-slate-600 hover:text-emerald-600">Berita</a>
                <a href="/informasi/pengumuman" class="block pl-3 py-1 text-sm font-semibold text-slate-600 hover:text-emerald-600">Pengumuman</a>
                <a href="/galeri" class="block pl-3 py-1 text-sm font-semibold text-slate-600 hover:text-emerald-600">Galeri</a>
            </div>
            <div class="pt-2 flex flex-col gap-2">
                <a href="/register" class="block text-center py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-sm font-bold transition-colors">Registrasi Siswa</a>
                @if(auth()->check() && auth()->user()->role?->name === 'Siswa')
                    <a href="{{ route('siswa.dashboard') }}" class="block text-center py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold">Portal Siswa &rarr;</a>
                @else
                    <a href="/login/siswa" class="block text-center py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold">Login Siswa &rarr;</a>
                @endif
                <a href="/ppdb" class="block text-center py-2 rounded-xl bg-teal-700 text-white text-sm font-bold">PPDB (Pendaftaran Siswa Baru)</a>
            </div>
        </div>
    </header>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 w-full flex flex-col">
        @yield('content')
    </main>

    {{-- ======================== ZILOM MULTI-COLUMN DARK FOOTER (FIXED / STICKY BOTTOM) ======================== --}}
    <footer class="bg-[#0b1320] text-slate-300 border-t border-slate-800 mt-auto w-full">
        <div class="mx-auto max-w-7xl px-6 py-14 grid grid-cols-1 md:grid-cols-4 gap-10 text-xs">
            {{-- Brand Column --}}
            <div class="space-y-4 md:col-span-1">
                <div class="flex items-center gap-3">
                    @if(isset($websiteSetting->logo) && $websiteSetting->logo)
                        <img src="{{ \Illuminate\Support\Str::startsWith($websiteSetting->logo, ['http://', 'https://']) ? $websiteSetting->logo : asset($websiteSetting->logo) }}" alt="Logo" class="w-10 h-10 object-contain shrink-0">
                    @else
                        <img src="{{ asset('images/default-logo.png') }}" alt="Logo" class="w-10 h-10 object-contain shrink-0">
                    @endif
                    <div class="min-w-0">
                        <div class="text-base font-bold text-white leading-tight truncate">
                            {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}
                        </div>
                        <div class="text-[10px] font-medium text-slate-400 mt-0.5 uppercase tracking-wider truncate">
                            {{ $websiteSetting->website_description ?? 'Sekolah Digital Terpadu' }}
                        </div>
                    </div>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed font-normal">
                    Membangun generasi penerus yang berakhlak mulia, berprestasi unggul, dan menguasai teknologi digital.
                </p>

                {{-- Social Media Links --}}
                <div class="pt-2 flex items-center gap-3">
                    @if(isset($websiteSetting->facebook) && $websiteSetting->facebook)
                        <a href="{{ $websiteSetting->facebook }}" target="_blank" rel="noopener noreferrer" title="Facebook" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-amber-500 hover:text-slate-950 text-slate-300 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    @endif
                    @if(isset($websiteSetting->instagram) && $websiteSetting->instagram)
                        <a href="{{ $websiteSetting->instagram }}" target="_blank" rel="noopener noreferrer" title="Instagram" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-amber-500 hover:text-slate-950 text-slate-300 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    @endif
                    @if(isset($websiteSetting->youtube) && $websiteSetting->youtube)
                        <a href="{{ $websiteSetting->youtube }}" target="_blank" rel="noopener noreferrer" title="YouTube" class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-amber-500 hover:text-slate-950 text-slate-300 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Navigation Links --}}
            <div class="space-y-3">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Navigasi Utama</h3>
                <ul class="space-y-2 text-xs">
                    <li><a href="/" class="hover:text-amber-400 transition-colors">Beranda</a></li>
                    <li><a href="/profil/sejarah" class="hover:text-amber-400 transition-colors">Profil Sekolah</a></li>
                    <li><a href="/informasi/berita" class="hover:text-amber-400 transition-colors">Berita & Informasi</a></li>
                    <li><a href="/galeri" class="hover:text-amber-400 transition-colors">Galeri Foto</a></li>
                    <li><a href="/ppdb" class="hover:text-amber-400 transition-colors">PPDB Online</a></li>
                    <li><a href="/register" class="hover:text-amber-400 transition-colors">Registrasi Siswa</a></li>
                </ul>
            </div>

            {{-- Informasi & Profil Links --}}
            <div class="space-y-3">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Informasi & Profil</h3>
                <ul class="space-y-2 text-xs">
                    <li><a href="/informasi/artikel" class="hover:text-amber-400 transition-colors">Artikel Edukasi</a></li>
                    <li><a href="/informasi/berita" class="hover:text-amber-400 transition-colors">Berita Sekolah</a></li>
                    <li><a href="/informasi/pengumuman" class="hover:text-amber-400 transition-colors">Pengumuman</a></li>
                    <li><a href="/profil/prestasi" class="hover:text-amber-400 transition-colors">Prestasi Siswa</a></li>
                    <li><a href="/profil/ekstrakurikuler" class="hover:text-amber-400 transition-colors">Ekstrakurikuler</a></li>
                    <li><a href="/profil/fasilitas" class="hover:text-amber-400 transition-colors">Fasilitas</a></li>
                </ul>
            </div>

            {{-- Contact Information Column --}}
            <div class="space-y-3">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Informasi Kontak</h3>
                <ul class="space-y-2.5 text-xs text-slate-300">
                    @if(isset($websiteSetting->alamat) && $websiteSetting->alamat)
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $websiteSetting->alamat }}</span>
                        </li>
                    @endif
                    @if(isset($websiteSetting->telepon) && $websiteSetting->telepon)
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1.01 1.01 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:{{ $websiteSetting->telepon }}" class="hover:text-amber-400 transition-colors">{{ $websiteSetting->telepon }}</a>
                        </li>
                    @endif
                    @if(isset($websiteSetting->email) && $websiteSetting->email)
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $websiteSetting->email }}" class="hover:text-amber-400 transition-colors">{{ $websiteSetting->email }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Bottom Copyright Bar --}}
        <div class="border-t border-slate-800 bg-[#090d16] px-6 py-4 text-center text-xs text-slate-500 font-medium">
            <p>{{ $websiteSetting->footer ?? '© 2026 ' . ($websiteSetting->website_name ?? 'MA Al Ikhlas') . '. Semua Hak Cipta Dilindungi.' }}</p>
        </div>
    </footer>

    {{-- ======================== MODERN IMAGE LIGHTBOX MODAL ======================== --}}
    <div id="image-lightbox" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-950/90 backdrop-blur-xl p-4 sm:p-8 transition-all duration-300 opacity-0 pointer-events-none select-none">
        {{-- Clickable Backdrop --}}
        <div class="absolute inset-0 cursor-zoom-out" id="lightbox-backdrop"></div>

        {{-- Top Floating Toolbar --}}
        <div class="absolute top-4 left-4 right-4 sm:top-6 sm:left-6 sm:right-6 flex items-center justify-between z-20 pointer-events-auto">
            <div class="flex items-center gap-3 bg-slate-900/80 border border-slate-700/60 rounded-2xl px-4 py-2 text-white shadow-xl backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span id="lightbox-caption" class="text-xs sm:text-sm font-semibold truncate max-w-[180px] sm:max-w-md">Foto</span>
                <span id="lightbox-counter" class="text-[11px] font-bold px-2 py-0.5 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">1 / 1</span>
            </div>

            <button id="lightbox-close" type="button" aria-label="Tutup Foto" class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-slate-900/80 hover:bg-red-600/90 border border-slate-700/60 text-white flex items-center justify-center transition-all duration-200 shadow-xl backdrop-blur-md group hover:rotate-90 cursor-pointer">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Previous Button --}}
        <button id="lightbox-prev" type="button" aria-label="Foto Sebelumnya" class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-13 sm:h-13 rounded-2xl bg-slate-900/80 hover:bg-emerald-600 border border-slate-700/60 text-white flex items-center justify-center transition-all duration-200 shadow-2xl backdrop-blur-md hover:scale-110 cursor-pointer disabled:opacity-30 disabled:pointer-events-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        {{-- Next Button --}}
        <button id="lightbox-next" type="button" aria-label="Foto Selanjutnya" class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-13 sm:h-13 rounded-2xl bg-slate-900/80 hover:bg-emerald-600 border border-slate-700/60 text-white flex items-center justify-center transition-all duration-200 shadow-2xl backdrop-blur-md hover:scale-110 cursor-pointer disabled:opacity-30 disabled:pointer-events-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        {{-- Main Display Container --}}
        <div class="relative max-w-5xl max-h-[85vh] z-10 flex flex-col items-center justify-center p-2 sm:p-4">
            <div id="lightbox-img-wrapper" class="relative rounded-2xl sm:rounded-3xl overflow-hidden border border-white/15 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.8)] bg-slate-900/50 backdrop-blur-sm transform transition-all duration-300 scale-95">
                <img id="lightbox-img" src="" alt="Full Preview" class="max-w-full max-h-[78vh] object-contain rounded-2xl sm:rounded-3xl transition-transform duration-300">
            </div>
            <p class="text-xs text-slate-400 mt-3 font-medium text-center hidden sm:block">
                Tekan <kbd class="px-1.5 py-0.5 rounded bg-slate-800 border border-slate-700 text-slate-300 text-[10px] font-mono">Esc</kbd> atau klik di luar foto untuk menutup
            </p>
        </div>
    </div>

    <script>
        (function() {
            // Mobile menu toggle
            var btn = document.getElementById('public-mobile-toggle');
            var drawer = document.getElementById('public-mobile-drawer');
            if (btn && drawer) {
                btn.addEventListener('click', function() {
                    drawer.classList.toggle('hidden');
                });
            }

            // Modern Lightbox Logic
            var lightbox = document.getElementById('image-lightbox');
            var lightboxImg = document.getElementById('lightbox-img');
            var lightboxWrapper = document.getElementById('lightbox-img-wrapper');
            var lightboxCaption = document.getElementById('lightbox-caption');
            var lightboxCounter = document.getElementById('lightbox-counter');
            var closeBtn = document.getElementById('lightbox-close');
            var backdrop = document.getElementById('lightbox-backdrop');
            var prevBtn = document.getElementById('lightbox-prev');
            var nextBtn = document.getElementById('lightbox-next');

            if (!lightbox || !lightboxImg) return;

            var currentGroup = [];
            var currentIndex = 0;

            function updateLightboxImage() {
                if (currentGroup.length === 0) return;
                var currentItem = currentGroup[currentIndex];

                lightboxImg.style.opacity = '0';
                lightboxImg.style.transform = 'scale(0.96)';

                setTimeout(function() {
                    lightboxImg.src = currentItem.src;
                    lightboxCaption.textContent = currentItem.alt || 'Foto';
                    lightboxCounter.textContent = (currentIndex + 1) + ' / ' + currentGroup.length;
                    
                    lightboxImg.onload = function() {
                        lightboxImg.style.opacity = '1';
                        lightboxImg.style.transform = 'scale(1)';
                    };
                }, 150);

                if (prevBtn) prevBtn.disabled = (currentIndex === 0);
                if (nextBtn) nextBtn.disabled = (currentIndex === currentGroup.length - 1);
            }

            function openLightbox(group, index) {
                currentGroup = group;
                currentIndex = index;
                updateLightboxImage();

                lightbox.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                setTimeout(function() {
                    lightbox.classList.remove('opacity-0', 'pointer-events-none');
                    lightbox.classList.add('opacity-100', 'pointer-events-auto');
                    if (lightboxWrapper) {
                        lightboxWrapper.classList.remove('scale-95');
                        lightboxWrapper.classList.add('scale-100');
                    }
                }, 10);
            }

            function closeLightbox() {
                lightbox.classList.remove('opacity-100', 'pointer-events-auto');
                lightbox.classList.add('opacity-0', 'pointer-events-none');
                if (lightboxWrapper) {
                    lightboxWrapper.classList.remove('scale-100');
                    lightboxWrapper.classList.add('scale-95');
                }

                setTimeout(function() {
                    lightbox.classList.add('hidden');
                    document.body.style.overflow = '';
                    lightboxImg.src = '';
                }, 300);
            }

            if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
            if (backdrop) backdrop.addEventListener('click', closeLightbox);

            if (prevBtn) {
                prevBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (currentIndex > 0) {
                        currentIndex--;
                        updateLightboxImage();
                    }
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (currentIndex < currentGroup.length - 1) {
                        currentIndex++;
                        updateLightboxImage();
                    }
                });
            }

            document.addEventListener('keydown', function(e) {
                if (lightbox.classList.contains('hidden')) return;
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft' && currentIndex > 0) {
                    currentIndex--;
                    updateLightboxImage();
                }
                if (e.key === 'ArrowRight' && currentIndex < currentGroup.length - 1) {
                    currentIndex++;
                    updateLightboxImage();
                }
            });

            // Attach click listeners to all main article & gallery images
            document.addEventListener('DOMContentLoaded', function() {
                var mainContainer = document.querySelector('main');
                if (!mainContainer) return;

                // Group images by container (e.g. grid container, article, or galeri)
                var containers = mainContainer.querySelectorAll('.grid, article, .space-y-8, .space-y-6');
                
                containers.forEach(function(container) {
                    var imgs = Array.from(container.querySelectorAll('img')).filter(function(img) {
                        // Skip header logos and small icons
                        return !img.closest('header') && !img.closest('footer') && img.naturalWidth > 50;
                    });

                    if (imgs.length === 0) return;

                    var groupData = imgs.map(function(img) {
                        return {
                            src: img.getAttribute('src') || img.src,
                            alt: img.getAttribute('alt') || 'Foto Kegiatan'
                        };
                    });

                    imgs.forEach(function(img, idx) {
                        img.style.cursor = 'zoom-in';
                        img.classList.add('transition-all', 'duration-300', 'hover:brightness-105');
                        
                        img.addEventListener('click', function(e) {
                            e.preventDefault();
                            openLightbox(groupData, idx);
                        });
                    });
                });
            });
        })();
    </script>
</body>
</html>
