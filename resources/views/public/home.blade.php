<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}</title>
    @if(isset($websiteSetting->favicon) && $websiteSetting->favicon)
        <link rel="icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/default-logo.png') }}">
    @endif
    <script>
        @php
            $heroBgs = isset($websiteSetting) ? $websiteSetting->hero_bg_list : [];
            $heroBgsAssets = array_map(function($img) {
                return \Illuminate\Support\Str::startsWith($img, ['http://', 'https://']) ? $img : asset($img);
            }, $heroBgs);
        @endphp
        window.__WEBSITE_SETTING__ = {
            name: @json($websiteSetting->website_name ?? 'MA Al Ikhlas'),
            description: @json($websiteSetting->website_description ?? 'CMS Sekolah Digital Terpadu'),
            logo: @json(isset($websiteSetting->logo) && $websiteSetting->logo ? (\Illuminate\Support\Str::startsWith($websiteSetting->logo, ['http://', 'https://']) ? $websiteSetting->logo : asset($websiteSetting->logo)) : asset('images/default-logo.png')),
            favicon: @json(isset($websiteSetting->favicon) && $websiteSetting->favicon ? (\Illuminate\Support\Str::startsWith($websiteSetting->favicon, ['http://', 'https://']) ? $websiteSetting->favicon : asset($websiteSetting->favicon)) : asset('images/default-logo.png')),
            alamat: @json($websiteSetting->alamat ?? ''),
            telepon: @json($websiteSetting->telepon ?? ''),
            email: @json($websiteSetting->email ?? ''),
            facebook: @json($websiteSetting->facebook ?? ''),
            instagram: @json($websiteSetting->instagram ?? ''),
            youtube: @json($websiteSetting->youtube ?? ''),
            linkedin: @json($websiteSetting->linkedin ?? ''),
            footer: @json($websiteSetting->footer ?? ''),
            hero_bg: @json(isset($websiteSetting->hero_bg) && $websiteSetting->hero_bg ? (\Illuminate\Support\Str::startsWith($websiteSetting->hero_bg, ['http://', 'https://']) ? $websiteSetting->hero_bg : asset($websiteSetting->hero_bg)) : null),
            hero_bgs: @json($heroBgsAssets),
            hero_tagline: @json($websiteSetting->hero_tagline ?? ''),
            hero_title: @json($websiteSetting->hero_title ?? ''),
            hero_subtitle: @json($websiteSetting->hero_subtitle ?? ''),
            hero_btn1_text: @json($websiteSetting->hero_btn1_text ?? ''),
            hero_btn1_link: @json($websiteSetting->hero_btn1_link ?? ''),
            hero_btn2_text: @json($websiteSetting->hero_btn2_text ?? ''),
            hero_btn2_link: @json($websiteSetting->hero_btn2_link ?? ''),
            hero_btn3_text: @json($websiteSetting->hero_btn3_text ?? ''),
            hero_btn3_link: @json($websiteSetting->hero_btn3_link ?? ''),
            google_maps: @json($websiteSetting->google_maps ?? ''),
            ppdb_aktif: @json((bool)($websiteSetting->ppdb_aktif ?? false)),
            ppdb_tahun: @json($websiteSetting->ppdb_tahun ?? ''),
            ppdb_keterangan: @json($websiteSetting->ppdb_keterangan ?? ''),
            ppdb_link_daftar: @json($websiteSetting->ppdb_link_daftar ?? ''),
            info_pendaftaran_pembelajaran_judul: @json($websiteSetting->info_pendaftaran_pembelajaran_judul ?? ''),
            info_pendaftaran_pembelajaran_subjudul: @json($websiteSetting->info_pendaftaran_pembelajaran_subjudul ?? ''),
            info_pendaftaran_pembelajaran_konten: @json($websiteSetting->info_pendaftaran_pembelajaran_konten ?? ''),
            info_faq_list: @json($websiteSetting->info_faq_list ?? [])
        };
        window.__INITIAL_PAGES__ = @json(array_values($groupedPages ?? []));
        window.__INITIAL_ALL_PAGES__ = @json($allPagesMapped ?? []);
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="min-h-screen bg-[#f4f7f6] text-slate-800">
    <div id="app"></div>
</body>
</html>
