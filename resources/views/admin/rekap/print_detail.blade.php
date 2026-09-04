<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transkrip_Detail_Nilai_{{ \Illuminate\Support\Str::slug($siswa->nama_lengkap) }}_{{ $siswa->nis }}</title>
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

    {{-- Screen Floating Print Button Bar (Non-Printable) --}}
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print bg-white p-4 rounded-2xl shadow-md border border-slate-200">
        <div class="flex items-center space-x-3">
            <button onclick="window.close()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                ← Tutup / Kembali
            </button>
            <span class="text-xs text-slate-500 font-medium">
                Transkrip Nilai: <strong class="text-slate-800">{{ $siswa->nama_lengkap }}</strong> ({{ $siswa->kelas }})
            </span>
        </div>
        <button onclick="window.print()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak / Simpan PDF
        </button>
    </div>

    {{-- Printable Sheet (Tanpa Garis Tepi Dekorasional Sesuai Standar Cetak Rekap) --}}
    <div class="print-sheet max-w-4xl mx-auto bg-white p-8 sm:p-10 rounded-2xl shadow-sm space-y-6">
        
        {{-- KOP SEKOLAH --}}
        <table style="width: 100%; border-collapse: collapse; border-bottom: 2px solid #0f172a; margin-bottom: 12px;">
            <tr>
                <td style="width: 90px; text-align: left; vertical-align: bottom; padding-bottom: 6px;">
                    @if($setting && $setting->logo)
                        <img src="{{ Str::startsWith($setting->logo, ['http://', 'https://']) ? $setting->logo : asset($setting->logo) }}" alt="Logo Utama" style="width: 64px; height: 64px; max-width: 64px; max-height: 64px; object-fit: contain; display: block;">
                    @endif
                </td>
                <td style="text-align: center; vertical-align: bottom; padding-bottom: 6px;">
                    <h1 style="font-size: 22px; font-weight: 900; text-transform: uppercase; color: #0f172a; margin: 0; line-height: 1.2;">
                        {{ $setting->website_name ?? 'MA AL IKHLAS' }}
                    </h1>
                    <p style="font-size: 12px; font-weight: 600; color: #475569; margin: 2px 0 0 0;">
                        {{ $setting->alamat ?? 'Jl. Pendidikan No. 45' }} | Telp: {{ $setting->telepon ?? '-' }}
                    </p>
                    <p style="font-size: 11px; font-weight: 500; color: #64748b; margin: 2px 0 0 0;">
                        Email: {{ $setting->email ?? '-' }} | Website: {{ url('/') }}
                    </p>
                </td>
                <td style="width: 90px; text-align: right; vertical-align: bottom; padding-bottom: 6px;">
                    @if($setting && $setting->logo_instansi)
                        <img src="{{ Str::startsWith($setting->logo_instansi, ['http://', 'https://']) ? $setting->logo_instansi : asset($setting->logo_instansi) }}" alt="Logo Instansi/Kementerian/Yayasan" style="width: 64px; height: 64px; max-width: 64px; max-height: 64px; object-fit: contain; display: block; margin-left: auto;">
                    @endif
                </td>
            </tr>
        </table>

        {{-- JUDUL LAPORAN --}}
        <div class="text-center space-y-1">
            <h2 class="text-base font-black text-slate-900 uppercase underline tracking-wide">
                TRANSKRIP & RINCIAN HASIL EVALUASI AKADEMIK SISWA
            </h2>
            <p class="text-xs font-bold text-slate-700">
                TAHUN AJARAN {{ $setting?->ppdb_tahun ?? date('Y') . '/' . (date('Y')+1) }} &nbsp;|&nbsp; TANGGAL CETAK: <span>{{ date('d F Y') }}</span>
            </p>
        </div>

        {{-- IDENTITAS SISWA --}}
        <div class="grid grid-cols-2 gap-4 text-xs font-sans p-4 rounded-xl border border-slate-300 bg-slate-50/50">
            <div class="space-y-1.5">
                <div class="flex"><span class="w-32 text-slate-600">Nama Siswa</span><span class="font-bold">: {{ $siswa->nama_lengkap }}</span></div>
                <div class="flex"><span class="w-32 text-slate-600">NIS / NISN</span><span class="font-bold">: {{ $siswa->nis }} {{ $siswa->nisn ? '/ ' . $siswa->nisn : '' }}</span></div>
                <div class="flex"><span class="w-32 text-slate-600">Jenis Kelamin</span><span>: {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
            </div>
            <div class="space-y-1.5">
                <div class="flex"><span class="w-32 text-slate-600">Kelas / Tingkat</span><span class="font-bold">: {{ $siswa->kelas }}</span></div>
                <div class="flex"><span class="w-32 text-slate-600">Status Akademik</span><span class="font-bold text-emerald-800">: AKTIF</span></div>
                <div class="flex"><span class="w-32 text-slate-600">Tahun Masuk</span><span>: {{ $siswa->tahun_masuk ?? date('Y') }}</span></div>
            </div>
        </div>

        {{-- RINGKASAN REKAPITULASI & PERANKINGAN --}}
        <div class="space-y-2">
            <h3 class="text-xs font-bold uppercase text-slate-800 tracking-wider">
                1. RINGKASAN AKUMULASI EVALUASI & PERANKINGAN KELAS
            </h3>
            <table class="w-full text-xs text-left border-collapse border border-slate-400">
                <thead class="bg-slate-100 text-slate-900 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="border border-slate-400 p-2 text-center">Rata Tugas</th>
                        <th class="border border-slate-400 p-2 text-center">Ulangan Harian</th>
                        <th class="border border-slate-400 p-2 text-center">UTS</th>
                        <th class="border border-slate-400 p-2 text-center">UAS</th>
                        <th class="border border-slate-400 p-2 text-center bg-slate-200">Nilai Akhir Kumulatif</th>
                        <th class="border border-slate-400 p-2 text-center">Peringkat Kelas</th>
                        <th class="border border-slate-400 p-2 text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300 text-center font-bold">
                    <tr>
                        <td class="border border-slate-300 p-2 text-slate-800">{{ number_format($nilaiTugas, 1) }}</td>
                        <td class="border border-slate-300 p-2 text-slate-800">{{ number_format($nilaiUH, 1) }}</td>
                        <td class="border border-slate-300 p-2 text-slate-800">{{ number_format($nilaiUTS, 1) }}</td>
                        <td class="border border-slate-300 p-2 text-slate-800">{{ number_format($nilaiUAS, 1) }}</td>
                        <td class="border border-slate-300 p-2 bg-emerald-50 text-emerald-900 text-sm font-black">{{ number_format($nilaiAkhir, 2) }}</td>
                        <td class="border border-slate-300 p-2 text-slate-900">#{{ $ranking }} / {{ $totalSiswa }} Siswa</td>
                        <td class="border border-slate-300 p-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-blue-100 text-blue-900">
                                {{ $huruf }} ({{ $predikat }})
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- RINCIAN PENILAIAN TUGAS SEKOLAH --}}
        <div class="space-y-2">
            <h3 class="text-xs font-bold uppercase text-slate-800 tracking-wider">
                2. RINCIAN EVALUASI TUGAS SEKOLAH
            </h3>
            <table class="w-full text-xs text-left border-collapse border border-slate-400">
                <thead class="bg-slate-100 text-slate-900 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="border border-slate-400 p-2 w-8 text-center">No</th>
                        <th class="border border-slate-400 p-2">Mata Pelajaran</th>
                        <th class="border border-slate-400 p-2">Judul Tugas</th>
                        <th class="border border-slate-400 p-2 text-center">Tgl Kumpul</th>
                        <th class="border border-slate-400 p-2 text-center">Status</th>
                        <th class="border border-slate-400 p-2 text-center">Nilai (0-100)</th>
                        <th class="border border-slate-400 p-2">Catatan Evaluasi Guru</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @forelse($pengumpulanTugas as $idx => $pt)
                    <tr>
                        <td class="border border-slate-300 p-2 text-center font-mono">{{ $idx + 1 }}</td>
                        <td class="border border-slate-300 p-2 font-bold text-slate-800">{{ $pt->tugas?->mata_pelajaran ?? '-' }}</td>
                        <td class="border border-slate-300 p-2 font-semibold">{{ $pt->tugas?->judul ?? '-' }}</td>
                        <td class="border border-slate-300 p-2 text-center font-mono text-slate-600">{{ $pt->tanggal_kumpul ? $pt->tanggal_kumpul->format('d/m/Y') : '-' }}</td>
                        <td class="border border-slate-300 p-2 text-center">
                            <span class="text-[10px] font-bold text-emerald-700">Dikumpulkan</span>
                        </td>
                        <td class="border border-slate-300 p-2 text-center font-bold text-slate-900 font-mono">{{ !is_null($pt->nilai) ? $pt->nilai : '-' }}</td>
                        <td class="border border-slate-300 p-2 text-slate-600 italic text-[11px]">{{ $pt->catatan_guru ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="border border-slate-300 p-4 text-center text-slate-400 italic">Belum ada rincian tugas yang dikumpulkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- RINCIAN UJIAN ONLINE CBT --}}
        <div class="space-y-2">
            <h3 class="text-xs font-bold uppercase text-slate-800 tracking-wider">
                3. RINCIAN PERCOBAAN UJIAN ONLINE (CBT)
            </h3>
            <table class="w-full text-xs text-left border-collapse border border-slate-400">
                <thead class="bg-slate-100 text-slate-900 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="border border-slate-400 p-2 w-8 text-center">No</th>
                        <th class="border border-slate-400 p-2">Mata Pelajaran</th>
                        <th class="border border-slate-400 p-2">Judul Ujian CBT</th>
                        <th class="border border-slate-400 p-2 text-center">Tipe Modul</th>
                        <th class="border border-slate-400 p-2 text-center">Tgl Pengerjaan</th>
                        <th class="border border-slate-400 p-2 text-center bg-slate-200">Skor CBT</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @forelse($examAttempts as $idx => $ea)
                    <tr>
                        <td class="border border-slate-300 p-2 text-center font-mono">{{ $idx + 1 }}</td>
                        <td class="border border-slate-300 p-2 font-bold text-slate-800">{{ $ea->exam?->mata_pelajaran ?? '-' }}</td>
                        <td class="border border-slate-300 p-2 font-semibold">{{ $ea->exam?->judul ?? $ea->exam?->title ?? '-' }}</td>
                        <td class="border border-slate-300 p-2 text-center uppercase text-[10px] font-bold text-slate-700">{{ $ea->exam?->tipe_ujian ?? 'CBT' }}</td>
                        <td class="border border-slate-300 p-2 text-center font-mono text-slate-600">{{ $ea->created_at ? $ea->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td class="border border-slate-300 p-2 text-center font-bold text-slate-900 font-mono bg-slate-50">{{ number_format($ea->skor_akhir ?? 0, 1) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="border border-slate-300 p-4 text-center text-slate-400 italic">Belum ada ujian online CBT yang diselesaikan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- REKAP KEHADIRAN & PRESIENSI --}}
        <div class="space-y-2">
            <h3 class="text-xs font-bold uppercase text-slate-800 tracking-wider">
                4. REKAPITULASI KEHADIRAN / PRESENSI SISWA
            </h3>
            <div class="grid grid-cols-5 gap-2 text-center text-xs">
                <div class="border border-slate-300 p-2.5 rounded-lg bg-slate-50">
                    <span class="text-[10px] uppercase font-bold text-slate-700 block">Hadir</span>
                    <span class="font-extrabold text-slate-900 text-sm block mt-0.5">{{ $statHadir }} Hari</span>
                </div>
                <div class="border border-slate-300 p-2.5 rounded-lg bg-slate-50">
                    <span class="text-[10px] uppercase font-bold text-slate-700 block">Sakit</span>
                    <span class="font-extrabold text-slate-900 text-sm block mt-0.5">{{ $statSakit }} Hari</span>
                </div>
                <div class="border border-slate-300 p-2.5 rounded-lg bg-slate-50">
                    <span class="text-[10px] uppercase font-bold text-slate-700 block">Izin</span>
                    <span class="font-extrabold text-slate-900 text-sm block mt-0.5">{{ $statIzin }} Hari</span>
                </div>
                <div class="border border-slate-300 p-2.5 rounded-lg bg-slate-50">
                    <span class="text-[10px] uppercase font-bold text-slate-700 block">Alpa</span>
                    <span class="font-extrabold text-slate-900 text-sm block mt-0.5">{{ $statAlpa }} Hari</span>
                </div>
                <div class="border border-slate-300 p-2.5 rounded-lg bg-slate-100">
                    <span class="text-[10px] uppercase font-bold text-slate-700 block">Kehadiran</span>
                    <span class="font-extrabold text-slate-900 text-sm block mt-0.5">{{ $persenHadir }}%</span>
                </div>
            </div>
        </div>

        {{-- TANDA TANGAN FORMAL --}}
        <div class="pt-4 grid grid-cols-2 gap-6 text-xs border-t border-slate-200">
            <div class="space-y-12">
                <div>
                    <p class="text-slate-600">Mengetahui,</p>
                    <p class="font-bold text-slate-900 mt-0.5">Orang Tua / Wali Siswa</p>
                </div>
                <div>
                    <p class="border-b border-slate-900 font-bold text-slate-900 pb-1 w-48">( ......................................... )</p>
                </div>
            </div>

            <div class="text-right space-y-12">
                <div>
                    <p class="text-slate-700">
                        {{ $setting?->alamat ? explode(',', $setting->alamat)[0] : 'Kota Digital' }}, {{ date('d F Y') }}<br>
                        <strong>Wali Kelas {{ $siswa->kelas }}</strong>
                    </p>
                </div>
                <div>
                    <p class="font-bold text-slate-900 underline uppercase inline-block">( ...................................................... )</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">NIP/NIPY. ........................................</p>
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
