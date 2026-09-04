@extends('public.layouts.app')

@section('title', 'PPDB Online - ' . ($websiteSetting->website_name ?? 'MA Al Ikhlas'))

@section('content')
<div class="mx-auto max-w-4xl px-6 py-10 space-y-8 w-full flex-1">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
        <a href="/" class="hover:text-amber-400">Beranda</a>
        <span>/</span>
        <span class="text-amber-400 font-bold">PPDB Online</span>
    </div>

    {{-- Banner PPDB --}}
    <div class="bg-slate-900 rounded-[5px] p-8 sm:p-12 text-white shadow-xl space-y-6 relative overflow-hidden border border-slate-800">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-slate-800 border border-slate-700 text-amber-400 text-xs font-bold uppercase tracking-wider mb-3">
                <span>Tahun Ajaran {{ $setting->ppdb_tahun ?? '2026/2027' }}</span>
            </div>

            <h1 class="text-2xl sm:4xl md:text-5xl font-black leading-tight tracking-tight break-words">
                Penerimaan Peserta Didik Baru (PPDB)
            </h1>

            <p class="mt-2 text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">
                Selamat datang calon siswa-siswi baru {{ $websiteSetting->website_name ?? 'MA Al Ikhlas' }}. Silakan mendaftar secara online melalui formulir di bawah ini atau unduh formulir fisik (offline).
            </p>

            <div class="pt-2 flex flex-wrap items-center gap-3">
                <a href="#form-pendaftaran" class="px-6 py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 rounded-[5px] text-xs font-bold shadow-md transition-all flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Isi Formulir Online</span>
                </a>
                <a href="{{ route('public.ppdb.download-formulir') }}" target="_blank" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white rounded-[5px] text-xs font-bold shadow-md transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Download Formulir Offline (PDF)</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Alert Sukses Pendaftaran --}}
    @if(session('ppdb_success'))
    <div class="bg-emerald-50 border-2 border-emerald-500 rounded-[5px] p-6 sm:p-8 shadow-lg text-slate-800 space-y-4">
        <div class="flex items-center gap-3 text-emerald-700 font-extrabold text-xl">
            <div class="w-10 h-10 rounded-[5px] bg-emerald-600 text-white flex items-center justify-center shadow-md shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span>Pendaftaran Berhasil Dikirim!</span>
        </div>
        <p class="text-sm text-slate-600 leading-relaxed">
            Terima kasih, pendaftaran calon siswa atas nama <strong class="text-slate-900">{{ session('ppdb_success')['nama'] }}</strong> telah kami terima.
        </p>
        <div class="bg-white p-5 rounded-[5px] border border-emerald-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block">Nomor Pendaftaran Anda:</span>
                <span class="text-2xl font-black text-emerald-700 tracking-wide">{{ session('ppdb_success')['no_pendaftaran'] }}</span>
            </div>
            <div class="text-xs text-slate-500">
                <span>Simpan nomor pendaftaran ini sebagai bukti pendaftaran resmi. Panitia PPDB akan menghubungi Anda via WhatsApp di no: <strong>{{ session('ppdb_success')['no_hp'] }}</strong>.</span>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-[5px] text-sm font-semibold">
        {{ session('error') }}
    </div>
    @endif

    {{-- Keterangan / Syarat PPDB --}}
    @if($setting && $setting->ppdb_keterangan)
    <div class="bg-white rounded-[5px] border border-slate-200 p-8 shadow-sm space-y-3">
        <h2 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Informasi & Persyaratan PPDB</span>
        </h2>
        <div class="prose prose-amber max-w-none text-slate-600 text-sm leading-relaxed whitespace-pre-line">
            {{ $setting->ppdb_keterangan }}
        </div>
    </div>
    @endif
    {{-- Card Option Download Offline --}}
    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-[5px] p-6 sm:p-8 shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="space-y-1">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-400">Pendaftaran Offline / Fisik</span>
            <h3 class="text-lg font-bold text-white">Ingin Mendaftar Langsung ke Sekolah?</h3>
            <p class="text-xs text-slate-300 max-w-xl leading-relaxed">
                Unduh formulir pendaftaran fisik (PDF A4 Siap Cetak), isi secara manual, lalu serahkan ke panitia PPDB di gedung sekolah.
            </p>
        </div>
        <a href="{{ route('public.ppdb.download-formulir') }}" target="_blank"
            class="shrink-0 px-6 py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs rounded-[5px] transition-all shadow-lg shadow-amber-500/20 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Cetak / Download Formulir PDF</span>
        </a>
    </div>

    {{-- Form Pendaftaran Online --}}
    @if($setting && $setting->ppdb_aktif)
    <div id="form-pendaftaran" class="bg-white rounded-[5px] border border-slate-200 p-8 sm:p-10 shadow-sm space-y-8">
        <div class="border-b border-slate-100 pb-4">
            <h2 class="text-2xl font-black text-slate-800">Formulir Pendaftaran Siswa Baru</h2>
            <p class="text-xs text-slate-500 mt-1">Lengkapi seluruh kolom yang ditandai bintang (<span class="text-red-500">*</span>) untuk mendaftar.</p>
        </div>

        <form action="{{ route('public.ppdb.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- SECTION 1: DATA CALON SISWA --}}
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-amber-500 uppercase tracking-wider border-b border-amber-100 pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>1. Data Pribadi Calon Siswa</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Nama Lengkap Calon Siswa <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                            placeholder="Contoh: Ahmad Fauzi Rahmat"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                        @error('nama_lengkap') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            NISN (Nomor Induk Siswa Nasional)
                        </label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}"
                            placeholder="Contoh: 0051234567"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kelamin" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required
                            placeholder="Contoh: Jakarta"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                        @error('tempat_lahir') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                        @error('tanggal_lahir') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Agama
                        </label>
                        <input type="text" name="agama" value="{{ old('agama', 'Islam') }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Pilihan Jurusan / Program Studi
                        </label>
                        <select name="jurusan"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                            @php
                                $jurusanList = $setting ? $setting->jurusan_list : ['MIPA', 'IPS', 'Keagamaan'];
                            @endphp
                            @foreach($jurusanList as $itemJurusan)
                                <option value="{{ $itemJurusan }}" {{ old('jurusan') == $itemJurusan ? 'selected' : '' }}>
                                    {{ $itemJurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: SEKOLAH ASAL & KONTAK --}}
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-amber-500 uppercase tracking-wider border-b border-amber-100 pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0V7m0 4h4M9 7h4"/></svg>
                    <span>2. Sekolah Asal & Orang Tua / Wali</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Sekolah Asal (SMP / MTs) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal') }}" required
                            placeholder="Contoh: MTs Negeri 1 Kota"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                        @error('sekolah_asal') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Nama Orang Tua / Wali <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_orang_tua" value="{{ old('nama_orang_tua') }}" required
                            placeholder="Contoh: H. Suparman"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                        @error('nama_orang_tua') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            No. HP / WhatsApp Aktif <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                            placeholder="Contoh: 081234567890"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                        @error('no_hp') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Email Aktif (Opsional)
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="Contoh: email@gmail.com"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Alamat Tempat Tinggal Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alamat" rows="3" required
                            placeholder="Jl. Melati No. 12, RT 02/RW 05, Kel. Kebon, Kec. Kota"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">{{ old('alamat') }}</textarea>
                        @error('alamat') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            @php
                $customFields = \App\Models\PpdbCustomField::activeOrdered()->get();
            @endphp

            @if($customFields->count() > 0)
                {{-- SECTION: DATA TAMBAHAN KUSTOM SEKOLAH --}}
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-amber-500 uppercase tracking-wider border-b border-amber-100 pb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>3. Informasi & Data Tambahan Khusus Sekolah</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @foreach($customFields as $field)
                            <div class="{{ $field->tipe === 'textarea' ? 'sm:col-span-2' : '' }}">
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                    {{ $field->label }}
                                    @if($field->is_required) <span class="text-red-500">*</span> @endif
                                </label>

                                @if($field->tipe === 'text')
                                    <input type="text" name="data_tambahan[{{ $field->field_key }}]"
                                           value="{{ old('data_tambahan.' . $field->field_key) }}"
                                           placeholder="{{ $field->placeholder }}"
                                           {{ $field->is_required ? 'required' : '' }}
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">

                                @elseif($field->tipe === 'number')
                                    <input type="number" name="data_tambahan[{{ $field->field_key }}]"
                                           value="{{ old('data_tambahan.' . $field->field_key) }}"
                                           placeholder="{{ $field->placeholder }}"
                                           {{ $field->is_required ? 'required' : '' }}
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">

                                @elseif($field->tipe === 'textarea')
                                    <textarea name="data_tambahan[{{ $field->field_key }}]" rows="3"
                                              placeholder="{{ $field->placeholder }}"
                                              {{ $field->is_required ? 'required' : '' }}
                                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">{{ old('data_tambahan.' . $field->field_key) }}</textarea>

                                @elseif($field->tipe === 'date')
                                    <input type="date" name="data_tambahan[{{ $field->field_key }}]"
                                           value="{{ old('data_tambahan.' . $field->field_key) }}"
                                           {{ $field->is_required ? 'required' : '' }}
                                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">

                                @elseif($field->tipe === 'select')
                                    <select name="data_tambahan[{{ $field->field_key }}]" {{ $field->is_required ? 'required' : '' }}
                                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-amber-400 outline-none transition-all">
                                        <option value="">-- Pilih {{ $field->label }} --</option>
                                        @if(is_array($field->options))
                                             @foreach($field->options as $opt)
                                                <option value="{{ $opt }}" {{ old('data_tambahan.' . $field->field_key) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                @elseif($field->tipe === 'checkbox')
                                    <div class="flex flex-wrap gap-3 pt-1">
                                        @if(is_array($field->options))
                                             @foreach($field->options as $opt)
                                                <label class="inline-flex items-center gap-2 px-3 py-2 bg-slate-50 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 cursor-pointer hover:bg-white">
                                                    <input type="checkbox" name="data_tambahan[{{ $field->field_key }}][]" value="{{ $opt }}"
                                                           {{ is_array(old('data_tambahan.' . $field->field_key)) && in_array($opt, old('data_tambahan.' . $field->field_key)) ? 'checked' : '' }}
                                                           class="w-4 h-4 text-amber-500 rounded">
                                                    <span>{{ $opt }}</span>
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif

                                @if($field->help_text)
                                    <p class="text-[11px] text-slate-400 mt-1">{{ $field->help_text }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- SECTION 4: UPLOAD BERKAS --}}
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-amber-500 uppercase tracking-wider border-b border-amber-100 pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <span>4. Upload Berkas Pendukung (Opsional)</span>
                </h3>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Upload Pas Foto / Ijazah / KK (PDF, JPG, PNG max 5MB)
                    </label>
                    <input type="file" name="berkas" accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 cursor-pointer">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="submit"
                    class="w-full sm:w-auto px-10 py-4 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-sm rounded-xl transition-all shadow-lg shadow-amber-500/20 flex items-center gap-2 justify-center uppercase tracking-wider">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>Kirim Pendaftaran PPDB</span>
                </button>
            </div>
        </form>
    </div>
    @else
    <div class="bg-amber-500/10 border border-amber-300/40 rounded-[28px] p-8 text-center text-amber-800 space-y-3">
        <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 mx-auto flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <div class="font-bold text-base">Pendaftaran PPDB Saat Ini Ditutup</div>
        <p class="text-xs text-slate-600 max-w-md mx-auto">
            Silakan hubungi panitia PPDB sekolah atau pantau pengumuman resmi di website ini untuk jadwal pendaftaran gelombang berikutnya.
        </p>
    </div>
    @endif

    {{-- Kontak Panitia --}}
    <div class="bg-white rounded-[28px] border border-slate-200 p-8 shadow-sm grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
        <div class="p-4 flex flex-col items-center">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="font-bold text-slate-800 text-sm">Alamat Sekolah</div>
            <div class="text-xs text-slate-500 mt-1">{{ $setting->alamat ?? 'MA Al Ikhlas' }}</div>
        </div>
        <div class="p-4 border-y sm:border-y-0 sm:border-x border-slate-100 flex flex-col items-center">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1.01 1.01 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <div class="font-bold text-slate-800 text-sm">Panitia PPDB</div>
            <div class="text-xs text-slate-500 mt-1">{{ $setting->telepon ?? '-' }}</div>
        </div>
        <div class="p-4 flex flex-col items-center">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div class="font-bold text-slate-800 text-sm">Email Informasi</div>
            <div class="text-xs text-slate-500 mt-1">{{ $setting->email ?? '-' }}</div>
        </div>
    </div>
</div>
@endsection
