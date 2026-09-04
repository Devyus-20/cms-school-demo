<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Data_Siswa_{{ date('Ymd_His') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 landscape; margin: 10mm; }
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
    <div class="max-w-6xl mx-auto mb-6 flex items-center justify-between no-print bg-white p-4 rounded-2xl shadow-md border border-slate-200">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.reports.index', ['type' => 'siswa']) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                ← Kembali ke Pusat Laporan
            </a>
            <span class="text-xs text-slate-500 font-medium">Total: {{ $siswaList->count() }} Siswa</span>
        </div>
        <button onclick="window.print()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak / Simpan PDF
        </button>
    </div>

    {{-- Printable Sheet --}}
    <div class="print-sheet max-w-6xl mx-auto bg-white p-8 rounded-2xl shadow-sm space-y-6">
        
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
                <p class="text-xs text-slate-700 font-bold uppercase tracking-widest mt-1">
                    BUKU INDUK / DAFTAR REKAPITULASI DATA SISWA
                </p>
            </div>
            <div class="w-16 h-16 flex items-center justify-center shrink-0">
                @if($setting && $setting->logo_instansi)
                    <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo_instansi, ['http://', 'https://']) ? $setting->logo_instansi : asset($setting->logo_instansi) }}" alt="Logo Instansi" class="w-16 h-16 max-w-[64px] max-h-[64px] object-contain shrink-0">
                @endif
            </div>
        </div>

        {{-- Filter Info --}}
        <div class="grid grid-cols-3 gap-2 text-xs border border-slate-200 p-3 rounded-lg bg-slate-50">
            @foreach($filterDesc as $key => $val)
                <div><span class="font-bold text-slate-500">{{ $key }}:</span> <span class="font-semibold text-slate-800">{{ $val }}</span></div>
            @endforeach
            <div><span class="font-bold text-slate-500">Tanggal Cetak:</span> <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span></div>
        </div>

        {{-- Table --}}
        <table class="w-full text-left text-xs border-collapse border border-slate-300">
            <thead>
                <tr class="bg-slate-100 text-slate-800 font-bold text-center">
                    <th class="border border-slate-300 p-2 w-10">No</th>
                    <th class="border border-slate-300 p-2">NIS</th>
                    <th class="border border-slate-300 p-2">NISN</th>
                    <th class="border border-slate-300 p-2 text-left">Nama Lengkap</th>
                    <th class="border border-slate-300 p-2 w-12">L/P</th>
                    <th class="border border-slate-300 p-2">Kelas</th>
                    <th class="border border-slate-300 p-2">Angkatan</th>
                    <th class="border border-slate-300 p-2">Status</th>
                    <th class="border border-slate-300 p-2 text-left">Kontak / Email</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaList as $idx => $s)
                    <tr class="border-b border-slate-200">
                        <td class="border border-slate-300 p-2 text-center">{{ $idx + 1 }}</td>
                        <td class="border border-slate-300 p-2 text-center font-mono font-bold">{{ $s->nis }}</td>
                        <td class="border border-slate-300 p-2 text-center font-mono">{{ $s->nisn ?? '-' }}</td>
                        <td class="border border-slate-300 p-2 font-bold">{{ $s->nama_lengkap }}</td>
                        <td class="border border-slate-300 p-2 text-center">{{ $s->jenis_kelamin }}</td>
                        <td class="border border-slate-300 p-2 text-center font-bold">{{ $s->kelas }}</td>
                        <td class="border border-slate-300 p-2 text-center">{{ $s->tahun_masuk }}</td>
                        <td class="border border-slate-300 p-2 text-center uppercase text-[10px] font-bold">{{ str_replace('_', ' ', $s->status) }}</td>
                        <td class="border border-slate-300 p-2">
                            <div>{{ $s->email }}</div>
                            <div class="text-[10px] text-slate-500">{{ $s->telepon ?? '-' }}</div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="p-4 text-center text-slate-500">Tidak ada data siswa.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- Signature --}}
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
                <p class="font-bold">Petugas / Operator Data</p>
                <div class="h-16"></div>
                <p class="font-bold underline">{{ auth()->user()->name ?? auth()->user()->username }}</p>
                <p class="text-slate-500">NIP / ID: {{ auth()->id() }}</p>
            </div>
        </div>
    </div>
</body>
</html>
