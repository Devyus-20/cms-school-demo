<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        try {
            $setting = isset($websiteSetting) ? $websiteSetting : \App\Models\Setting::first();
        } catch (\Throwable $e) {
            $setting = null;
        }
        $siteName = $setting->website_name ?? 'CMS School';
        $logo = $setting->logo ?? null;
        $favicon = $setting->favicon ?? null;
    @endphp
    <title>@yield('title', 'Terjadi Kesalahan') - {{ $siteName }}</title>
    
    @if($favicon)
        <link rel="icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($favicon, ['http://', 'https://']) ? $favicon : asset($favicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($favicon, ['http://', 'https://']) ? $favicon : asset($favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
    @endif

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Tailwind CSS CDN for guaranteed styling even on server/asset errors --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between selection:bg-emerald-500 selection:text-white relative overflow-x-hidden">
    
    {{-- Ambient Background Glow --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>
    </div>

    {{-- Header --}}
    <header class="relative z-10 w-full border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                @if($logo)
                    <img src="{{ \Illuminate\Support\Str::startsWith($logo, ['http://', 'https://']) ? $logo : asset($logo) }}" 
                         alt="{{ $siteName }}" 
                         class="w-9 h-9 sm:w-10 sm:h-10 object-contain rounded-lg bg-white/10 p-1 group-hover:scale-105 transition-transform">
                @else
                    <img src="{{ asset('images/default-logo.png') }}" 
                         alt="{{ $siteName }}" 
                         class="w-9 h-9 sm:w-10 sm:h-10 object-contain rounded-lg bg-white/10 p-1 group-hover:scale-105 transition-transform">
                @endif
                <div>
                    <span class="font-bold text-white text-sm sm:text-base tracking-wide block leading-tight group-hover:text-emerald-400 transition-colors">
                        {{ $siteName }}
                    </span>
                    <span class="text-[10px] sm:text-xs text-slate-400 font-medium">Sistem Informasi Sekolah</span>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ url('/') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                    Beranda
                </a>
                @if(Route::has('login'))
                    <a href="{{ route('login') }}" class="px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-bold bg-emerald-500 hover:bg-emerald-400 text-slate-950 shadow-sm transition-colors">
                        Masuk Portal
                    </a>
                @endif
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="relative z-10 flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="max-w-2xl w-full text-center space-y-6 sm:space-y-8 my-8">
            
            {{-- Error Visual / Icon Badge --}}
            <div class="inline-flex flex-col items-center justify-center">
                <div class="relative">
                    <div class="text-7xl sm:text-9xl font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-r @yield('gradient_class', 'from-emerald-400 via-teal-300 to-cyan-400') select-none opacity-90">
                        @yield('code', 'Error')
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="p-3 sm:p-4 rounded-2xl bg-slate-800/90 border border-slate-700 shadow-2xl backdrop-blur-md">
                            @yield('icon')
                        </div>
                    </div>
                </div>
            </div>

            {{-- Text Heading & Description --}}
            <div class="space-y-3">
                <h1 class="text-xl sm:text-3xl font-extrabold text-white tracking-tight">
                    @yield('heading', 'Terjadi Masalah')
                </h1>
                <p class="text-xs sm:text-base text-slate-300 max-w-lg mx-auto leading-relaxed">
                    @yield('message', 'Halaman atau layanan yang Anda tuju sedang mengalami kendala. Silakan coba beberapa saat lagi.')
                </p>
                @if(trim($__env->yieldContent('extra_details')))
                    <div class="mt-4 p-3 bg-slate-800/60 border border-slate-700/60 rounded-xl text-left font-mono text-xs text-slate-400 max-w-lg mx-auto overflow-x-auto">
                        @yield('extra_details')
                    </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                <a href="{{ url('/') }}" 
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs sm:text-sm shadow-lg shadow-emerald-500/20 transition-all hover:scale-[1.02]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Kembali ke Beranda
                </a>

                <button onclick="window.history.back()" 
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white font-bold text-xs sm:text-sm border border-slate-700 shadow-md transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Halaman Sebelumnya
                </button>

                @if(Route::has('admin.dashboard'))
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white font-semibold text-xs sm:text-sm border border-slate-700/80 transition-all">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Dashboard Admin
                    </a>
                @endif
            </div>

            {{-- Quick Links / Help Section --}}
            <div class="pt-6 border-t border-slate-800/80 text-xs text-slate-400 flex flex-wrap items-center justify-center gap-4">
                <span>Butuh bantuan?</span>
                @if($setting && $setting->email)
                    <a href="mailto:{{ $setting->email }}" class="text-emerald-400 hover:underline flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $setting->email }}
                    </a>
                @endif
                @if($setting && $setting->telepon)
                    <span class="text-slate-600">•</span>
                    <a href="tel:{{ $setting->telepon }}" class="text-emerald-400 hover:underline flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $setting->telepon }}
                    </a>
                @endif
            </div>

        </div>
    </main>

    {{-- Footer --}}
    <footer class="relative z-10 w-full border-t border-slate-800/80 bg-slate-950/60 py-4 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} {{ $siteName }}. Seluruh hak cipta dilindungi.</p>
    </footer>

</body>
</html>
