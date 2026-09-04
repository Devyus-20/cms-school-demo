@extends('admin.layouts.app')

@section('title', 'Edit Ujian Online')
@section('page-title', 'Edit Pengaturan Ujian')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.exams.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-sm text-slate-700 font-semibold">Edit Ujian</span>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.exams.update', $exam) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Ujian / Ulangan <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" required value="{{ old('judul', $exam->judul) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <input type="text" name="mata_pelajaran" required value="{{ old('mata_pelajaran', $exam->mata_pelajaran) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori / Tipe Ujian <span class="text-red-500">*</span></label>
                    <select name="tipe_ujian" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm font-semibold text-slate-800 outline-none transition-all">
                        <option value="uh" {{ old('tipe_ujian', $exam->tipe_ujian) == 'uh' ? 'selected' : '' }}>Ulangan Harian (UH)</option>
                        <option value="uts" {{ old('tipe_ujian', $exam->tipe_ujian) == 'uts' ? 'selected' : '' }}>Ujian Tengah Semester (UTS)</option>
                        <option value="uas" {{ old('tipe_ujian', $exam->tipe_ujian) == 'uas' ? 'selected' : '' }}>Ujian Akhir Semester (UAS)</option>
                        <option value="lainnya" {{ old('tipe_ujian', $exam->tipe_ujian) == 'lainnya' ? 'selected' : '' }}>Kuis / Tugas Online / Lainnya</option>
                    </select>
                    @error('tipe_ujian')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Durasi (Menit) <span class="text-red-500">*</span></label>
                    <input type="number" name="durasi_menit" min="1" required value="{{ old('durasi_menit', $exam->durasi_menit) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Standar KKM (Nilai Minimum) <span class="text-red-500">*</span></label>
                    <input type="number" name="kkm" min="0" max="100" required value="{{ old('kkm', $exam->kkm ?? 75) }}" placeholder="75"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                    <p class="mt-1 text-[11px] text-slate-400">Nilai batas ketuntasan siswa (Default: 75).</p>
                    @error('kkm')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waktu Mulai Pelaksanaan (Opsional)</label>
                    <input type="datetime-local" name="waktu_mulai" value="{{ old('waktu_mulai', $exam->waktu_mulai ? $exam->waktu_mulai->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waktu Selesai Pelaksanaan (Opsional)</label>
                    <input type="datetime-local" name="waktu_selesai" value="{{ old('waktu_selesai', $exam->waktu_selesai ? $exam->waktu_selesai->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Token Masuk Ujian (Opsional)</label>
                    <input type="text" name="token" value="{{ old('token', $exam->token) }}" placeholder="Contoh: EXAM123"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none uppercase font-mono tracking-wider transition-all">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi / Petunjuk Ujian</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">{{ old('deskripsi', $exam->deskripsi) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="acak_soal" value="1" {{ old('acak_soal', $exam->acak_soal) ? 'checked' : '' }}
                           class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                    <span class="text-sm font-medium text-slate-700">Acak urutan soal untuk setiap siswa</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="tampilkan_nilai" value="1" {{ old('tampilkan_nilai', $exam->tampilkan_nilai) ? 'checked' : '' }}
                           class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                    <span class="text-sm font-medium text-slate-700">Tampilkan nilai/skor akhir secara langsung setelah siswa menyelesaikan ujian</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="batasi_peserta" value="1" {{ old('batasi_peserta', $exam->batasi_peserta) ? 'checked' : '' }}
                           class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                    <div>
                        <span class="text-sm font-medium text-slate-700 block">Batasi Hanya Peserta Terdaftar (Whitelist)</span>
                        <span class="text-[11px] text-slate-400">Jika diaktifkan, hanya NIS/Email yang terdaftar di daftar peserta yang diperbolehkan masuk ujian.</span>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="aktif" value="1" {{ old('aktif', $exam->aktif) ? 'checked' : '' }}
                           class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                    <span class="text-sm font-medium text-slate-700">Aktifkan Ujian Ini (Dapat diakses oleh siswa)</span>
                </label>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-100">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-md shadow-emerald-600/20 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Perbarui Ujian
                </button>
                <a href="{{ route('admin.exams.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
