@extends('admin.layouts.app')

@section('title', 'Tambah Ujian Online')
@section('page-title', 'Tambah Ujian Online Baru')

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.exams.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-emerald-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
        <span class="text-slate-300">/</span>
        <span class="text-sm text-slate-700 font-semibold">Buat Ujian Baru</span>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.exams.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Ujian / Ulangan <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" required value="{{ old('judul') }}" placeholder="Contoh: Ulangan Harian BAB 1 Matematika"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                    @error('judul')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <input type="text" name="mata_pelajaran" required value="{{ old('mata_pelajaran') }}" placeholder="Contoh: Matematika"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                    @error('mata_pelajaran')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori / Tipe Ujian <span class="text-red-500">*</span></label>
                    <select name="tipe_ujian" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm font-semibold text-slate-800 outline-none transition-all">
                        <option value="uh" {{ old('tipe_ujian') == 'uh' ? 'selected' : '' }}>Ulangan Harian (UH)</option>
                        <option value="uts" {{ old('tipe_ujian') == 'uts' ? 'selected' : '' }}>Ujian Tengah Semester (UTS)</option>
                        <option value="uas" {{ old('tipe_ujian') == 'uas' ? 'selected' : '' }}>Ujian Akhir Semester (UAS)</option>
                        <option value="lainnya" {{ old('tipe_ujian') == 'lainnya' ? 'selected' : '' }}>Kuis / Tugas Online / Lainnya</option>
                    </select>
                    @error('tipe_ujian')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Durasi (Menit) <span class="text-red-500">*</span></label>
                    <input type="number" name="durasi_menit" min="1" required value="{{ old('durasi_menit', 60) }}" placeholder="60"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                    @error('durasi_menit')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Standar KKM (Nilai Minimum) <span class="text-red-500">*</span></label>
                    <input type="number" name="kkm" min="0" max="100" required value="{{ old('kkm', 75) }}" placeholder="75"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                    <p class="mt-1 text-[11px] text-slate-400">Nilai batas ketuntasan siswa (Default: 75).</p>
                    @error('kkm')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waktu Mulai Pelaksanaan (Opsional)</label>
                    <input type="datetime-local" name="waktu_mulai" value="{{ old('waktu_mulai') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Waktu Selesai Pelaksanaan (Opsional)</label>
                    <input type="datetime-local" name="waktu_selesai" value="{{ old('waktu_selesai') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Token Masuk Ujian (Opsional)</label>
                    <input type="text" name="token" value="{{ old('token') }}" placeholder="Contoh: EXAM123 (Kosongkan jika tanpa token)"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none uppercase font-mono tracking-wider transition-all">
                    <p class="mt-1 text-[11px] text-slate-400">Jika diisi, siswa harus memasukkan token ini sebelum dapat mengerjakan ujian.</p>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi / Petunjuk Ujian</label>
                    <textarea name="deskripsi" rows="3" placeholder="Tuliskan petunjuk pengerjaan ujian bagi siswa..."
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 text-sm text-slate-800 outline-none transition-all">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="acak_soal" value="1" {{ old('acak_soal') ? 'checked' : '' }}
                           class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                    <span class="text-sm font-medium text-slate-700">Acak urutan soal untuk setiap siswa</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="tampilkan_nilai" value="1" {{ old('tampilkan_nilai', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                    <span class="text-sm font-medium text-slate-700">Tampilkan nilai/skor akhir secara langsung setelah siswa menyelesaikan ujian</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="batasi_peserta" value="1" {{ old('batasi_peserta') ? 'checked' : '' }}
                           class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                    <div>
                        <span class="text-sm font-medium text-slate-700 block">Batasi Hanya Peserta Terdaftar (Whitelist)</span>
                        <span class="text-[11px] text-slate-400">Jika diaktifkan, hanya NIS/Email yang terdaftar di daftar peserta yang diperbolehkan masuk ujian.</span>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="aktif" value="1" {{ old('aktif', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                    <span class="text-sm font-medium text-slate-700">Aktifkan Ujian Ini (Dapat diakses oleh siswa)</span>
                </label>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-100">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors shadow-md shadow-emerald-600/20 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Ujian Baru
                </button>
                <a href="{{ route('admin.exams.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
