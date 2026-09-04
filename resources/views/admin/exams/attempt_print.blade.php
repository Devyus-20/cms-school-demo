<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar_Jawaban_{{ \Illuminate\Support\Str::slug($attempt->nama_peserta) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 portrait; margin: 10mm; }
        @media print {
            .no-print { display: none !important; }
            html, body { font-size: 11pt; background: #fff !important; padding: 0 !important; margin: 0 !important; }
            .print-sheet { border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 p-4 sm:p-8">

    {{-- Action Bar --}}
    <div class="max-w-3xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('admin.exams.results.detail', $attempt) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 text-white text-xs font-bold hover:bg-slate-900 transition-colors">
            ← Kembali ke Detail Jawaban
        </a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors">
            🖨️ Cetak Lembar Jawaban
        </button>
    </div>

    {{-- Printable Paper Sheet --}}
    <div class="print-sheet max-w-3xl mx-auto bg-white p-8 sm:p-10 rounded-2xl border border-slate-200 shadow-lg space-y-6">
        
        {{-- Header --}}
        <div class="border-b-2 border-slate-800 pb-3 text-center flex items-center justify-between gap-4">
            <div class="w-16 h-16 flex items-center justify-center shrink-0">
                @if($setting && $setting->logo)
                    <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo, ['http://', 'https://']) ? $setting->logo : asset($setting->logo) }}" alt="Logo Utama" class="w-16 h-16 max-w-[64px] max-h-[64px] object-contain shrink-0">
                @endif
            </div>
            <div class="text-center flex-1">
                <h1 class="text-xl font-black uppercase text-slate-900">{{ $setting->website_name ?? 'MA AL IKHLAS' }}</h1>
                <h2 class="text-xs font-bold text-slate-700 uppercase tracking-wide mt-0.5">LEMBAR JAWABAN PESERTA UJIAN ONLINE</h2>
            </div>
            <div class="w-16 h-16 flex items-center justify-center shrink-0">
                @if($setting && $setting->logo_instansi)
                    <img src="{{ \Illuminate\Support\Str::startsWith($setting->logo_instansi, ['http://', 'https://']) ? $setting->logo_instansi : asset($setting->logo_instansi) }}" alt="Logo Instansi" class="w-16 h-16 max-w-[64px] max-h-[64px] object-contain shrink-0">
                @endif
            </div>
        </div>

        {{-- Identitas Siswa --}}
        <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-xl border border-slate-200">
            <div>
                <table class="w-full">
                    <tr><td class="py-1 text-slate-500 w-28">Nama Peserta</td><td class="font-bold">: {{ $attempt->nama_peserta }}</td></tr>
                    <tr><td class="py-1 text-slate-500">NIS / Email</td><td class="font-bold">: {{ $attempt->nis_email }}</td></tr>
                    <tr><td class="py-1 text-slate-500">Kelas</td><td class="font-bold">: {{ $attempt->kelas }}</td></tr>
                </table>
            </div>
            <div>
                <table class="w-full">
                    <tr><td class="py-1 text-slate-500 w-28">Mata Pelajaran</td><td class="font-bold">: {{ $attempt->exam->mata_pelajaran ?? '-' }}</td></tr>
                    <tr><td class="py-1 text-slate-500">Judul Ujian</td><td class="font-bold">: {{ $attempt->exam->judul ?? '-' }}</td></tr>
                    <tr><td class="py-1 text-slate-500">Nilai Akhir</td><td class="font-black text-emerald-700">: {{ number_format($attempt->skor_akhir, 1) }} / 100</td></tr>
                </table>
            </div>
        </div>

        {{-- Rincian Jawaban Per-Soal --}}
        <div class="space-y-4 pt-2">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 border-b pb-1">Rincian Jawaban Siswa</h3>

            @php
                $answersByQ = $attempt->answers->keyBy('id_question');
                $questions = $attempt->exam ? $attempt->exam->questions : collect();
            @endphp

            @foreach($questions as $index => $q)
                @php
                    $ans = $answersByQ->get($q->id_question);
                    $jwb = $ans ? $ans->jawaban_peserta : '-';
                    $isBenar = $ans ? $ans->is_benar : false;
                    $nilai = $ans ? $ans->nilai_soal : 0;
                @endphp
                <div class="p-3.5 rounded-xl border {{ $isBenar ? 'border-emerald-200 bg-emerald-50/40' : 'border-slate-200 bg-slate-50/40' }} space-y-2 text-xs">
                    <div class="flex items-start justify-between gap-2">
                        <div class="font-bold text-slate-800">
                            Soal {{ $index + 1 }}. {{ $q->pertanyaan }}
                        </div>
                        @if($q->gambar)
                            <div class="my-1.5 p-1 rounded-lg border border-slate-200 bg-white inline-block max-w-sm">
                                <img src="{{ asset($q->gambar) }}" alt="Gambar Soal {{ $index + 1 }}" class="max-h-40 object-contain rounded">
                            </div>
                        @endif
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold shrink-0 {{ $isBenar ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            Skor: {{ $nilai }} / {{ $q->bobot_nilai }}
                        </span>
                    </div>

                    @if($q->jenis === 'pilihan_ganda')
                        <div class="grid grid-cols-2 gap-2 text-[11px] pt-1">
                            <div>Jawaban Siswa: <strong class="{{ $isBenar ? 'text-emerald-700' : 'text-red-700' }}">{{ strtoupper($jwb) }}</strong></div>
                            <div>Kunci Jawaban: <strong class="text-slate-800">{{ strtoupper($q->kunci_jawaban) }}</strong></div>
                        </div>
                    @else
                        <div class="space-y-1 text-[11px] pt-1">
                            <div class="text-slate-500">Jawaban Essay Siswa:</div>
                            <div class="p-2 rounded bg-white border border-slate-200 font-mono text-slate-800">{!! nl2br(e($jwb)) !!}</div>
                            @if($q->kunci_jawaban)
                                <div class="text-slate-500 pt-1">Kunci / Acuan Jawaban:</div>
                                <div class="p-2 rounded bg-slate-100 border border-slate-200 font-mono text-slate-700">{!! nl2br(e($q->kunci_jawaban)) !!}</div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Footer Tanda Tangan --}}
        <div class="pt-6 grid grid-cols-2 text-center text-xs text-slate-700">
            <div>
                <p>Mengetahui,</p>
                <p class="font-bold">Orang Tua / Wali Siswa</p>
                <div class="h-14"></div>
                <p class="font-bold underline">( _______________________ )</p>
            </div>
            <div>
                <p>Pemeriksa,</p>
                <p class="font-bold">Guru Mata Pelajaran</p>
                <div class="h-14"></div>
                <p class="font-bold underline">( _______________________ )</p>
            </div>
        </div>

    </div>
</body>
</html>
