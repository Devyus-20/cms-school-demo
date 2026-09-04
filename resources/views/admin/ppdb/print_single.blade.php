<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        @media print {
            .no-print { display: none !important; }
            html, body { background: #fff !important; color: #000 !important; font-size: 12px !important; padding: 0 !important; margin: 0 !important; }
            .print-card { border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 p-4 sm:p-8">

    {{-- Action Bar --}}
    <div class="max-w-3xl mx-auto mb-6 flex items-center justify-between no-print bg-white p-4 rounded-2xl shadow-md border border-slate-200">
        <a href="{{ route('admin.ppdb.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
            ← Kembali ke Daftar PPDB
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak / Download PDF
        </button>
    </div>

    {{-- Printable Card --}}
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-3xl shadow-xl border border-slate-200 print-card space-y-6">

        {{-- KOP SEKOLAH --}}
        <table style="width: 100%; border-collapse: collapse; border-bottom: 2px solid #0f172a; margin-bottom: 12px;">
            <tr>
                <td style="width: 90px; text-align: left; vertical-align: bottom; padding-bottom: 6px;">
                    @if($setting && $setting->logo)
                        <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo, ['http://', 'https://']) ? $setting->logo : asset($setting->logo) }}" alt="Logo Utama" style="width: 64px; height: 64px; max-width: 64px; max-height: 64px; object-fit: contain; display: block;">
                    @endif
                </td>
                <td style="text-align: center; vertical-align: bottom; padding-bottom: 6px;">
                    <h1 style="font-size: 22px; font-weight: 900; text-transform: uppercase; color: #0f172a; margin: 0; line-height: 1.2;">
                        {{ $setting->website_name ?? 'PANITIA PPDB ONLINE' }}
                    </h1>
                    <p style="font-size: 12px; font-weight: 600; color: #475569; margin: 2px 0 0 0;">
                        {{ $setting->alamat ?? 'Jl. Pendidikan No. 45' }} | Telp/WA: {{ $setting->telepon ?? '-' }}
                    </p>
                    <p style="font-size: 11px; font-weight: 500; color: #64748b; margin: 2px 0 0 0;">
                        Email: {{ $setting->email ?? '-' }} | Website: {{ url('/') }}
                    </p>
                </td>
                <td style="width: 90px; text-align: right; vertical-align: bottom; padding-bottom: 6px;">
                    @if($setting && $setting->logo_instansi)
                        <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo_instansi, ['http://', 'https://']) ? $setting->logo_instansi : asset($setting->logo_instansi) }}" alt="Logo Instansi" style="width: 64px; height: 64px; max-width: 64px; max-height: 64px; object-fit: contain; display: block; margin-left: auto;">
                    @endif
                </td>
            </tr>
        </table>

        {{-- DOCUMENT TITLE & REGISTRATION NO --}}
        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-center relative">
            <h2 class="text-base font-bold text-slate-900 uppercase tracking-wide">
                BUKTI PENDAFTARAN & BIODATA CALON SISWA
            </h2>
            <p class="text-xs text-slate-500 font-medium">Penerimaan Peserta Didik Baru (PPDB) T.A {{ $setting->ppdb_tahun ?? date('Y') }}</p>
            
            <div class="mt-3 inline-block bg-white px-4 py-1.5 rounded-xl border border-slate-300 font-mono font-bold text-base text-slate-900 shadow-sm">
                NO. REG: {{ $pendaftar->no_pendaftaran }}
            </div>

            <div class="mt-2 text-xs">
                Status Verifikasi:
                @if($pendaftar->status === 'diterima')
                    <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">DITERIMA</span>
                @elseif($pendaftar->status === 'ditolak')
                    <span class="font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-md border border-red-200">DITOLAK</span>
                @else
                    <span class="font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">MENUNGGU VERIFIKASI (PENDING)</span>
                @endif
            </div>
        </div>

        {{-- BIODATA TABLE --}}
        <div class="space-y-4">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-1">
                A. IDENTITAS CALON SISWA
            </h3>

            <table class="w-full text-xs text-slate-800 border-collapse">
                <tbody>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500 w-1/3">Nama Lengkap</td>
                        <td class="py-2.5 font-bold text-slate-900">: {{ $pendaftar->nama_lengkap }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500">NISN</td>
                        <td class="py-2.5 font-mono font-bold">: {{ $pendaftar->nisn ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500">Jenis Kelamin</td>
                        <td class="py-2.5">: {{ $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500">Tempat, Tanggal Lahir</td>
                        <td class="py-2.5">: {{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tanggal_lahir }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500">Agama</td>
                        <td class="py-2.5">: {{ $pendaftar->agama ?? 'Islam' }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500">Alamat Lengkap</td>
                        <td class="py-2.5">: {{ $pendaftar->alamat }}</td>
                    </tr>
                </tbody>
            </table>

            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-1 pt-2">
                B. AKADEMIK & KONTAK
            </h3>

            <table class="w-full text-xs text-slate-800 border-collapse">
                <tbody>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500 w-1/3">Sekolah Asal (SMP/MTs)</td>
                        <td class="py-2.5 font-bold">: {{ $pendaftar->sekolah_asal }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500">Pilihan Jurusan / Peminatan</td>
                        <td class="py-2.5 font-bold text-emerald-700">: {{ $pendaftar->jurusan ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500">No. HP / WhatsApp Active</td>
                        <td class="py-2.5 font-mono font-bold">: {{ $pendaftar->no_hp }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500">Email</td>
                        <td class="py-2.5">: {{ $pendaftar->email ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>

            @if(is_array($pendaftar->data_tambahan) && count(array_filter($pendaftar->data_tambahan)) > 0)
                @php
                    $customFieldsMap = \App\Models\PpdbCustomField::pluck('label', 'field_key')->toArray();
                @endphp
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-1 pt-2">
                    C. DATA TAMBAHAN KHUSUS SEKOLAH
                </h3>

                <table class="w-full text-xs text-slate-800 border-collapse">
                    <tbody>
                        @foreach($pendaftar->data_tambahan as $key => $val)
                            @if(!empty($val))
                                <tr class="border-b border-slate-100">
                                    <td class="py-2.5 font-semibold text-slate-500 w-1/3">{{ $customFieldsMap[$key] ?? \Illuminate\Support\Str::title(str_replace('_', ' ', $key)) }}</td>
                                    <td class="py-2.5 font-bold">: {{ is_array($val) ? implode(', ', $val) : $val }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @endif

            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-200 pb-1 pt-2">
                C. DATA ORANG TUA / WALI & BERKAS
            </h3>

            <table class="w-full text-xs text-slate-800 border-collapse">
                <tbody>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500 w-1/3">Nama Orang Tua / Wali</td>
                        <td class="py-2.5 font-bold">: {{ $pendaftar->nama_orang_tua }}</td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500">Status Berkas Pendaftaran</td>
                        <td class="py-2.5">
                            : {{ $pendaftar->berkas ? 'Telah Diunggah (Terlampir)' : 'Belum Ada Berkas Terlampir' }}
                        </td>
                    </tr>
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500">Tanggal Waktu Pendaftaran</td>
                        <td class="py-2.5">: {{ $pendaftar->created_at->format('d F Y - H:i:s') }} WIB</td>
                    </tr>
                    @if($pendaftar->catatan)
                    <tr class="border-b border-slate-100">
                        <td class="py-2.5 font-semibold text-slate-500">Catatan Panitia</td>
                        <td class="py-2.5 font-semibold text-amber-700">: {{ $pendaftar->catatan }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- SIGNATURE SECTION --}}
        <div class="pt-6 border-t border-slate-200 grid grid-cols-2 gap-8 text-xs text-center">
            <div>
                <p class="text-slate-500">Calon Siswa Baru,</p>
                <div class="h-16"></div>
                <p class="font-bold text-slate-900 underline">{{ $pendaftar->nama_lengkap }}</p>
                <p class="text-[10px] text-slate-400">Tanda Tangan & Nama Terang</p>
            </div>
            <div>
                <p class="text-slate-500">{{ $setting->alamat ? explode(',', $setting->alamat)[0] : 'Kota' }}, {{ date('d F Y') }}</p>
                <p class="text-slate-500">Panitia PPDB,</p>
                <div class="h-14"></div>
                <p class="font-bold text-slate-900 underline">(......................................................)</p>
                <p class="text-[10px] text-slate-400">Verifikator PPDB</p>
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
