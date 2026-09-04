<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        @media print {
            .no-print { display: none !important; }
            html, body { background: #fff !important; color: #000 !important; font-size: 11px !important; padding: 0 !important; margin: 0 !important; }
            .print-container { border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 p-4 sm:p-8">

    {{-- Action Bar (Non-printable) --}}
    <div class="max-w-7xl mx-auto mb-6 flex items-center justify-between no-print bg-white p-4 rounded-2xl shadow-md border border-slate-200">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.ppdb.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                ← Kembali ke Admin PPDB
            </a>
            <span class="text-xs text-slate-500 font-medium">
                Menampilkan {{ $pendaftar->count() }} data pendaftar
            </span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    {{-- Print Container --}}
    <div class="print-container max-w-7xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-slate-200">
        
        {{-- KOP SEKOLAH --}}
        <div class="border-b-2 border-slate-900 pb-3 mb-6 grid grid-cols-[80px_1fr_80px] items-center gap-3 w-full">
            <div class="w-20 h-20 flex items-center justify-center shrink-0">
                @if($setting && $setting->logo)
                    <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo, ['http://', 'https://']) ? $setting->logo : asset($setting->logo) }}" alt="Logo Utama" class="w-16 h-16 max-w-[64px] max-h-[64px] object-contain shrink-0">
                @endif
            </div>
            <div class="text-center min-w-0">
                <h1 class="text-2xl font-black text-slate-900 uppercase tracking-wide">
                    {{ $setting->website_name ?? 'PANITIA PPDB ONLINE' }}
                </h1>
                <p class="text-xs text-slate-600 font-medium">
                    {{ $setting->alamat ?? 'Jl. Pendidikan No. 45' }} | Telp/WA: {{ $setting->telepon ?? '-' }}
                </p>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">
                    Email: {{ $setting->email ?? '-' }} | Website: {{ url('/') }}
                </p>
            </div>
            <div class="w-20 h-20 flex items-center justify-center shrink-0">
                @if($setting && $setting->logo_instansi)
                    <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo_instansi, ['http://', 'https://']) ? $setting->logo_instansi : asset($setting->logo_instansi) }}" alt="Logo Instansi" class="w-16 h-16 max-w-[64px] max-h-[64px] object-contain shrink-0">
                @endif
            </div>
        </div>

        {{-- JUDUL LAPORAN --}}
        <div class="text-center mb-6">
            <h2 class="text-lg font-bold text-slate-900 uppercase underline tracking-wider">
                REKAPITULASI PENDAFTARAN PPDB ONLINE TAHUN {{ $setting->ppdb_tahun ?? date('Y') }}
            </h2>
            <p class="text-xs text-slate-500 font-medium mt-1">
                Filter Status: <span class="font-bold uppercase text-slate-800">{{ $statusFilter }}</span> | Tanggal Cetak: {{ date('d F Y - H:i') }} WIB
            </p>
        </div>

        {{-- TABEL DATA --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse border border-slate-300">
                <thead class="bg-slate-100 text-slate-800 font-bold uppercase border-b-2 border-slate-300">
                    <tr>
                        <th class="border border-slate-300 px-3 py-2 text-center w-8">No</th>
                        <th class="border border-slate-300 px-3 py-2">No. Reg</th>
                        <th class="border border-slate-300 px-3 py-2">NISN</th>
                        <th class="border border-slate-300 px-3 py-2">Nama Lengkap</th>
                        <th class="border border-slate-300 px-3 py-2 text-center">L/P</th>
                        <th class="border border-slate-300 px-3 py-2">Tempat, Tgl Lahir</th>
                        <th class="border border-slate-300 px-3 py-2">Sekolah Asal</th>
                        <th class="border border-slate-300 px-3 py-2">Jurusan</th>
                        <th class="border border-slate-300 px-3 py-2">Nama Wali</th>
                        <th class="border border-slate-300 px-3 py-2">No. HP / WA</th>
                        <th class="border border-slate-300 px-3 py-2">Alamat</th>
                        <th class="border border-slate-300 px-3 py-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pendaftar as $index => $item)
                    <tr class="hover:bg-slate-50">
                        <td class="border border-slate-300 px-3 py-2 text-center font-bold">{{ $index + 1 }}</td>
                        <td class="border border-slate-300 px-3 py-2 font-mono font-bold">{{ $item->no_pendaftaran }}</td>
                        <td class="border border-slate-300 px-3 py-2 font-mono">{{ $item->nisn ?? '-' }}</td>
                        <td class="border border-slate-300 px-3 py-2 font-bold text-slate-900">{{ $item->nama_lengkap }}</td>
                        <td class="border border-slate-300 px-3 py-2 text-center font-bold">{{ $item->jenis_kelamin }}</td>
                        <td class="border border-slate-300 px-3 py-2">{{ $item->tempat_lahir }}, {{ $item->tanggal_lahir }}</td>
                        <td class="border border-slate-300 px-3 py-2">{{ $item->sekolah_asal }}</td>
                        <td class="border border-slate-300 px-3 py-2 font-semibold">{{ $item->jurusan ?? '-' }}</td>
                        <td class="border border-slate-300 px-3 py-2">{{ $item->nama_orang_tua }}</td>
                        <td class="border border-slate-300 px-3 py-2 font-mono">{{ $item->no_hp }}</td>
                        <td class="border border-slate-300 px-3 py-2 text-[11px]">{{ $item->alamat }}</td>
                        <td class="border border-slate-300 px-3 py-2 text-center font-bold uppercase text-[10px]">
                            @if($item->status === 'diterima')
                                <span class="text-emerald-700">DITERIMA</span>
                            @elseif($item->status === 'ditolak')
                                <span class="text-red-700">DITOLAK</span>
                            @else
                                <span class="text-amber-700">PENDING</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="border border-slate-300 px-4 py-8 text-center text-slate-400 italic">
                            Tidak ada data pendaftar yang sesuai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- SUMMARY & SIGNATURE BLOCK --}}
        <div class="mt-8 grid grid-cols-2 gap-6 text-xs pt-4 border-t border-slate-200">
            <div>
                <p class="font-bold text-slate-800 mb-1">Ringkasan Pendaftaran:</p>
                <ul class="list-disc list-inside text-slate-600 space-y-0.5">
                    <li>Total Pendaftar Ditampilkan: <strong>{{ $pendaftar->count() }} Orang</strong></li>
                    <li>Laki-laki: <strong>{{ $pendaftar->where('jenis_kelamin', 'L')->count() }} Orang</strong></li>
                    <li>Perempuan: <strong>{{ $pendaftar->where('jenis_kelamin', 'P')->count() }} Orang</strong></li>
                </ul>
            </div>
            <div class="text-right">
                <p class="text-slate-600 mb-12">
                    {{ $setting->alamat ? explode(',', $setting->alamat)[0] : 'Kota' }}, {{ date('d F Y') }}<br>
                    <strong>Ketua Panitia PPDB</strong>
                </p>
                <p class="font-bold text-slate-900 underline uppercase">
                    (......................................................)
                </p>
                <p class="text-[10px] text-slate-400">NIP/NIPY. ........................................</p>
            </div>
        </div>

    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
