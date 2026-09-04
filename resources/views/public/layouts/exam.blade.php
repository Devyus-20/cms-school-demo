<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Ujian Online') - {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}</title>
    <meta name="description" content="Portal Ujian Online Terpadu Sekolah">
    @if(isset($websiteSetting->favicon) && $websiteSetting->favicon)
        <link rel="icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
    @endif
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col justify-between antialiased selection:bg-amber-400 selection:text-slate-950">

    {{-- ======================== EXAM STANDALONE HEADER WHITE ======================== --}}
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 text-slate-800 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            
            {{-- Brand / Logo --}}
            <div class="flex items-center gap-3">
                @if(isset($websiteSetting->logo) && $websiteSetting->logo)
                    <img src="{{ \Illuminate\Support\Str::startsWith($websiteSetting->logo, ['http://', 'https://']) ? $websiteSetting->logo : asset($websiteSetting->logo) }}" alt="Logo" class="w-9 h-9 object-contain shrink-0">
                @else
                    <img src="{{ asset('images/default-logo.png') }}" alt="Logo" class="w-9 h-9 object-contain shrink-0">
                @endif
                <div>
                    <div class="font-extrabold text-slate-900 text-sm sm:text-base tracking-tight leading-tight flex items-center gap-2">
                        <span>{{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}</span>
                        <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-extrabold uppercase tracking-wider border border-amber-200">
                            Ujian Online CBT
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 hidden sm:block">Computer Based Test (CBT) System</p>
                </div>
            </div>

            {{-- Right User Controls --}}
            <div class="flex items-center gap-2 sm:gap-3">
                @if(auth()->check() && auth()->user()->role?->name === 'Siswa')
                    <div class="hidden md:flex items-center gap-2 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-[5px]">
                        <div class="w-6 h-6 rounded-full bg-amber-500 text-slate-950 text-[11px] font-black flex items-center justify-center shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                        </div>
                        <div class="text-left">
                            <div class="text-xs font-bold text-slate-900 leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-amber-700 font-bold">Siswa Terverifikasi</div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </header>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 w-full">
        @yield('content')
    </main>

    {{-- ======================== MINIMALIST EXAM FOOTER ======================== --}}
    <footer class="bg-white border-t border-slate-200 text-slate-500 py-6 px-4 text-center text-xs font-medium mt-auto">
        <div class="mx-auto max-w-7xl flex flex-col sm:flex-row items-center justify-between gap-3">
            <div>
                &copy; {{ date('Y') }} <strong class="text-slate-900">{{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}</strong>. Portal Ujian Online Terpadu.
            </div>
            <div class="text-[11px] text-slate-400">
                Sistem Ujian Berbasis Komputer & Handphone (CBT)
            </div>
        </div>
    </footer>

</body>
</html>
