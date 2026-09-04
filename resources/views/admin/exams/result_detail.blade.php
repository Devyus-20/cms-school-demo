@extends('admin.layouts.app')

@section('title', 'Detail Jawaban Peserta')
@section('page-title', 'Detail Jawaban: ' . $attempt->nama_peserta)

@section('content')
<div class="space-y-6 max-w-4xl">
    {{-- Header --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.exams.results', $attempt->id_exam) }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-600">← Kembali ke Rekap Hasil</a>
            </div>
            <h1 class="text-xl font-bold text-slate-800 mt-1">{{ $attempt->nama_peserta }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">NIS/Email: <strong>{{ $attempt->nis_email }}</strong> | Kelas: <strong>{{ $attempt->kelas }}</strong></p>
        </div>

        <div class="text-right flex flex-col items-end gap-2">
            <div>
                <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Skor Akhir</div>
                <div class="text-3xl font-black text-emerald-600">{{ number_format($attempt->skor_akhir, 1) }}</div>
            </div>
            <a href="{{ route('admin.exams.results.print', $attempt) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-colors shadow-sm">
                🖨️ Cetak Lembar Jawaban
            </a>
        </div>
    </div>

    {{-- Answers Breakdown --}}
    <div class="space-y-4">
        <h2 class="text-base font-bold text-slate-800">Rincian Jawaban per Nomor Soal</h2>

        @forelse($attempt->answers as $index => $ans)
            @php $q = $ans->question; @endphp
            @if($q)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-slate-900 text-white font-bold text-xs flex items-center justify-center">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-xs font-bold uppercase text-slate-500">{{ $q->jenis }}</span>
                        </div>

                        @if($q->jenis === 'pilihan_ganda')
                            @if($ans->is_benar)
                                <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs">
                                    ✓ Benar (+{{ $ans->nilai_soal }})
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-red-100 text-red-800 font-bold text-xs">
                                    ✗ Salah (0)
                                </span>
                            @endif
                        @endif
                    </div>

                    <div class="text-sm font-semibold text-slate-800 leading-relaxed">
                        {!! nl2br(e($q->pertanyaan)) !!}
                    </div>

                    @if($q->gambar)
                        <div class="my-2 p-2 rounded-xl border border-slate-200 bg-slate-50 inline-block max-w-md">
                            <img src="{{ asset($q->gambar) }}" alt="Gambar Soal {{ $index + 1 }}" class="max-h-52 object-contain rounded-lg w-full">
                        </div>
                    @endif

                    @if($q->jenis === 'pilihan_ganda')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs pt-1">
                            @foreach(['A' => $q->pilihan_a, 'B' => $q->pilihan_b, 'C' => $q->pilihan_c, 'D' => $q->pilihan_d, 'E' => $q->pilihan_e] as $opt => $text)
                                @if($text)
                                    @php
                                        $isChosen = (strtoupper(trim($ans->jawaban_peserta)) === $opt);
                                        $isKey    = (strtoupper(trim($q->kunci_jawaban)) === $opt);
                                        
                                        $style = 'bg-slate-50 border-slate-200 text-slate-600';
                                        if ($isKey) {
                                            $style = 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold';
                                        }
                                        if ($isChosen && !$isKey) {
                                            $style = 'bg-red-50 border-red-300 text-red-900 font-bold';
                                        }
                                    @endphp
                                    <div class="p-2.5 rounded-xl border flex items-center justify-between {{ $style }}">
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-md text-[10px] font-black flex items-center justify-center uppercase {{ $isKey ? 'bg-emerald-600 text-white' : ($isChosen ? 'bg-red-600 text-white' : 'bg-slate-200 text-slate-600') }}">
                                                {{ $opt }}
                                            </span>
                                            <span>{{ $text }}</span>
                                        </div>
                                        <div>
                                            @if($isChosen && $isKey)
                                                <span class="text-[10px] font-bold text-emerald-700">✓ Jawaban Siswa (Benar)</span>
                                            @elseif($isChosen && !$isKey)
                                                <span class="text-[10px] font-bold text-red-600">✗ Jawaban Siswa</span>
                                            @elseif($isKey)
                                                <span class="text-[10px] font-bold text-emerald-600">★ Kunci Jawaban</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        {{-- Essay Section --}}
                        <div class="space-y-3 pt-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                {{-- Jawaban Siswa --}}
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="font-extrabold text-slate-600 uppercase tracking-wider text-[10px]">Jawaban Essay Siswa:</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-700">Perolehan Nilai: {{ $ans->nilai_soal }} / {{ $q->bobot_nilai }}</span>
                                    </div>
                                    <div class="text-slate-800 whitespace-pre-wrap font-medium leading-relaxed bg-white p-3 rounded-lg border border-slate-200 min-h-[60px]">
                                        {{ $ans->jawaban_peserta ?? '(Siswa tidak menjawab)' }}
                                    </div>
                                </div>

                                {{-- Kunci / Pedoman Jawaban Admin --}}
                                <div class="p-4 rounded-xl bg-purple-50 border border-purple-200 text-xs space-y-1.5">
                                    <div class="font-extrabold text-purple-700 uppercase tracking-wider text-[10px]">Kunci / Pedoman Jawaban (Guru/Admin):</div>
                                    <div class="text-purple-900 whitespace-pre-wrap font-medium leading-relaxed bg-white p-3 rounded-lg border border-purple-200 min-h-[60px]">
                                        {{ $q->kunci_jawaban ?: '(Belum ada kunci / pedoman jawaban)' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Form Penilaian Manual Essay --}}
                            <form action="{{ route('admin.exams.answers.grade', $ans->id_answer) }}" method="POST" class="flex flex-col sm:flex-row items-end gap-3 p-3.5 rounded-xl bg-amber-50 border border-amber-200">
                                @csrf
                                @method('PUT')
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-amber-900 mb-1">
                                        Beri / Ubah Nilai Soal Essay (Maksimal: {{ $q->bobot_nilai }} Poin)
                                    </label>
                                    <input type="number" name="nilai_soal" step="0.5" min="0" max="{{ $q->bobot_nilai }}" value="{{ old('nilai_soal', $ans->nilai_soal) }}" required
                                           class="w-full px-3 py-1.5 rounded-lg border border-amber-300 bg-white text-xs font-bold text-slate-800 focus:outline-none focus:border-amber-500">
                                </div>
                                <button type="submit" class="px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs transition-colors shadow-sm cursor-pointer whitespace-nowrap">
                                    Simpan Nilai Essay
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        @empty
            <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center text-slate-400 text-sm">
                Belum ada rincian jawaban.
            </div>
        @endforelse
    </div>
</div>
@endsection
