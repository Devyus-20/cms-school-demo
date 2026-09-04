<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap_Presensi_Kelas_{{ \Illuminate\Support\Str::slug($selectedKelas) }}_{{ $tahun }}_{{ $bulan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        @media print {
            .no-print { display: none !important; }
            html, body { background: #fff !important; color: #000 !important; font-size: 10pt !important; padding: 0 !important; margin: 0 !important; }
            .print-sheet { border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; }
            table { page-break-inside: auto; width: 100%; border-collapse: collapse; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 p-4 sm:p-8">

    {{-- Action Bar --}}
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print bg-white p-4 rounded-2xl shadow-md border border-slate-200">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.reports.index', ['type' => 'presensi', 'kelas' => $selectedKelas, 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                ← Kembali ke Pusat Laporan
            </a>
            <span class="text-xs text-slate-500 font-medium">Periode: {{ $namaBulan[$bulan] ?? $bulan }} {{ $tahun }}</span>
        </div>
        <button onclick="window.print()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak / Simpan PDF
        </button>
    </div>

    {{-- Printable Sheet --}}
    <div class="print-sheet max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-sm space-y-6">
        
        {{-- KOP SEKOLAH --}}
        <div class="border-b-2 border-slate-900 pb-3 text-center flex items-center justify-between gap-4">
            <div class="w-16 h-16 flex items-center justify-center shrink-0">
                @if($setting && $setting->logo)
                    <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo, ['http://', 'https://']) ? $setting->logo : asset($setting->logo) }}" alt="Logo Utama" class="w-16 h-16 max-w-[64px] max-h-[64px] object-contain shrink-0">
                @endif
            </div>
            <div class="text-center flex-1">
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-wide">
                    {{ $setting->website_name ?? 'CMS SCHOOL' }}
                </h1>
                <p class="text-xs text-slate-600 font-medium">
                    {{ $setting->alamat ?? 'Jl. Pendidikan No. 45' }} | Telp: {{ $setting->telepon ?? '-' }}
                </p>
                <p class="text-[11px] text-slate-500 font-medium">
                    Email: {{ $setting->email ?? '-' }} | Website: {{ url('/') }}
                </p>
                <p class="text-xs text-slate-700 font-bold uppercase tracking-wider mt-1">
                    LAPORAN REKAPITULASI PRESENSI & KEHADIRAN SISWA
                </p>
            </div>
            <div class="w-16 h-16 flex items-center justify-center shrink-0">
                @if($setting && $setting->logo_instansi)
                    <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo_instansi, ['http://', 'https://']) ? $setting->logo_instansi : asset($setting->logo_instansi) }}" alt="Logo Instansi" class="w-16 h-16 max-w-[64px] max-h-[64px] object-contain shrink-0">
                @endif
            </div>
        </div>

        {{-- Meta info --}}
        <div class="grid grid-cols-3 gap-2 text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 p-2.5 rounded-lg">
            <div>Kelas: <span class="font-bold text-slate-900">{{ $selectedKelas }}</span></div>
            <div>Bulan / Periode: <span class="font-bold text-slate-900">{{ $namaBulan[$bulan] ?? $bulan }} {{ $tahun }}</span></div>
            <div>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
        </div>

        {{-- Table --}}
        <table class="w-full text-left text-xs border-collapse border border-slate-300">
            <thead>
                <tr class="bg-slate-100 text-slate-900 font-bold text-center">
                    <th class="border border-slate-300 p-2 w-12">No</th>
                    <th class="border border-slate-300 p-2 w-28">NIS</th>
                    <th class="border border-slate-300 p-2 text-left">Nama Siswa</th>
                    <th class="border border-slate-300 p-2 w-16">Hadir</th>
                    <th class="border border-slate-300 p-2 w-16">Izin</th>
                    <th class="border border-slate-300 p-2 w-16">Sakit</th>
                    <th class="border border-slate-300 p-2 w-16">Alpa</th>
                    <th class="border border-slate-300 p-2 w-20">Total Sesi</th>
                    <th class="border border-slate-300 p-2 w-24">% Hadir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapPresensi as $idx => $item)
                    <tr class="border-b border-slate-200 text-center">
                        <td class="border border-slate-300 p-2 font-bold">{{ $idx + 1 }}</td>
                        <td class="border border-slate-300 p-2 font-mono font-semibold">{{ $item['siswa']->nis }}</td>
                        <td class="border border-slate-300 p-2 text-left font-bold">{{ $item['siswa']->nama_lengkap }}</td>
                        <td class="border border-slate-300 p-2">{{ $item['hadir'] }}</td>
                        <td class="border border-slate-300 p-2">{{ $item['izin'] }}</td>
                        <td class="border border-slate-300 p-2">{{ $item['sakit'] }}</td>
                        <td class="border border-slate-300 p-2">{{ $item['alpa'] }}</td>
                        <td class="border border-slate-300 p-2 font-bold">{{ $item['total'] }}</td>
                        <td class="border border-slate-300 p-2 font-black text-slate-900 bg-slate-50">{{ $item['persen'] }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="p-4 text-center text-slate-500">Tidak ada data presensi.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- Signatures --}}
        <div class="pt-8 flex justify-between items-start text-xs">
            <div class="text-center w-64">
                <p>Mengetahui,</p>
                <p class="font-bold">Kepala Sekolah</p>
                <div class="h-16"></div>
                <p class="font-bold underline">_________________________</p>
                <p class="text-slate-500">NIP. -</p>
            </div>
            <div class="text-center w-64">
                <p>{{ $setting->kota ?? 'Kota Sekolah' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p class="font-bold">Wali Kelas {{ $selectedKelas }}</p>
                <div class="h-16"></div>
                <p class="font-bold underline">_________________________</p>
                <p class="text-slate-500">NIP. -</p>
            </div>
        </div>
    </div>
</body>
</html>
