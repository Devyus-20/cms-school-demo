<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal Login Siswa - {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}</title>
    @if(isset($websiteSetting->favicon) && $websiteSetting->favicon)
        <link rel="icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="bg-slate-950 text-slate-800 antialiased font-sans">
    <script>
        window.websiteSetting = {!! json_encode($websiteSetting) !!};
    </script>
    <div id="login-siswa-app"></div>
</body>
</html>
