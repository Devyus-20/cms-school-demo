@extends('admin.layouts.app')

@section('title', 'Hasil Pengumpulan Tugas')
@section('page-title', 'Detail Tugas & Nilai')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="inline-block text-xs uppercase font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-md mb-1">{{ $tugas->mata_pelajaran }} — {{ $tugas->kelas }}</span>
            <h2 class="text-xl font-extrabold text-slate-800">{{ $tugas->judul }}</h2>
            <p class="text-xs text-slate-500 mt-1">Batas Deadline: {{ $tugas->deadline->isoFormat('D MMMM Y, HH:mm') }} WIB</p>
        </div>
        <a href="{{ route('admin.tugas.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold hover:bg-slate-200 transition-colors shrink-0">
            &larr; Kembali ke Daftar Tugas
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

    {{-- Deskripsi Tugas --}}
    @if($tugas->deskripsi || $tugas->file_lampiran)
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
        <h3 class="text-xs font-bold uppercase text-slate-500">Petunjuk Tugas:</h3>
        <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $tugas->deskripsi ?? 'Tidak ada petunjuk tertulis.' }}</p>
        @if($tugas->file_lampiran)
            <div class="pt-2">
                <a href="{{ asset('storage/' . $tugas->file_lampiran) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold hover:bg-emerald-100 transition-colors">
                    📎 Unduh Lampiran Tugas
                </a>
            </div>
        @endif
    </div>
    @endif

    {{-- Submissions Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">Daftar Pengumpulan Siswa Kelas {{ $tugas->kelas }}</h3>
            <span class="text-xs text-slate-500">Dikumpulkan: {{ $tugas->pengumpulan->count() }} / {{ $siswaKelas->count() }} Siswa</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 uppercase text-[11px] font-bold text-slate-500">
                    <tr>
                        <th class="px-5 py-3.5">Nama Siswa</th>
                        <th class="px-5 py-3.5">Waktu Kumpul</th>
                        <th class="px-5 py-3.5">Jawaban / File</th>
                        <th class="px-5 py-3.5">Nilai (0-100)</th>
                        <th class="px-5 py-3.5">Catatan Guru</th>
                        <th class="px-5 py-3.5">Aksi / Simpan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tugas->pengumpulan as $p)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-5 py-4 font-semibold text-slate-800">
                            {{ $p->siswa?->nama_lengkap }}
                            <div class="text-[10px] text-slate-400 font-mono">NIS: {{ $p->siswa?->nis }}</div>
                        </td>
                        <td class="px-5 py-4 text-xs font-medium text-slate-600">
                            {{ $p->tanggal_kumpul->isoFormat('D MMM Y, HH:mm') }} WIB
                            @if($p->tanggal_kumpul->gt($tugas->deadline))
                                <span class="inline-block px-1.5 py-0.5 text-[9px] rounded bg-red-100 text-red-600 font-bold ml-1">Terlambat</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-xs">
                            @if($p->jawaban_teks)
                                <div class="max-w-xs p-2 rounded bg-slate-50 text-slate-700 mb-1 border border-slate-200 text-xs italic">
                                    "{{ Str::limit($p->jawaban_teks, 80) }}"
                                </div>
                            @endif
                            @if($p->file_tugas)
                                <a href="{{ asset('storage/' . $p->file_tugas) }}" target="_blank" class="inline-flex items-center gap-1 text-emerald-700 font-bold hover:underline text-xs">
                                    📄 Download Berkas Tugas
                                </a>
                            @endif
                        </td>

                        <form action="{{ route('admin.tugas.nilai.update', $p->id_pengumpulan) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td class="px-5 py-4 w-32">
                                <input type="number" step="0.1" name="nilai" min="0" max="100" value="{{ old('nilai', $p->nilai) }}" required placeholder="0-100"
                                       class="w-full px-3 py-1.5 rounded-lg border border-slate-200 font-bold text-center text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                            </td>
                            <td class="px-5 py-4">
                                <input type="text" name="catatan_guru" value="{{ old('catatan_guru', $p->catatan_guru) }}" placeholder="Catatan guru..."
                                       class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                            </td>
                            <td class="px-5 py-4">
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors shadow-sm">
                                    Simpan Nilai
                                </button>
                            </td>
                        </form>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada siswa yang mengumpulkan tugas ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
