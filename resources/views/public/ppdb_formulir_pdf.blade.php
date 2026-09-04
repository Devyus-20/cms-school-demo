<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir PPDB - {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm 10mm 12mm;
        }
        @media print {
            .no-print { display: none !important; }
            html, body { background: white !important; padding: 0 !important; margin: 0 !important; }
            .print-container { box-shadow: none !important; border: none !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
            thead.print-header-repeat { display: table-header-group !important; }
            tr.section-row { page-break-inside: avoid !important; }
            .page-break-before { page-break-before: always !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 font-sans p-4 sm:p-8 antialiased min-h-screen">

    {{-- Action Bar (No Print) --}}
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <a href="{{ route('public.ppdb') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-emerald-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Halaman PPDB</span>
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-extrabold shadow-md inline-flex items-center gap-2 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H7a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak / Simpan sebagai PDF</span>
        </button>
    </div>

    {{-- Printable A4 Sheet Container --}}
    <div class="print-container max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-3xl border border-slate-300 shadow-lg">
        
        {{-- Master Table structure so Kop Header repeats automatically on every new printed page --}}
        <table class="w-full border-collapse">
            <thead class="print-header-repeat">
                <tr>
                    <th class="font-normal text-left pb-4">
                        {{-- KOP SURAT SEKOLAH (3 Cell Layout Presisi: Logo Kiri & Logo Kanan Sejajar Rata Bawah pada Garis Hitam) --}}
                        <table style="width: 100%; border-collapse: collapse; border-bottom: 2px solid #0f172a; margin-bottom: 8px;">
                            <tr>
                                {{-- CELL KIRI: Logo Utama (Posisi Kotak Merah Kiri) --}}
                                <td style="width: 90px; text-align: left; vertical-align: bottom; padding-bottom: 6px;">
                                    @if(isset($websiteSetting->logo) && $websiteSetting->logo)
                                        <img src="{{ \Illuminate\Support\Str::startsWith($websiteSetting->logo, ['http://', 'https://']) ? $websiteSetting->logo : asset($websiteSetting->logo) }}"
                                             alt="Logo Utama"
                                             style="width: 64px; height: 64px; max-width: 64px; max-height: 64px; object-fit: contain; display: block;">
                                    @else
                                        <div style="width: 60px; height: 60px; background-color: #065f46; color: #ffffff; border-radius: 12px; font-weight: 900; font-size: 24px; display: flex; align-items: center; justify-content: center;">
                                            {{ strtoupper(substr($websiteSetting->website_name ?? 'M', 0, 1)) }}
                                        </div>
                                    @endif
                                </td>

                                {{-- CELL TENGAH: Teks Identitas Sekolah (Tepat di Tengah Sumbu 50%) --}}
                                <td style="text-align: center; vertical-align: bottom; padding-bottom: 6px;">
                                    <h1 style="font-size: 22px; font-weight: 900; text-transform: uppercase; color: #0f172a; margin: 0; line-height: 1.2; letter-spacing: 0.5px;">
                                        {{ $websiteSetting->website_name ?? 'MA AL IKHLAS' }}
                                    </h1>
                                    <p style="font-size: 12px; font-weight: 700; color: #475569; margin: 2px 0 0 0;">
                                        {{ $websiteSetting->website_description ?? 'CMS Sekolah Digital Terpadu' }}
                                    </p>
                                    <p style="font-size: 11px; font-weight: 500; color: #64748b; margin: 4px 0 0 0;">
                                        {{ $websiteSetting->alamat ?? 'Jl. Pendidikan No. 45, Kota Digital' }} | Telp: {{ $websiteSetting->telepon ?? '0812-3456-7890' }} | Email: {{ $websiteSetting->email ?? 'admin@school.test' }}
                                    </p>
                                </td>

                                {{-- CELL KANAN: Logo Instansi / Kementerian / Yayasan (Posisi Kotak Merah Kanan) --}}
                                <td style="width: 90px; text-align: right; vertical-align: bottom; padding-bottom: 6px;">
                                    @if(isset($websiteSetting->logo_instansi) && $websiteSetting->logo_instansi)
                                        <img src="{{ \Illuminate\Support\Str::startsWith($websiteSetting->logo_instansi, ['http://', 'https://']) ? $websiteSetting->logo_instansi : asset($websiteSetting->logo_instansi) }}"
                                             alt="Logo Instansi"
                                             style="width: 64px; height: 64px; max-width: 64px; max-height: 64px; object-fit: contain; display: block; margin-left: auto;">
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="pt-2">
                        {{-- Judul Formulir --}}
                        <div class="text-center space-y-1 pb-4">
                            <h2 class="text-base sm:text-lg font-black uppercase text-slate-900 underline tracking-wide">
                                FORMULIR PENDAFTARAN PESERTA DIDIK BARU (PPDB)
                            </h2>
                            <p class="text-xs font-bold text-slate-600">
                                TAHUN AJARAN {{ $setting->ppdb_tahun ?? '2026/2027' }}
                            </p>
                        </div>

                        {{-- Isian Data --}}
                        <div class="space-y-6 text-xs text-slate-800">

                            {{-- 1. DATA PRIBADI --}}
                            <div class="space-y-2">
                                <div class="font-bold uppercase text-slate-900 bg-slate-100 p-2 border-l-4 border-emerald-700">
                                    A. DATA PRIBADI CALON SISWA
                                </div>
                                <table class="w-full border-collapse">
                                    <tbody>
                                        <tr class="border-b border-slate-200">
                                            <td class="py-2.5 font-semibold w-48">1. Nama Lengkap</td>
                                            <td class="py-2.5 w-4">:</td>
                                            <td class="py-2.5"></td>
                                        </tr>
                                        <tr class="border-b border-slate-200">
                                            <td class="py-2.5 font-semibold">2. NISN</td>
                                            <td class="py-2.5">:</td>
                                            <td class="py-2.5"></td>
                                        </tr>
                                        <tr class="border-b border-slate-200">
                                            <td class="py-2.5 font-semibold">3. Jenis Kelamin</td>
                                            <td class="py-2.5">:</td>
                                            <td class="py-2.5">[  ] Laki-laki &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [  ] Perempuan</td>
                                        </tr>
                                        <tr class="border-b border-slate-200">
                                            <td class="py-2.5 font-semibold">4. Tempat, Tanggal Lahir</td>
                                            <td class="py-2.5">:</td>
                                            <td class="py-2.5"></td>
                                        </tr>
                                        <tr class="border-b border-slate-200">
                                            <td class="py-2.5 font-semibold">5. Agama</td>
                                            <td class="py-2.5">:</td>
                                            <td class="py-2.5"></td>
                                        </tr>
                                        <tr class="border-b border-slate-200">
                                            <td class="py-2.5 font-semibold">6. Pilihan Jurusan</td>
                                            <td class="py-2.5">:</td>
                                            <td class="py-2.5">
                                                @php
                                                    $jList = isset($websiteSetting) ? $websiteSetting->jurusan_list : (isset($setting) ? $setting->jurusan_list : ['MIPA', 'IPS', 'Keagamaan']);
                                                @endphp
                                                @foreach($jList as $jItem)
                                                    [ &nbsp; ] {{ $jItem }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                @endforeach
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- 2. SEKOLAH ASAL & ORANG TUA --}}
                            <div class="space-y-2">
                                <div class="font-bold uppercase text-slate-900 bg-slate-100 p-2 border-l-4 border-emerald-700">
                                    B. SEKOLAH ASAL & DATA ORANG TUA / WALI
                                </div>
                                <table class="w-full border-collapse">
                                    <tbody>
                                        <tr class="border-b border-slate-200">
                                            <td class="py-2.5 font-semibold w-48">1. Sekolah Asal (SMP/MTs)</td>
                                            <td class="py-2.5 w-4">:</td>
                                            <td class="py-2.5"></td>
                                        </tr>
                                        <tr class="border-b border-slate-200">
                                            <td class="py-2.5 font-semibold">2. Nama Orang Tua / Wali</td>
                                            <td class="py-2.5">:</td>
                                            <td class="py-2.5"></td>
                                        </tr>
                                        <tr class="border-b border-slate-200">
                                            <td class="py-2.5 font-semibold">3. No. HP / WhatsApp Aktif</td>
                                            <td class="py-2.5">:</td>
                                            <td class="py-2.5"></td>
                                        </tr>
                                        <tr class="border-b border-slate-200">
                                            <td class="py-2.5 font-semibold">4. Email (Opsional)</td>
                                            <td class="py-2.5">:</td>
                                            <td class="py-2.5"></td>
                                        </tr>
                                        <tr class="border-b border-slate-200">
                                            <td class="py-2.5 font-semibold">5. Alamat Lengkap Rumah</td>
                                            <td class="py-2.5">:</td>
                                            <td class="py-2.5"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            @php
                                $pdfCustomFields = \App\Models\PpdbCustomField::activeOrdered()->get();
                            @endphp
                            @if($pdfCustomFields->count() > 0)
                                {{-- 3. DATA TAMBAHAN KHUSUS SEKOLAH --}}
                                <div class="space-y-2">
                                    <div class="font-bold uppercase text-slate-900 bg-slate-100 p-2 border-l-4 border-emerald-700">
                                        C. DATA TAMBAHAN KHUSUS SEKOLAH
                                    </div>
                                    <table class="w-full border-collapse">
                                        <tbody>
                                            @foreach($pdfCustomFields as $idx => $cf)
                                                <tr class="border-b border-slate-200">
                                                    <td class="py-2.5 font-semibold w-48">{{ $idx + 1 }}. {{ $cf->label }}</td>
                                                    <td class="py-2.5 w-4">:</td>
                                                    <td class="py-2.5">
                                                        @if(is_array($cf->options) && count($cf->options) > 0)
                                                            <span class="text-[11px] text-slate-500 font-normal">Pilihan: {{ implode(' / ', $cf->options) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            {{-- Tanda Tangan & Pas Foto Box --}}
                            <div class="pt-6 grid grid-cols-3 gap-6 items-center text-center text-xs">
                                {{-- Box Pas Foto --}}
                                <div class="border-2 border-dashed border-slate-400 h-32 w-24 mx-auto flex flex-col items-center justify-center text-[10px] text-slate-400 font-bold">
                                    <span>PAS FOTO</span>
                                    <span>3 x 4</span>
                                </div>

                                {{-- Tanda Tangan Ortu --}}
                                <div class="space-y-12">
                                    <div>
                                        <p class="text-slate-500 font-semibold">Orang Tua / Wali Siswa,</p>
                                    </div>
                                    <div>
                                        <p class="font-bold underline">( ................................................... )</p>
                                    </div>
                                </div>

                                {{-- Tanda Tangan Panitia --}}
                                <div class="space-y-12">
                                    <div>
                                        <p class="text-slate-500 font-semibold">Panitia PPDB Sekolah,</p>
                                    </div>
                                    <div>
                                        <p class="font-bold underline">( ................................................... )</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Footer Lembar --}}
                        <div class="border-t border-slate-200 pt-4 mt-6 text-[10px] text-slate-400">
                            <span>* Serahkan formulir yang telah diisi lengkap ke Sekretariat Panitia PPDB {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}.</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
</body>
</html>
