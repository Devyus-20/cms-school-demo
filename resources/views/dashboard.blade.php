<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') - {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}</title>
    @if(isset($websiteSetting->favicon) && $websiteSetting->favicon)
        <link rel="icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
    @endif
    <script>
        window.__INITIAL_STATS__ = {
            users: {{ $users ?? \App\Models\User::count() }},
            roles: {{ $roles ?? \App\Models\Role::count() }},
            permissions: {{ $permissions ?? \App\Models\Permission::count() }},
            posts: {{ $posts ?? \App\Models\Post::count() }},
            pending_ppdb: {{ $pending_ppdb ?? \App\Models\PpdbRegistration::where('status', 'pending')->count() }}
        };
        @auth
        window.__INITIAL_USER__ = {
            id: {{ auth()->user()->id_user }},
            name: @json(auth()->user()->name),
            username: @json(auth()->user()->username),
            email: @json(auth()->user()->email),
            role: @json(auth()->user()->role->name ?? 'Admin'),
            permissions: @json(auth()->user()->role ? auth()->user()->role->permissions->pluck('name')->toArray() : [])
        };
        @endauth
        window.__WEBSITE_SETTING__ = {
            name: @json($websiteSetting->website_name ?? 'MA Al Ikhlas'),
            description: @json($websiteSetting->website_description ?? 'CMS Sekolah Digital Terpadu'),
            logo: @json(isset($websiteSetting->logo) && $websiteSetting->logo ? asset($websiteSetting->logo) : asset('images/default-logo.png')),
            favicon: @json(isset($websiteSetting->favicon) && $websiteSetting->favicon ? asset($websiteSetting->favicon) : asset('images/default-logo.png')),
            alamat: @json($websiteSetting->alamat ?? ''),
            telepon: @json($websiteSetting->telepon ?? ''),
            email: @json($websiteSetting->email ?? ''),
            facebook: @json($websiteSetting->facebook ?? ''),
            instagram: @json($websiteSetting->instagram ?? ''),
            youtube: @json($websiteSetting->youtube ?? ''),
            linkedin: @json($websiteSetting->linkedin ?? ''),
            footer: @json($websiteSetting->footer ?? ''),
            ppdb_aktif: @json((bool)($websiteSetting->ppdb_aktif ?? false)),
            ppdb_tahun: @json($websiteSetting->ppdb_tahun ?? ''),
            ppdb_keterangan: @json($websiteSetting->ppdb_keterangan ?? ''),
            ppdb_link_daftar: @json($websiteSetting->ppdb_link_daftar ?? '')
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <div id="dashboard-app"></div>
</body>
</html>
