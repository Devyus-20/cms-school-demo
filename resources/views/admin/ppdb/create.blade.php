@extends('admin.layouts.app')

@section('title', 'Tambah Pendaftar Offline PPDB')
@section('page-title', 'Tambah Pendaftar PPDB (Offline)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header / Back link --}}
    <div class="flex items-center justify-between bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.ppdb.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-800">Form Input Pendaftar Offline PPDB</h1>
                <p class="text-xs text-slate-500">Inputkan data calon siswa baru yang mendaftar secara tatap muka / offline di sekolah.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                PPDB Ditandai Aktif
            </span>
        </div>
    </div>

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.ppdb.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        {{-- Section 1: Data Pribadi Siswa --}}
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2 flex items-center gap-2">
                <span>👤</span> Data Pribadi Calon Siswa
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Nama lengkap sesuai ijazah/KK"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
                    @error('nama_lengkap') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">NISN (Opsional)</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="Nomor Induk Siswa Nasional"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
                    @error('nisn') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Agama <span class="text-red-500">*</span></label>
                    <select name="agama" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
                        <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                        <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                        <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                        <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                    </select>
                    @error('agama') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Kota/Kabupaten kelahiran"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
                    @error('tempat_lahir') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
                    @error('tanggal_lahir') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Tempat Tinggal <span class="text-red-500">*</span></label>
                <textarea name="alamat" rows="2" required placeholder="Alamat domisili lengkap calon siswa..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">{{ old('alamat') }}</textarea>
                @error('alamat') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Section 2: Asal Sekolah & Kontak Orang Tua --}}
        <div class="space-y-4 pt-2 border-t border-slate-100">
            <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2 flex items-center gap-2">
                <span>🏫</span> Sekolah Asal & Kontak Orang Tua
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Sekolah Asal (SMP/MTs) <span class="text-red-500">*</span></label>
                    <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal') }}" required placeholder="Misal: SMP Negeri 1..."
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
                    @error('sekolah_asal') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilihan Jurusan (Opsional)</label>
                    <select name="jurusan" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
                        <option value="MIPA / IPA" {{ old('jurusan') == 'MIPA / IPA' ? 'selected' : '' }}>MIPA / IPA</option>
                        <option value="IPS" {{ old('jurusan') == 'IPS' ? 'selected' : '' }}>IPS</option>
                        <option value="Keagamaan / Agama" {{ old('jurusan') == 'Keagamaan / Agama' ? 'selected' : '' }}>Keagamaan / Agama</option>
                        <option value="Bahasa" {{ old('jurusan') == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                    </select>
                    @error('jurusan') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Orang Tua / Wali <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_orang_tua" value="{{ old('nama_orang_tua') }}" required placeholder="Nama ayah/ibu/wali"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
                    @error('nama_orang_tua') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">No. Handphone / WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
                    @error('no_hp') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Siswa (Opsional)</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
                    @error('email') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Upload Berkas / Dokumen (Opsional)</label>
                    <input type="file" name="berkas" accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200">
                    <p class="text-[10px] text-slate-400 mt-1">Format: PDF/JPG/PNG (Maksimal 5MB)</p>
                    @error('berkas') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        @if(isset($customFields) && $customFields->count() > 0)
            {{-- Section Kustom: Data Tambahan Khusus Sekolah --}}
            <div class="space-y-4 pt-2 border-t border-slate-100">
                <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2 flex items-center gap-2">
                    <span>📋</span> Data Tambahan Khusus Sekolah (Dynamic Fields)
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($customFields as $field)
                        <div class="{{ $field->tipe === 'textarea' ? 'md:col-span-2' : '' }}">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                {{ $field->label }}
                                @if($field->is_required) <span class="text-red-500">*</span> @endif
                            </label>

                            @if($field->tipe === 'text')
                                <input type="text" name="data_tambahan[{{ $field->field_key }}]"
                                       value="{{ old('data_tambahan.' . $field->field_key) }}"
                                       placeholder="{{ $field->placeholder }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">

                            @elseif($field->tipe === 'number')
                                <input type="number" name="data_tambahan[{{ $field->field_key }}]"
                                       value="{{ old('data_tambahan.' . $field->field_key) }}"
                                       placeholder="{{ $field->placeholder }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">

                            @elseif($field->tipe === 'textarea')
                                <textarea name="data_tambahan[{{ $field->field_key }}]" rows="2"
                                          placeholder="{{ $field->placeholder }}"
                                          {{ $field->is_required ? 'required' : '' }}
                                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">{{ old('data_tambahan.' . $field->field_key) }}</textarea>

                            @elseif($field->tipe === 'date')
                                <input type="date" name="data_tambahan[{{ $field->field_key }}]"
                                       value="{{ old('data_tambahan.' . $field->field_key) }}"
                                       {{ $field->is_required ? 'required' : '' }}
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">

                            @elseif($field->tipe === 'select')
                                <select name="data_tambahan[{{ $field->field_key }}]" {{ $field->is_required ? 'required' : '' }}
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
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
                                            <label class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 cursor-pointer hover:bg-white">
                                                <input type="checkbox" name="data_tambahan[{{ $field->field_key }}][]" value="{{ $opt }}"
                                                       {{ is_array(old('data_tambahan.' . $field->field_key)) && in_array($opt, old('data_tambahan.' . $field->field_key)) ? 'checked' : '' }}
                                                       class="w-4 h-4 text-emerald-600 rounded">
                                                <span>{{ $opt }}</span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                            @if($field->help_text)
                                <p class="text-[10px] text-slate-400 mt-1">{{ $field->help_text }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Section 3: Status & Catatan Pendaftaran Offline --}}
        <div class="space-y-4 pt-2 border-t border-slate-100">
            <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2 flex items-center gap-2">
                <span>⚙️</span> Status & Catatan Pendaftaran
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Pendaftaran Awal <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl border border-emerald-300 bg-emerald-50 text-xs font-bold text-emerald-900 outline-none focus:bg-white focus:border-emerald-500">
                        <option value="diterima" {{ old('status', 'diterima') == 'diterima' ? 'selected' : '' }}>✅ Langsung Diterima (Offline)</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>⏳ Menunggu Verifikasi (Pending)</option>
                        <option value="ditolak" {{ old('status') == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                    </select>
                    @error('status') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Tambahan (Opsional)</label>
                    <input type="text" name="catatan" value="{{ old('catatan', 'Pendaftaran manual / offline di lokasi sekolah.') }}" placeholder="Catatan khusus dari petugas..."
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-400">
                    @error('catatan') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Submit Action --}}
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.ppdb.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors shadow-md shadow-emerald-600/20 cursor-pointer flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Data Pendaftar Offline
            </button>
        </div>

    </form>
</div>
@endsection
