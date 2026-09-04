@extends('admin.layouts.app')

@section('title', 'Kelola Soal Ujian')
@section('page-title', 'Kelola Bank Soal: ' . $exam->judul)

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.exams.index') }}" class="text-xs font-semibold text-slate-500 hover:text-emerald-600">← Kembali ke Ujian</a>
                <span class="text-slate-300">/</span>
                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-xs font-bold">{{ $exam->mata_pelajaran }}</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800 mt-1">{{ $exam->judul }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">Total: <strong class="text-slate-700">{{ $questions->count() }} Soal</strong> | Durasi: {{ $exam->durasi_menit }} Menit | KKM: {{ $exam->kkm ?? 75 }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.exams.questions.template', $exam) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors shadow-md shadow-emerald-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download Template Excel/CSV
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Form: Tambah / Import Soal --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5 sticky top-20">
                
                {{-- Mode Tabs Header --}}
                <div class="border-b border-slate-200 pb-3 flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-800">Input / Import Soal</h2>
                </div>

                {{-- Mode Switcher Buttons --}}
                <div class="grid grid-cols-3 gap-1 bg-slate-100 p-1 rounded-xl text-[11px] font-bold text-slate-600">
                    <button type="button" id="tab_btn_manual" onclick="switchQuestionTab('manual')"
                            class="py-2 rounded-lg transition-all bg-white text-emerald-700 shadow-sm">
                        ✏️ Manual
                    </button>
                    <button type="button" id="tab_btn_file" onclick="switchQuestionTab('file')"
                            class="py-2 rounded-lg transition-all hover:text-slate-800">
                        📄 File CSV
                    </button>
                    <button type="button" id="tab_btn_text" onclick="switchQuestionTab('text')"
                            class="py-2 rounded-lg transition-all hover:text-slate-800">
                        📋 Paste Teks
                    </button>
                </div>

                {{-- TAB 1: FORM INPUT MANUAL --}}
                <div id="tab_content_manual" class="space-y-4">
                    <form action="{{ route('admin.exams.questions.store', $exam) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tipe Soal</label>
                            <select name="jenis" id="jenis_soal_select" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 focus:bg-white focus:border-emerald-400 outline-none">
                                <option value="pilihan_ganda">Pilihan Ganda (A - E)</option>
                                <option value="essay">Essay / Uraian</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Teks Pertanyaan <span class="text-red-500">*</span></label>
                            <textarea name="pertanyaan" rows="3" required placeholder="Tuliskan isi soal di sini..."
                                      class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:bg-white focus:border-emerald-400 outline-none">{{ old('pertanyaan') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Lampirkan Foto / Gambar Soal (Opsional)</label>
                            <input type="file" name="gambar" accept="image/*"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                            <p class="mt-1 text-[10px] text-slate-400">Atau URL Gambar: <input type="text" name="gambar_url" placeholder="https://..." class="mt-1 w-full px-3 py-1 rounded-lg border border-slate-200 text-xs bg-slate-50"></p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Bobot Nilai <span class="text-red-500">*</span></label>
                            <input type="number" name="bobot_nilai" min="1" value="{{ old('bobot_nilai', 1) }}" required
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 focus:bg-white focus:border-emerald-400 outline-none">
                        </div>

                        {{-- Pilihan Ganda Section --}}
                        <div id="pg_container" class="space-y-3 pt-2 border-t border-slate-100">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Pilihan Jawaban</label>
                            
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">A</span>
                                <input type="text" name="pilihan_a" placeholder="Pilihan A" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs outline-none focus:border-emerald-400">
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">B</span>
                                <input type="text" name="pilihan_b" placeholder="Pilihan B" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs outline-none focus:border-emerald-400">
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">C</span>
                                <input type="text" name="pilihan_c" placeholder="Pilihan C (Opsional)" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs outline-none focus:border-emerald-400">
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">D</span>
                                <input type="text" name="pilihan_d" placeholder="Pilihan D (Opsional)" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs outline-none focus:border-emerald-400">
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">E</span>
                                <input type="text" name="pilihan_e" placeholder="Pilihan E (Opsional)" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs outline-none focus:border-emerald-400">
                            </div>

                            <div class="pt-2">
                                <label class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-1">Kunci Jawaban Benar</label>
                                <select name="kunci_jawaban" class="w-full px-3 py-2 rounded-xl border border-emerald-300 bg-emerald-50 text-xs font-bold text-emerald-900 outline-none">
                                    <option value="A">Jawaban A</option>
                                    <option value="B">Jawaban B</option>
                                    <option value="C">Jawaban C</option>
                                    <option value="D">Jawaban D</option>
                                    <option value="E">Jawaban E</option>
                                </select>
                            </div>
                        </div>

                        {{-- Essay Section --}}
                        <div id="essay_container" class="space-y-3 pt-2 border-t border-slate-100 hidden">
                            <label class="block text-xs font-bold text-purple-700 uppercase tracking-wider mb-1">
                                Kunci / Pedoman Jawaban Essay (Opsional)
                            </label>
                            <textarea name="kunci_jawaban_essay" rows="3" placeholder="Tuliskan kunci / pedoman / kata kunci jawaban essay sebagai acuan penilaian..."
                                      class="w-full px-3 py-2 rounded-xl border border-purple-300 bg-purple-50 text-xs text-purple-900 focus:bg-white focus:border-purple-500 outline-none">{{ old('kunci_jawaban_essay') }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors shadow-md shadow-emerald-600/20 cursor-pointer">
                            + Tambahkan Soal
                        </button>
                    </form>
                </div>

                {{-- TAB 2: UPLOAD FILE EXCEL / CSV --}}
                <div id="tab_content_file" class="space-y-4 hidden">
                    <form action="{{ route('admin.exams.questions.import_file', $exam) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 space-y-1">
                            <p class="font-bold">💡 Petunjuk Upload Dokumen:</p>
                            <p class="text-[11px] text-emerald-700">1. Unduh template CSV dengan mengklik tombol di bawah ini.<br>2. Isi soal & pilihan jawaban di Excel/Spreadsheet.<br>3. Simpan dan unggah filenya di sini.</p>
                            <a href="{{ route('admin.exams.questions.template', $exam) }}" class="inline-block mt-1 underline font-bold text-emerald-900">
                                📥 Unduh File Template CSV Soal
                            </a>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih Dokumen File (.csv / .txt) <span class="text-red-500">*</span></label>
                            <input type="file" name="file_soal" accept=".csv, .txt" required
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-800 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors shadow-md shadow-emerald-600/20 cursor-pointer flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unggah & Impor Soal Dokumen
                        </button>
                    </form>
                </div>

                {{-- TAB 3: IMPORT COPY-PASTE TEKS --}}
                <div id="tab_content_text" class="space-y-4 hidden">
                    <form action="{{ route('admin.exams.questions.import_text', $exam) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="p-3.5 rounded-xl bg-purple-50 border border-purple-200 text-xs text-purple-900 space-y-1">
                            <p class="font-bold">📋 Format Copy-Paste dari Word / Catatan:</p>
                            <p class="text-[11px] font-mono text-purple-800">
                                1. Berapakah 15 + 25?<br>
                                A. 30<br>
                                B. 35<br>
                                C. 40<br>
                                D. 45<br>
                                Kunci: C<br>
                                Bobot: 1
                            </p>
                            <p class="text-[11px] text-purple-700">Pisahkan setiap nomor soal dengan 1 baris kosong.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tempelkan Teks Soal di Sini <span class="text-red-500">*</span></label>
                            <textarea name="raw_text" rows="8" required placeholder="Tempelkan (paste) teks soal dari Word di sini..."
                                      class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono text-slate-800 focus:bg-white focus:border-purple-400 outline-none resize-y"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs transition-colors shadow-md shadow-purple-600/20 cursor-pointer flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Impor Teks Bulk Soal
                        </button>
                    </form>
                </div>

            </div>
        </div>

        {{-- Right List: Daftar Soal --}}
        <div class="lg:col-span-2 space-y-4">
            @forelse($questions as $index => $q)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-3 relative group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-xl bg-slate-900 text-white font-bold text-xs flex items-center justify-center">
                                {{ $index + 1 }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-md text-[11px] font-extrabold uppercase {{ $q->jenis === 'pilihan_ganda' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">
                                {{ $q->jenis === 'pilihan_ganda' ? 'Pilihan Ganda' : 'Essay' }}
                            </span>
                            <span class="text-xs text-slate-400 font-semibold">Bobot: {{ $q->bobot_nilai }} Poin</span>
                        </div>

                        <form action="{{ route('admin.exams.questions.delete', $q) }}" method="POST" onsubmit="return confirm('Hapus soal ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>

                    <div class="text-sm font-semibold text-slate-800 leading-relaxed pt-1">
                        {!! nl2br(e($q->pertanyaan)) !!}
                    </div>

                    @if($q->gambar)
                        <div class="my-2 p-2 rounded-xl border border-slate-200 bg-slate-50 inline-block max-w-md">
                            <img src="{{ asset($q->gambar) }}" alt="Gambar Soal {{ $index + 1 }}" class="max-h-52 object-contain rounded-lg w-full">
                        </div>
                    @endif

                    @if($q->jenis === 'pilihan_ganda')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 text-xs">
                            @foreach(['a' => $q->pilihan_a, 'b' => $q->pilihan_b, 'c' => $q->pilihan_c, 'd' => $q->pilihan_d, 'e' => $q->pilihan_e] as $key => $val)
                                @if($val)
                                    @php $isKunci = (strtoupper($key) === strtoupper($q->kunci_jawaban)); @endphp
                                    <div class="p-2.5 rounded-xl border flex items-center gap-2 {{ $isKunci ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : 'bg-slate-50 border-slate-200 text-slate-700' }}">
                                        <span class="w-5 h-5 rounded-md text-[10px] font-black flex items-center justify-center uppercase {{ $isKunci ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                                            {{ $key }}
                                        </span>
                                        <span class="truncate">{{ $val }}</span>
                                        @if($isKunci)
                                            <span class="ml-auto text-[10px] font-extrabold text-emerald-700 uppercase tracking-wider">Kunci</span>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        {{-- Tampilan Kunci Jawaban Essay di Daftar Soal --}}
                        @if($q->kunci_jawaban)
                            <div class="p-3 rounded-xl bg-purple-50 border border-purple-200 text-xs text-purple-900 space-y-1">
                                <div class="font-extrabold uppercase text-[10px] text-purple-700">Kunci / Pedoman Jawaban Essay:</div>
                                <div class="whitespace-pre-wrap font-medium">{!! nl2br(e($q->kunci_jawaban)) !!}</div>
                            </div>
                        @endif
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 text-sm">
                    Belum ada soal untuk ujian ini. Gunakan form di sebelah kiri untuk menambah atau mengunggah dokumen soal.
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function switchQuestionTab(tab) {
        var btnManual = document.getElementById('tab_btn_manual');
        var btnFile   = document.getElementById('tab_btn_file');
        var btnText   = document.getElementById('tab_btn_text');

        var contentManual = document.getElementById('tab_content_manual');
        var contentFile   = document.getElementById('tab_content_file');
        var contentText   = document.getElementById('tab_content_text');

        // Reset all
        [btnManual, btnFile, btnText].forEach(function(btn) {
            if (btn) {
                btn.className = 'py-2 rounded-lg transition-all hover:text-slate-800 text-slate-600';
            }
        });
        [contentManual, contentFile, contentText].forEach(function(c) {
            if (c) c.classList.add('hidden');
        });

        if (tab === 'manual') {
            btnManual.className = 'py-2 rounded-lg transition-all bg-white text-emerald-700 shadow-sm font-bold';
            contentManual.classList.remove('hidden');
        } else if (tab === 'file') {
            btnFile.className = 'py-2 rounded-lg transition-all bg-white text-emerald-700 shadow-sm font-bold';
            contentFile.classList.remove('hidden');
        } else if (tab === 'text') {
            btnText.className = 'py-2 rounded-lg transition-all bg-white text-purple-700 shadow-sm font-bold';
            contentText.classList.remove('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var select = document.getElementById('jenis_soal_select');
        var pgContainer = document.getElementById('pg_container');
        var essayContainer = document.getElementById('essay_container');

        function toggleContainers() {
            if (!select) return;
            if (select.value === 'essay') {
                if (pgContainer) pgContainer.classList.add('hidden');
                if (essayContainer) essayContainer.classList.remove('hidden');
            } else {
                if (pgContainer) pgContainer.classList.remove('hidden');
                if (essayContainer) essayContainer.classList.add('hidden');
            }
        }

        if (select) {
            select.addEventListener('change', toggleContainers);
            toggleContainers();
        }
    });
</script>
@endsection
