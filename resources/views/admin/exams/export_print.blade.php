<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap_Nilai_{{ \Illuminate\Support\Str::slug($exam->judul) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        @media print {
            .no-print { display: none !important; }
            html, body { font-size: 11pt; background: #fff !important; padding: 0 !important; margin: 0 !important; }
            .print-sheet { border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 p-4 sm:p-8">

    {{-- Action Bar --}}
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('admin.exams.results', $exam) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 text-white text-xs font-bold hover:bg-slate-900 transition-colors">
            ← Kembali ke Rekap
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.exams.export.csv', $exam) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors">
                📥 Download Excel/CSV
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors">
                🖨️ Cetak PDF / Print
            </button>
        </div>
    </div>

    {{-- Printable Paper Sheet --}}
    <div class="print-sheet max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-2xl border border-slate-200 shadow-lg space-y-6">
        
        {{-- Kop Sekolah --}}
        <div class="border-b-2 border-slate-800 pb-4 text-center flex items-center justify-between gap-4">
            <div class="w-16 h-16 flex items-center justify-center shrink-0">
                @if($setting && $setting->logo)
                    <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo, ['http://', 'https://']) ? $setting->logo : asset($setting->logo) }}" alt="Logo Utama" class="w-16 h-16 max-w-[64px] max-h-[64px] object-contain shrink-0">
                @endif
            </div>
            <div class="text-center flex-1">
                <h1 class="text-xl sm:text-2xl font-black uppercase tracking-wider text-slate-900">
                    {{ $setting->website_name ?? 'MA AL IKHLAS' }}
                </h1>
                <p class="text-xs text-slate-600">
                    {{ $setting->alamat ?? $setting->alamat_sekolah ?? 'Jl. Raya Pendidikan No. 123' }}
                    @if($setting && $setting->telepon) | Telp: {{ $setting->telepon }} @endif
                    @if($setting && $setting->email) | Email: {{ $setting->email }} @endif
                </p>
            </div>
            <div class="w-16 h-16 flex items-center justify-center shrink-0">
                @if($setting && $setting->logo_instansi)
                    <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo_instansi, ['http://', 'https://']) ? $setting->logo_instansi : asset($setting->logo_instansi) }}" alt="Logo Instansi" class="w-16 h-16 max-w-[64px] max-h-[64px] object-contain shrink-0">
                @endif
            </div>
        </div>

        {{-- Title --}}
        <div class="text-center space-y-1 pt-2">
            <h2 class="text-lg font-bold uppercase tracking-wide text-slate-900 underline">
                LAPORAN REKAPITULASI HASIL UJIAN ONLINE
            </h2>
            <p class="text-xs text-slate-500">Mata Pelajaran: <strong>{{ $exam->mata_pelajaran }}</strong></p>
        </div>

        {{-- Metadata Ujian --}}
        <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-xl border border-slate-200">
            <div>
                <table class="w-full">
                    <tr><td class="py-1 text-slate-500 w-32">Judul Ujian</td><td class="font-bold">: {{ $exam->judul }}</td></tr>
                    <tr><td class="py-1 text-slate-500">Mata Pelajaran</td><td class="font-bold">: {{ $exam->mata_pelajaran }}</td></tr>
                    <tr><td class="py-1 text-slate-500">Durasi Pengerjaan</td><td class="font-bold">: {{ $exam->durasi_menit }} Menit</td></tr>
                </table>
            </div>
            <div>
                <table class="w-full">
                    <tr><td class="py-1 text-slate-500 w-32">Total Soal</td><td class="font-bold">: {{ $questionsCount }} Soal</td></tr>
                    <tr><td class="py-1 text-slate-500">Total Peserta Ujian</td><td class="font-bold">: {{ $totalPeserta }} Siswa</td></tr>
                    <tr><td class="py-1 text-slate-500">Tanggal Cetak</td><td class="font-bold">: {{ date('d F Y, H:i') }} WIB</td></tr>
                </table>
            </div>
        </div>

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-4 gap-3 text-center">
            <div class="p-3 bg-blue-50 rounded-xl border border-blue-200">
                <div class="text-[10px] uppercase font-bold text-blue-600">Rata-rata Nilai</div>
                <div class="text-lg font-black text-blue-900 mt-0.5">{{ $avgScore }}</div>
            </div>
            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200">
                <div class="text-[10px] uppercase font-bold text-emerald-600">Nilai Tertinggi</div>
                <div class="text-lg font-black text-emerald-900 mt-0.5">{{ $maxScore }}</div>
            </div>
            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200">
                <div class="text-[10px] uppercase font-bold text-amber-600">Nilai Terendah</div>
                <div class="text-lg font-black text-amber-900 mt-0.5">{{ $minScore }}</div>
            </div>
            <div class="p-3 bg-purple-50 rounded-xl border border-purple-200">
                <div class="text-[10px] uppercase font-bold text-purple-600">Ketuntasan (KKM {{ $exam->kkm ?? 75 }})</div>
                <div class="text-lg font-black text-purple-900 mt-0.5">{{ $lulusCount }} / {{ $totalPeserta }}</div>
            </div>
        </div>

        {{-- Tabel Hasil Ujian --}}
        <div class="overflow-x-auto pt-2">
            <table class="w-full text-xs text-left border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-200 text-slate-800 font-bold border-b border-slate-300">
                        <th class="py-2.5 px-3 border border-slate-300 w-10 text-center">No</th>
                        <th class="py-2.5 px-3 border border-slate-300">Nama Peserta</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-28">NIS / Email</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-20 text-center">Kelas</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-36 text-center">Waktu Pengerjaan</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-24 text-center">Nilai Akhir</th>
                        <th class="py-2.5 px-3 border border-slate-300 w-24 text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $index => $attempt)
                        @php
                            $isPass = $attempt->skor_akhir >= ($exam->kkm ?? 75);
                        @endphp
                        <tr class="border-b border-slate-200">
                            <td class="py-2 px-3 border border-slate-300 text-center font-bold">{{ $index + 1 }}</td>
                            <td class="py-2 px-3 border border-slate-300 font-bold text-slate-800">{{ $attempt->nama_peserta }}</td>
                            <td class="py-2 px-3 border border-slate-300">{{ $attempt->nis_email }}</td>
                            <td class="py-2 px-3 border border-slate-300 text-center font-bold">{{ $attempt->kelas }}</td>
                            <td class="py-2 px-3 border border-slate-300 text-center text-[10px]">
                                {{ $attempt->waktu_mulai ? $attempt->waktu_mulai->format('H:i') : '-' }} - 
                                {{ $attempt->waktu_selesai ? $attempt->waktu_selesai->format('H:i') : '-' }}
                            </td>
                            <td class="py-2 px-3 border border-slate-300 text-center font-black text-sm">
                                {{ number_format($attempt->skor_akhir, 1) }}
                            </td>
                            <td class="py-2 px-3 border border-slate-300 text-center font-bold text-[10px]">
                                @if($isPass)
                                    <span class="text-emerald-700">TUNTAS</span>
                                @else
                                    <span class="text-red-700">BELUM TUNTAS</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-500">Belum ada data peserta yang menyelesaikan ujian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Lembar Pengesahan --}}
        <div class="pt-8 grid grid-cols-2 text-center text-xs text-slate-700">
            <div>
                <p>Mengetahui,</p>
                <p class="font-bold">Kepala Sekolah</p>
                <div class="h-16"></div>
                <p class="font-bold underline">( _______________________ )</p>
                <p class="text-[10px] text-slate-500">NIP. ........................................</p>
            </div>
            <div>
                <p>Tanggal: {{ date('d F Y') }}</p>
                <p class="font-bold">Guru Mata Pelajaran</p>
                <div class="h-16"></div>
                <p class="font-bold underline">( _______________________ )</p>
                <p class="text-[10px] text-slate-500">NIP. ........................................</p>
            </div>
        </div>

    </div>
</body>
</html>
