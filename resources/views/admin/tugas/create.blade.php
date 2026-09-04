@extends('admin.layouts.app')

@section('title', 'Buat Tugas Baru')
@section('page-title', 'Buat Tugas')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800">Buat Tugas Baru</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Publikasikan tugas ke portal siswa sesuai kelas & mata pelajaran.</p>
        </div>
        <a href="{{ route('admin.tugas.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold hover:bg-slate-200 transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
        <form action="{{ route('admin.tugas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Mata Pelajaran *</label>
                    <input type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}" required placeholder="cth: Matematika / Bahasa Indonesia"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                    @error('mata_pelajaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Target Kelas *</label>
                    <input type="text" name="kelas" value="{{ old('kelas', 'X MIPA 1') }}" required placeholder="cth: X MIPA 1"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                    @error('kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Judul Tugas *</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="cth: Tugas Bab 3 - Persamaan Kuadrat"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 font-semibold">
                @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Batas Pengumpulan (Deadline) *</label>
                <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                @error('deadline') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">Deskripsi / Petunjuk Pengerjaan</label>
                <textarea name="deskripsi" rows="4" placeholder="Tuliskan petunjuk pengerjaan tugas di sini..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50">{{ old('deskripsi') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-slate-700 mb-1">File Lampiran (PDF / Gambar / Docx - Maks 10 MB)</label>
                <input type="file" name="file_lampiran" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-600 bg-slate-50">
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.tugas.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-md shadow-emerald-600/20 transition-colors">
                    Publikasikan Tugas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
