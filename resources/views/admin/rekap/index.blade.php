@extends('admin.layouts.app')

@section('title', 'Rekapitulasi Nilai & Perankingan Siswa')
@section('page-title', 'Rekap & Perankingan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800">Rekapitulasi Nilai & Perankingan Otomatis</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Rumus Nilai Akhir: <strong class="text-emerald-700 font-mono">(Nilai Tugas + Ulangan Harian + UTS + UAS) / 4</strong>
            </p>
        </div>
        <a href="{{ route('admin.rekap.print', ['kelas' => $selectedKelas]) }}" target="_blank"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-blue-600/20 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Rekap & Perankingan (A4)
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Filter Bar --}}
    <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.rekap.index') }}" method="GET" class="flex items-center gap-3 w-full sm:w-auto">
            <label class="text-xs font-bold uppercase text-slate-600">Pilih Kelas:</label>
            <select name="kelas" onchange="this.form.submit()" class="px-3.5 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 font-bold text-slate-800">
                @foreach($kelases as $k)
                    <option value="{{ $k }}" {{ $selectedKelas == $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>
        </form>
        <div class="text-xs text-slate-500 font-semibold">
            Total Siswa: <span class="font-bold text-slate-800">{{ $rankedData->count() }} Orang</span>
        </div>
    </div>

    {{-- Rekap Table Form --}}
    <div class="bg-white rounded-[5px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">Tabel Perankingan & Komponen Nilai Kelas {{ $selectedKelas }}</h3>
            <span class="text-xs text-slate-500">Anda dapat menginput / mengedit Nilai UH, UTS, dan UAS langsung pada tabel di bawah ini.</span>
        </div>

        <form action="{{ route('admin.rekap.nilai-manual.store') }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-xs sm:text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 uppercase text-[11px] font-bold text-slate-500">
                        <tr>
                            <th class="px-4 py-3.5 text-center">Rank</th>
                            <th class="px-4 py-3.5">NIS & Nama Siswa</th>
                            <th class="px-4 py-3.5 text-center">Nilai Tugas</th>
                            <th class="px-4 py-3.5 text-center w-28">Ulangan Harian (UH)</th>
                            <th class="px-4 py-3.5 text-center w-28">UTS</th>
                            <th class="px-4 py-3.5 text-center w-28">UAS</th>
                            <th class="px-4 py-3.5 text-center">Nilai Akhir</th>
                            <th class="px-4 py-3.5 text-center">Rincian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($rankedData as $item)
                        @php
                            $rank = $item['ranking'];
                            $siswa = $item['siswa'];
                            $badgeBg = 'bg-slate-100 text-slate-700';
                            if ($rank === 1) $badgeBg = 'bg-amber-400 text-slate-900 shadow font-extrabold';
                            elseif ($rank === 2) $badgeBg = 'bg-slate-300 text-slate-900 font-extrabold';
                            elseif ($rank === 3) $badgeBg = 'bg-amber-700 text-white font-bold';

                            $detailData = [
                                'id_siswa' => $siswa->id_siswa,
                                'nama' => $siswa->nama_lengkap,
                                'nis' => $siswa->nis,
                                'kelas' => $siswa->kelas,
                                'nilai_tugas' => $item['nilai_tugas'],
                                'nilai_uh' => $item['nilai_uh'],
                                'nilai_uts' => $item['nilai_uts'],
                                'nilai_uas' => $item['nilai_uas'],
                                'nilai_akhir' => number_format($item['nilai_akhir'], 2),
                                'tugas' => $item['tugas_details'],
                                'uh' => $item['uh_attempts']->map(fn($a) => ['judul' => $a->exam?->judul ?? 'Ulangan Harian', 'skor' => $a->skor_akhir, 'tgl' => $a->created_at ? $a->created_at->format('d/m/Y') : '-']),
                                'uts' => $item['uts_attempts']->map(fn($a) => ['judul' => $a->exam?->judul ?? 'UTS', 'skor' => $a->skor_akhir, 'tgl' => $a->created_at ? $a->created_at->format('d/m/Y') : '-']),
                                'uas' => $item['uas_attempts']->map(fn($a) => ['judul' => $a->exam?->judul ?? 'UAS', 'skor' => $a->skor_akhir, 'tgl' => $a->created_at ? $a->created_at->format('d/m/Y') : '-']),
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs {{ $badgeBg }}">
                                    #{{ $rank }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-bold text-slate-800">{{ $siswa->nama_lengkap }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">NIS: {{ $siswa->nis }}</div>
                            </td>
                            <td class="px-4 py-3.5 text-center font-bold text-blue-700">
                                {{ $item['nilai_tugas'] }}
                            </td>

                            {{-- Input UH --}}
                            <td class="px-4 py-3.5">
                                <input type="number" step="0.1" min="0" max="100" name="nilai[{{ $siswa->id_siswa }}][uh]"
                                       value="{{ old('nilai.' . $siswa->id_siswa . '.uh', $item['nilai_uh'] != 0 ? $item['nilai_uh'] : '') }}"
                                       placeholder="0"
                                       class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 font-bold text-center text-xs focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                            </td>

                            {{-- Input UTS --}}
                            <td class="px-4 py-3.5">
                                <input type="number" step="0.1" min="0" max="100" name="nilai[{{ $siswa->id_siswa }}][uts]"
                                       value="{{ old('nilai.' . $siswa->id_siswa . '.uts', $item['nilai_uts'] != 0 ? $item['nilai_uts'] : '') }}"
                                       placeholder="0"
                                       class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 font-bold text-center text-xs focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                            </td>

                            {{-- Input UAS --}}
                            <td class="px-4 py-3.5">
                                <input type="number" step="0.1" min="0" max="100" name="nilai[{{ $siswa->id_siswa }}][uas]"
                                       value="{{ old('nilai.' . $siswa->id_siswa . '.uas', $item['nilai_uas'] != 0 ? $item['nilai_uas'] : '') }}"
                                       placeholder="0"
                                       class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 font-bold text-center text-xs focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                            </td>

                            {{-- Nilai Akhir Hasil Rumus --}}
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-block px-3 py-1 rounded-xl bg-emerald-100 text-emerald-800 text-sm font-extrabold">
                                    {{ number_format($item['nilai_akhir'], 2) }}
                                </span>
                            </td>

                            {{-- Tombol Detail & Cetak Nilai --}}
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" onclick="openDetailModal({{ json_encode($detailData) }})"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs transition-colors"
                                            title="Lihat Rincian Nilai">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </button>
                                    <a href="{{ route('admin.rekap.print-siswa', $siswa->id_siswa) }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs transition-colors"
                                       title="Cetak Transkrip Detail Nilai Siswa">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Cetak
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-slate-400">Tidak ada data siswa untuk kelas {{ $selectedKelas }}.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($rankedData->isNotEmpty())
            <div class="p-4 border-t border-slate-100 flex justify-end bg-slate-50/50">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/20 transition-colors">
                    Simpan Nilai UH, UTS, UAS & Hitung Ulang Perankingan
                </button>
            </div>
            @endif
        </form>
    </div>
</div>

{{-- Modal Detail Rincian Nilai --}}
<div id="detailModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden">
        {{-- Modal Header --}}
        <div class="p-5 bg-slate-900 text-white flex items-center justify-between">
            <div>
                <h3 id="detSiswaName" class="text-base font-bold leading-tight">Detail Nilai Siswa</h3>
                <p id="detSiswaSub" class="text-xs text-slate-400 mt-0.5"></p>
            </div>
            <button onclick="closeDetailModal()" class="text-slate-400 hover:text-white text-xl font-bold p-1">&times;</button>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-4 gap-2 p-4 bg-slate-50 border-b border-slate-200 text-center text-xs">
            <div class="bg-white p-2 rounded-xl border border-slate-200">
                <span class="text-[10px] text-slate-500 font-bold uppercase block">Rata Tugas</span>
                <span id="detNilaiTugas" class="font-extrabold text-blue-700 text-sm"></span>
            </div>
            <div class="bg-white p-2 rounded-xl border border-slate-200">
                <span class="text-[10px] text-slate-500 font-bold uppercase block">Nilai UH</span>
                <span id="detNilaiUH" class="font-extrabold text-purple-700 text-sm"></span>
            </div>
            <div class="bg-white p-2 rounded-xl border border-slate-200">
                <span class="text-[10px] text-slate-500 font-bold uppercase block">Nilai UTS</span>
                <span id="detNilaiUTS" class="font-extrabold text-amber-700 text-sm"></span>
            </div>
            <div class="bg-white p-2 rounded-xl border border-slate-200">
                <span class="text-[10px] text-slate-500 font-bold uppercase block">Nilai UAS</span>
                <span id="detNilaiUAS" class="font-extrabold text-red-700 text-sm"></span>
            </div>
        </div>

        {{-- Modal Body Scrollable --}}
        <div class="p-5 overflow-y-auto space-y-5 text-xs text-slate-800 flex-1">
            {{-- Section 1: Rincian Tugas --}}
            <div>
                <h4 class="font-bold text-slate-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span> Rincian Tugas Siswa
                </h4>
                <div id="detTugasContainer" class="border border-slate-200 rounded-xl overflow-hidden"></div>
            </div>

            {{-- Section 2: Rincian Ujian Online --}}
            <div>
                <h4 class="font-bold text-slate-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Rincian Ujian Online (UH, UTS, UAS)
                </h4>
                <div id="detUjianContainer" class="border border-slate-200 rounded-xl overflow-hidden"></div>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
            <a id="btnCetakModal" href="#" target="_blank" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors inline-flex items-center gap-1.5 shadow-md shadow-emerald-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Lembar Transkrip Siswa (A4)
            </a>
            <button onclick="closeDetailModal()" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openDetailModal(data) {
        document.getElementById('detSiswaName').innerText = data.nama;
        document.getElementById('detSiswaSub').innerText = 'NIS: ' + data.nis + ' | Kelas: ' + data.kelas + ' | Nilai Akhir: ' + data.nilai_akhir;
        document.getElementById('btnCetakModal').href = '/admin/rekap-akademik/print-siswa/' + data.id_siswa;

        document.getElementById('detNilaiTugas').innerText = data.nilai_tugas;
        document.getElementById('detNilaiUH').innerText = data.nilai_uh;
        document.getElementById('detNilaiUTS').innerText = data.nilai_uts;
        document.getElementById('detNilaiUAS').innerText = data.nilai_uas;

        // Render Tugas Table
        let tugasHtml = '';
        if (data.tugas && data.tugas.length > 0) {
            tugasHtml = `<table class="w-full text-left">
                <thead class="bg-slate-100 text-slate-600 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-2.5">Judul Tugas</th>
                        <th class="p-2.5">Mata Pelajaran</th>
                        <th class="p-2.5 text-center">Status</th>
                        <th class="p-2.5 text-center">Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">`;
            data.tugas.forEach(t => {
                let statusBadge = t.terkumpul
                    ? '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Dikumpulkan</span>'
                    : '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Belum Kumpul</span>';
                let nilaiText = t.nilai !== null && t.nilai !== undefined ? '<strong class="text-blue-700">' + t.nilai + '</strong>' : '-';
                tugasHtml += `<tr>
                    <td class="p-2.5 font-bold">${t.judul}</td>
                    <td class="p-2.5 text-slate-600">${t.mata_pelajaran}</td>
                    <td class="p-2.5 text-center">${statusBadge}</td>
                    <td class="p-2.5 text-center font-mono">${nilaiText}</td>
                </tr>`;
            });
            tugasHtml += `</tbody></table>`;
        } else {
            tugasHtml = `<div class="p-4 text-center text-slate-400 italic">Belum ada tugas yang diberikan di kelas ini.</div>`;
        }
        document.getElementById('detTugasContainer').innerHTML = tugasHtml;

        // Render Ujian Table
        let allUjian = [];
        if (data.uh) data.uh.forEach(u => allUjian.push({...u, tipe: 'UH (Ulangan Harian)'}));
        if (data.uts) data.uts.forEach(u => allUjian.push({...u, tipe: 'UTS (Tengah Semester)'}));
        if (data.uas) data.uas.forEach(u => allUjian.push({...u, tipe: 'UAS (Akhir Semester)'}));

        let ujianHtml = '';
        if (allUjian.length > 0) {
            ujianHtml = `<table class="w-full text-left">
                <thead class="bg-slate-100 text-slate-600 font-bold uppercase text-[10px]">
                    <tr>
                        <th class="p-2.5">Kategori</th>
                        <th class="p-2.5">Judul Ujian</th>
                        <th class="p-2.5 text-center">Tanggal</th>
                        <th class="p-2.5 text-center">Skor Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">`;
            allUjian.forEach(u => {
                ujianHtml += `<tr>
                    <td class="p-2.5 font-bold text-slate-600">${u.tipe}</td>
                    <td class="p-2.5 font-bold text-slate-800">${u.judul}</td>
                    <td class="p-2.5 text-center font-mono text-slate-500">${u.tgl}</td>
                    <td class="p-2.5 text-center font-extrabold text-emerald-700 font-mono">${u.skor}</td>
                </tr>`;
            });
            ujianHtml += `</tbody></table>`;
        } else {
            ujianHtml = `<div class="p-4 text-center text-slate-400 italic">Belum ada percobaan ujian online yang diselesaikan oleh siswa ini.</div>`;
        }
        document.getElementById('detUjianContainer').innerHTML = ujianHtml;

        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>
@endsection
