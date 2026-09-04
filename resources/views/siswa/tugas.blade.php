@extends('siswa.layouts.app')

@section('title', 'Tugas Saya')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Daftar Tugas Sekolah</h2>
            <p class="text-xs text-slate-500 mt-1">Kumpulkan tugas Anda secara online sebelum batas waktu deadline.</p>
        </div>
        <a href="{{ route('siswa.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-colors border border-slate-200">
            &larr; Kembali
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm flex items-center gap-2">
        <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="space-y-4">
        @forelse($tugasList as $tugas)
        @php $pengumpulan = $submittedTugas[$tugas->id_tugas] ?? null; @endphp
        <div class="bg-white border border-slate-200 rounded-[5px] p-6 text-slate-900 space-y-4 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <span class="inline-block text-[10px] uppercase font-bold text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded border border-amber-200 mb-1">
                        {{ $tugas->mata_pelajaran }}
                    </span>
                    <h3 class="text-base font-extrabold text-slate-900">{{ $tugas->judul }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Deadline: <span class="font-bold text-slate-700">{{ $tugas->deadline->isoFormat('D MMMM Y, HH:mm') }} WIB</span>
                    </p>
                </div>

                <div>
                    @if($pengumpulan)
                        @if(!is_null($pengumpulan->nilai))
                            <div class="text-right">
                                <span class="text-[10px] text-slate-400 block uppercase font-bold">Nilai Tugas</span>
                                <span class="text-2xl font-black text-emerald-600">{{ $pengumpulan->nilai }} / 100</span>
                            </div>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                ⏳ Dikumpulkan (Menunggu Penilaian)
                            </span>
                        @endif
                    @else
                        @if($tugas->deadline->isPast())
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                ⚠️ Melewati Batas Deadline
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                📌 Belum Dikumpulkan
                            </span>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Deskripsi Tugas --}}
            @if($tugas->deskripsi || $tugas->file_lampiran)
            <div class="bg-slate-50 p-4 rounded-[5px] border border-slate-200 space-y-2 text-xs">
                <span class="font-bold text-slate-500 uppercase">Petunjuk Guru:</span>
                <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $tugas->deskripsi ?? 'Tidak ada petunjuk tertulis.' }}</p>
                @if($tugas->file_lampiran)
                    <div class="pt-1">
                        <a href="{{ asset('storage/' . $tugas->file_lampiran) }}" target="_blank" class="inline-flex items-center gap-1 text-emerald-600 font-bold hover:underline">
                            📎 Unduh File Lampiran Tugas
                        </a>
                    </div>
                @endif
            </div>
            @endif

            {{-- Result & Feedback if graded --}}
            @if($pengumpulan && $pengumpulan->catatan_guru)
            <div class="p-3.5 rounded-[5px] bg-purple-50 border border-purple-200 text-purple-800 text-xs">
                <strong>Catatan Guru:</strong> "{{ $pengumpulan->catatan_guru }}"
            </div>
            @endif

            {{-- Submission Form --}}
            <div class="pt-2 border-t border-slate-100">
                <h4 class="text-xs font-bold uppercase text-slate-500 mb-2">
                    {{ $pengumpulan ? 'Perbarui Pengumpulan Tugas Saya:' : 'Kumpulkan Jawaban Tugas:' }}
                </h4>

                <form action="{{ route('siswa.tugas.store', $tugas->id_tugas) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf

                    <div>
                        <textarea name="jawaban_teks" rows="3" placeholder="Tuliskan teks jawaban tugas Anda di sini (opsional jika mengunggah berkas)..."
                                  class="w-full px-4 py-3 rounded-[5px] bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-900 focus:bg-white focus:ring-2 focus:ring-amber-500 outline-none">{{ old('jawaban_teks', $pengumpulan?->jawaban_teks) }}</textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex-1">
                            <input type="file" name="file_tugas" class="w-full text-xs text-slate-600 bg-slate-50 px-3 py-2 rounded-[5px] border border-slate-200">
                            @if($pengumpulan?->file_tugas)
                                <a href="{{ asset('storage/' . $pengumpulan->file_tugas) }}" target="_blank" class="inline-block mt-1 text-[11px] text-emerald-600 font-bold hover:underline">
                                    📄 Berkas saat ini tersimpan: Download
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="px-5 py-2.5 rounded-[5px] bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-sm transition-colors shrink-0">
                            {{ $pengumpulan ? 'Simpan Perubahan Jawaban' : 'Kirim Jawaban Tugas' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-slate-400 bg-white rounded-[5px] border border-slate-200 shadow-sm">
            Belum ada tugas yang dipublikasikan untuk kelas {{ $siswa->kelas }}.
        </div>
        @endforelse

        @if($tugasList->hasPages())
        <div class="pt-4">
            {{ $tugasList->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
