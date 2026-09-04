<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Presensi Kelas {{ $selectedKelas }} - {{ \Carbon\Carbon::create()->month($selectedBulan)->isoFormat('MMMM') }} {{ $selectedTahun }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        @media print {
            .no-print { display: none !important; }
            html, body {
                background: #fff !important;
                color: #000 !important;
                font-size: 10px !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .page-container {
                box-shadow: none !important;
                border: none !important;
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
<body class="bg-slate-100 font-sans text-slate-800 p-4 sm:p-6">

    @php
        $namaBulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulan = $namaBulanList[$selectedBulan] ?? 'Bulan ' . $selectedBulan;
    @endphp

    {{-- Action Bar (Non-printable) --}}
    <div class="max-w-[297mm] mx-auto mb-6 flex flex-wrap items-center justify-between no-print bg-white p-4 rounded-2xl shadow-md border border-slate-200 gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.presensi.index', ['kelas' => $selectedKelas]) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                ← Kembali ke Admin Presensi
            </a>
            <span class="text-xs text-slate-500 font-medium">
                Rekap Kelas {{ $selectedKelas }} ({{ $daftarSiswa->count() }} Siswa)
            </span>
        </div>

        {{-- Filter Bulan & Tahun Form --}}
        <form action="{{ route('admin.presensi.print') }}" method="GET" class="flex items-center gap-2">
            <input type="hidden" name="kelas" value="{{ $selectedKelas }}">
            
            <select name="bulan" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold bg-slate-50">
                @foreach($namaBulanList as $num => $name)
                    <option value="{{ $num }}" {{ $selectedBulan == $num ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>

            <select name="tahun" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold bg-slate-50">
                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                    <option value="{{ $y }}" {{ $selectedTahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <button onclick="window.print()" type="button" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2 ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </form>
    </div>

    {{-- Printable Page Container (Mengikuti ukuran A4 murni tanpa garis tepi dekoratif) --}}
    <div class="page-container max-w-[297mm] mx-auto bg-white p-6 rounded-xl shadow-sm">
        
        {{-- KOP SEKOLAH --}}
        <div class="border-b-2 border-slate-900 pb-3 mb-4 text-center flex items-center justify-between gap-4">
            <div class="w-16 h-16 flex items-center justify-center shrink-0">
                @if($setting && $setting->logo)
                    <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo, ['http://', 'https://']) ? $setting->logo : asset($setting->logo) }}" alt="Logo Utama" class="w-16 h-16 max-w-[64px] max-h-[64px] object-contain shrink-0">
                @endif
            </div>
            <div class="text-center flex-1">
                <h1 class="text-xl font-black text-slate-900 uppercase tracking-wide">
                    {{ $setting->website_name ?? 'LAPORAN PRESENSI SISWA' }}
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
        <div class="text-center mb-4">
            <h2 class="text-base font-bold text-slate-900 uppercase tracking-wide">
                REKAPITULASI PRESENSI KEHADIRAN SISWA
            </h2>
            <p class="text-xs font-semibold text-slate-700 mt-0.5">
                KELAS: <span class="text-blue-800 uppercase">{{ $selectedKelas }}</span> &nbsp;|&nbsp; PERIODE: <span class="text-blue-800 uppercase">{{ $namaBulan }} {{ $selectedTahun }}</span>
            </p>
        </div>

        {{-- TABEL PRESENSI --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[11px] border-collapse border border-slate-400">
                <thead class="bg-slate-100 text-slate-900 font-bold uppercase">
                    <tr>
                        <th class="border border-slate-400 px-1 py-1.5 text-center w-6" rowspan="2">No</th>
                        <th class="border border-slate-400 px-2 py-1.5 w-20" rowspan="2">NIS</th>
                        <th class="border border-slate-400 px-2 py-1.5" rowspan="2" style="min-width: 140px;">Nama Lengkap</th>
                        <th class="border border-slate-400 px-1 py-1.5 text-center w-7" rowspan="2">L/P</th>
                        <th class="border border-slate-400 text-center py-1" colspan="{{ $daysInMonth }}">Tanggal ({{ $namaBulan }})</th>
                        <th class="border border-slate-400 text-center py-1" colspan="5">Rekapitulasi</th>
                    </tr>
                    <tr>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            <th class="border border-slate-400 text-center w-5 py-0.5 font-mono text-[10px]">{{ $d }}</th>
                        @endfor
                        <th class="border border-slate-400 text-center w-6 bg-emerald-50 text-emerald-800 py-0.5">H</th>
                        <th class="border border-slate-400 text-center w-6 bg-amber-50 text-amber-800 py-0.5">S</th>
                        <th class="border border-slate-400 text-center w-6 bg-blue-50 text-blue-800 py-0.5">I</th>
                        <th class="border border-slate-400 text-center w-6 bg-red-50 text-red-800 py-0.5">A</th>
                        <th class="border border-slate-400 text-center w-8 bg-slate-200 py-0.5">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @forelse($daftarSiswa as $index => $siswa)
                    @php
                        $countH = 0;
                        $countS = 0;
                        $countI = 0;
                        $countA = 0;
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="border border-slate-300 px-1 py-1 text-center font-semibold text-slate-700">{{ $index + 1 }}</td>
                        <td class="border border-slate-300 px-2 py-1 font-mono text-slate-700">{{ $siswa->nis }}</td>
                        <td class="border border-slate-300 px-2 py-1 font-semibold text-slate-900 whitespace-nowrap">{{ $siswa->nama_lengkap }}</td>
                        <td class="border border-slate-300 px-1 py-1 text-center font-bold text-slate-600">{{ $siswa->jenis_kelamin }}</td>
                        
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $status = $presensiMap[$siswa->id_siswa][$d] ?? null;
                                $code = '-';
                                $classColor = 'text-slate-300';
                                if ($status === 'hadir') {
                                    $code = 'H';
                                    $classColor = 'text-emerald-700 font-bold';
                                    $countH++;
                                } elseif ($status === 'sakit') {
                                    $code = 'S';
                                    $classColor = 'text-amber-700 font-bold';
                                    $countS++;
                                } elseif ($status === 'izin') {
                                    $code = 'I';
                                    $classColor = 'text-blue-700 font-bold';
                                    $countI++;
                                } elseif ($status === 'alpa') {
                                    $code = 'A';
                                    $classColor = 'text-red-700 font-bold';
                                    $countA++;
                                }
                            @endphp
                            <td class="border border-slate-300 text-center py-1 {{ $classColor }} text-[10px]">{{ $code }}</td>
                        @endfor

                        @php
                            $totalEntri = $countH + $countS + $countI + $countA;
                            $persen = $totalEntri > 0 ? round(($countH / $totalEntri) * 100) : 0;
                        @endphp
                        <td class="border border-slate-300 text-center font-bold text-emerald-800 bg-emerald-50/50 py-1">{{ $countH }}</td>
                        <td class="border border-slate-300 text-center font-bold text-amber-800 bg-amber-50/50 py-1">{{ $countS }}</td>
                        <td class="border border-slate-300 text-center font-bold text-blue-800 bg-blue-50/50 py-1">{{ $countI }}</td>
                        <td class="border border-slate-300 text-center font-bold text-red-800 bg-red-50/50 py-1">{{ $countA }}</td>
                        <td class="border border-slate-300 text-center font-bold text-slate-900 bg-slate-100 py-1">{{ $persen }}%</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $daysInMonth + 9 }}" class="border border-slate-300 px-4 py-6 text-center text-slate-400 italic">
                            Belum ada data siswa di kelas {{ $selectedKelas }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- KETERANGAN & BLOCK TANDA TANGAN --}}
        <div class="mt-6 grid grid-cols-2 gap-6 text-[11px] pt-4 border-t border-slate-200">
            <div>
                <p class="font-bold text-slate-800 mb-1">Keterangan Status Kehadiran:</p>
                <div class="flex items-center gap-4 text-xs text-slate-700 font-medium">
                    <span><strong class="text-emerald-700">H</strong> = Hadir</span>
                    <span><strong class="text-amber-700">S</strong> = Sakit</span>
                    <span><strong class="text-blue-700">I</strong> = Izin</span>
                    <span><strong class="text-red-700">A</strong> = Alpa</span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-slate-700 mb-12">
                    {{ $setting->alamat ? explode(',', $setting->alamat)[0] : 'Sekolah' }}, {{ date('d') }} {{ $namaBulan }} {{ $selectedTahun }}<br>
                    <strong>Wali Kelas {{ $selectedKelas }}</strong>
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
