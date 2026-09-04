<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap_Akademik_Kelas_{{ \Illuminate\Support\Str::slug($selectedKelas) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        @media print {
            .no-print { display: none !important; }
            html, body {
                background: #fff !important;
                color: #000 !important;
                font-size: 11pt !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-sheet {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 p-4 sm:p-8">

    {{-- Action Bar (Non-printable) --}}
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print bg-white p-4 rounded-2xl shadow-md border border-slate-200">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.rekap.index', ['kelas' => $selectedKelas]) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                ← Kembali ke Rekap Akademik
            </a>
            <span class="text-xs text-slate-500 font-medium">
                Rekap Kelas {{ $selectedKelas }} ({{ $rankedData->count() }} Siswa)
            </span>
        </div>

        <div class="flex items-center gap-2">
            <form action="{{ route('admin.rekap.print') }}" method="GET" class="flex items-center gap-2">
                <select name="kelas" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold bg-slate-50">
                    @foreach($kelases as $k)
                        <option value="{{ $k }}" {{ $selectedKelas == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
            </form>

            <button onclick="window.print()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    {{-- Printable Sheet (Murni A4 tanpa pola garis tepi dekoratif) --}}
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
                    {{ $setting->website_name ?? 'MA AL IKHLAS' }}
                </h1>
                <p class="text-xs text-slate-600 font-medium">
                    {{ $setting->alamat ?? 'Jl. Pendidikan No. 45' }} | Telp: {{ $setting->telepon ?? '-' }}
                </p>
                <p class="text-[11px] text-slate-500 font-medium">
                    Email: {{ $setting->email ?? '-' }} | Website: {{ url('/') }}
                </p>
            </div>
            <div class="w-16 h-16 flex items-center justify-center shrink-0">
                @if($setting && $setting->logo_instansi)
                    <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo_instansi, ['http://', 'https://']) ? $setting->logo_instansi : asset($setting->logo_instansi) }}" alt="Logo Instansi" class="w-16 h-16 max-w-[64px] max-h-[64px] object-contain shrink-0">
                @endif
            </div>
        </div>

        {{-- JUDUL LAPORAN --}}
        <div class="text-center space-y-1">
            <h2 class="text-base font-black text-slate-900 uppercase underline tracking-wide">
                LAPORAN REKAPITULASI AKADEMIK & PERANKINGAN SISWA
            </h2>
            <p class="text-xs font-bold text-slate-700">
                KELAS: <span class="text-emerald-800 uppercase">{{ $selectedKelas }}</span> &nbsp;|&nbsp; TANGGAL CETAK: <span>{{ date('d F Y') }}</span>
            </p>
        </div>

        {{-- TABEL REKAP NILAI --}}
        <div class="overflow-x-auto pt-2">
            <table class="w-full text-left text-xs border-collapse border border-slate-400">
                <thead class="bg-slate-100 text-slate-900 font-bold uppercase">
                    <tr>
                        <th class="border border-slate-400 px-2 py-2 text-center w-10">Rank</th>
                        <th class="border border-slate-400 px-2.5 py-2 w-20">NIS</th>
                        <th class="border border-slate-400 px-3 py-2">Nama Lengkap Siswa</th>
                        <th class="border border-slate-400 px-2 py-2 text-center w-10">L/P</th>
                        <th class="border border-slate-400 px-2.5 py-2 text-center w-20">Tugas</th>
                        <th class="border border-slate-400 px-2.5 py-2 text-center w-20">UH</th>
                        <th class="border border-slate-400 px-2.5 py-2 text-center w-20">UTS</th>
                        <th class="border border-slate-400 px-2.5 py-2 text-center w-20">UAS</th>
                        <th class="border border-slate-400 px-3 py-2 text-center w-24 bg-slate-200">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @forelse($rankedData as $item)
                        @php
                            $siswa = $item['siswa'];
                            $rank = $item['ranking'];
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="border border-slate-300 px-2 py-2 text-center font-black text-slate-900">
                                #{{ $rank }}
                            </td>
                            <td class="border border-slate-300 px-2.5 py-2 font-mono text-slate-700">{{ $siswa->nis }}</td>
                            <td class="border border-slate-300 px-3 py-2 font-bold text-slate-900">{{ $siswa->nama_lengkap }}</td>
                            <td class="border border-slate-300 px-2 py-2 text-center font-semibold text-slate-600">{{ $siswa->jenis_kelamin }}</td>
                            <td class="border border-slate-300 px-2.5 py-2 text-center font-semibold text-slate-800">{{ number_format($item['nilai_tugas'], 1) }}</td>
                            <td class="border border-slate-300 px-2.5 py-2 text-center font-semibold text-slate-800">{{ number_format($item['nilai_uh'], 1) }}</td>
                            <td class="border border-slate-300 px-2.5 py-2 text-center font-semibold text-slate-800">{{ number_format($item['nilai_uts'], 1) }}</td>
                            <td class="border border-slate-300 px-2.5 py-2 text-center font-semibold text-slate-800">{{ number_format($item['nilai_uas'], 1) }}</td>
                            <td class="border border-slate-300 px-3 py-2 text-center font-black text-emerald-800 bg-emerald-50/50 text-sm">
                                {{ number_format($item['nilai_akhir'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="border border-slate-300 px-4 py-6 text-center text-slate-400 italic">
                                Tidak ada data rekapitulasi nilai untuk kelas {{ $selectedKelas }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- CATATAN RUMUS & LEMBAR PENGESAHAN --}}
        <div class="pt-4 grid grid-cols-2 gap-6 text-xs border-t border-slate-200">
            <div>
                <p class="font-bold text-slate-800 mb-1">Catatan Perhitungan:</p>
                <p class="text-[11px] text-slate-600 font-mono">
                    Nilai Akhir = (Nilai Tugas + UH + UTS + UAS) / 4
                </p>
                <p class="text-[10px] text-slate-400 mt-1">
                    * Perankingan diurutkan secara otomatis berdasarkan Nilai Akhir tertinggi.
                </p>
            </div>
            <div class="text-right space-y-12">
                <p class="text-slate-700">
                    {{ $setting->alamat ? explode(',', $setting->alamat)[0] : 'Sekolah' }}, {{ date('d F Y') }}<br>
                    <strong>Wali Kelas {{ $selectedKelas }}</strong>
                </p>
                <div>
                    <p class="font-bold text-slate-900 underline uppercase">(......................................................)</p>
                    <p class="text-[10px] text-slate-400">NIP/NIPY. ........................................</p>
                </div>
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
