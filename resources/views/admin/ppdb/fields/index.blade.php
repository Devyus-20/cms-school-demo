@extends('admin.layouts.app')

@section('title', 'Pengaturan Field Kustom PPDB')
@section('page-title', 'Pengaturan Data Tambahan Formulir PPDB')

@section('content')
<div class="space-y-6">

    {{-- Notification Alerts --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Top Action Header --}}
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Dynamic Form Fields PPDB
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Kelola field inputan kustom sesuai kebutuhan khusus sekolah Anda (misal: Ukuran Seragam, No. KKS/PKH/KIP, Prestasi, Pekerjaan Wali, dll).
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('admin.ppdb.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all border border-slate-200">
                &larr; Kembali ke Data PPDB
            </a>
            <button onclick="openCreateModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Tambah Field Baru</span>
            </button>
        </div>
    </div>

    {{-- Fields Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-xs font-extrabold uppercase text-slate-700 tracking-wider">
                Daftar Field Kustom Tambahan ({{ $fields->count() }} Field)
            </h3>
            <span class="text-[11px] text-slate-500">
                Field aktif akan otomatis tampil pada Formulir PPDB Online & Cetakan PDF.
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-3 w-12 text-center">Urutan</th>
                        <th class="px-4 py-3">Nama Label Input</th>
                        <th class="px-4 py-3">Tipe Inputan</th>
                        <th class="px-4 py-3">Pilihan / Opsi Dropdown</th>
                        <th class="px-4 py-3 text-center">Status Wajib</th>
                        <th class="px-4 py-3 text-center">Status Form</th>
                        <th class="px-4 py-3 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($fields as $f)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-4 py-3 text-center font-bold text-slate-600">
                                #{{ $f->urutan }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-900 text-sm">{{ $f->label }}</div>
                                <div class="text-[10px] font-mono text-slate-400 mt-0.5">key: {{ $f->field_key }}</div>
                                @if($f->help_text)
                                    <div class="text-[11px] text-slate-500 italic mt-0.5">{{ $f->help_text }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wide
                                    {{ $f->tipe === 'select' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $f->tipe === 'checkbox' ? 'bg-pink-100 text-pink-800' : '' }}
                                    {{ $f->tipe === 'textarea' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $f->tipe === 'date' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $f->tipe === 'number' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $f->tipe === 'text' ? 'bg-slate-100 text-slate-800' : '' }}">
                                    {{ $f->tipe }}
                                </span>
                            </td>
                            <td class="px-4 py-3 max-w-xs">
                                @if(is_array($f->options) && count($f->options) > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($f->options as $opt)
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-medium border border-slate-200">
                                                {{ $opt }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400 text-[11px] italic">— Tidak Ada —</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($f->is_required)
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800">Wajib Diisi</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">Opsional</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('admin.ppdb.fields.toggle', $f->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-xl text-[10px] font-extrabold transition-all border
                                        {{ $f->aktif ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}">
                                        {{ $f->aktif ? '✓ Aktif Tampil' : '✕ Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button onclick='openEditModal(@json($f))' class="p-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Edit Field">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form action="{{ route('admin.ppdb.fields.delete', $f->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus field kustom \'{{ $f->label }}\'?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus Field">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400 italic">
                                Belum ada field kustom tambahan yang dibuat. Klik tombol "+ Tambah Field Baru" di atas untuk menambahkan data khusus yang dibutuhkan sekolah.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modal Create Field --}}
<div id="createModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 my-8">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-900">Tambah Field Kustom Baru</h3>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
        </div>

        <form action="{{ route('admin.ppdb.fields.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Label Input <span class="text-red-500">*</span></label>
                <input type="text" name="label" required placeholder="Contoh: Ukuran Seragam / No. KKS"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-indigo-500 transition-all">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe Inputan <span class="text-red-500">*</span></label>
                    <select name="tipe" id="createTipeSelect" onchange="toggleOptionsVisibility('create')" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-800 outline-none focus:bg-white focus:border-indigo-500 transition-all">
                        <option value="text">Teks Singkat (Text)</option>
                        <option value="number">Angka (Number)</option>
                        <option value="textarea">Teks Panjang (Textarea)</option>
                        <option value="select">Pilihan Pilihan Dropdown (Select)</option>
                        <option value="checkbox">Centang Pilihan (Checkbox)</option>
                        <option value="date">Tanggal (Date)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Urutan Tampil</label>
                    <input type="number" name="urutan" value="0" min="0"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-indigo-500 transition-all">
                </div>
            </div>

            <div id="createOptionsContainer" class="hidden">
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilihan Opsi (1 Baris = 1 Pilihan) <span class="text-red-500">*</span></label>
                <textarea name="options_raw" rows="3" placeholder="S&#10;M&#10;L&#10;XL&#10;XXL"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono text-slate-800 outline-none focus:bg-white focus:border-indigo-500 transition-all"></textarea>
                <p class="text-[11px] text-slate-400 mt-1">Tulis setiap pilihan pada baris baru terpisah.</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Placeholder (Contoh Isian)</label>
                    <input type="text" name="placeholder" placeholder="Misal: Masukkan nomor 16 digit"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-indigo-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Petunjuk / Text Bantuan</label>
                    <input type="text" name="help_text" placeholder="Misal: Kosongkan bila tidak punya"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-indigo-500 transition-all">
                </div>
            </div>

            <div class="flex items-center gap-6 pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_required" value="1" class="w-4 h-4 text-indigo-600 rounded">
                    <span class="text-xs font-bold text-slate-800">Wajib Diisi Calon Siswa</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="aktif" value="1" checked class="w-4 h-4 text-emerald-600 rounded">
                    <span class="text-xs font-bold text-slate-800">Langsung Aktifkan</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md">Simpan Field</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Field --}}
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4 my-8">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-extrabold text-slate-900">Edit Field Kustom</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
        </div>

        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Label Input <span class="text-red-500">*</span></label>
                <input type="text" name="label" id="editLabel" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-indigo-500 transition-all">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tipe Inputan <span class="text-red-500">*</span></label>
                    <select name="tipe" id="editTipeSelect" onchange="toggleOptionsVisibility('edit')" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-800 outline-none focus:bg-white focus:border-indigo-500 transition-all">
                        <option value="text">Teks Singkat (Text)</option>
                        <option value="number">Angka (Number)</option>
                        <option value="textarea">Teks Panjang (Textarea)</option>
                        <option value="select">Pilihan Pilihan Dropdown (Select)</option>
                        <option value="checkbox">Centang Pilihan (Checkbox)</option>
                        <option value="date">Tanggal (Date)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nomor Urutan Tampil</label>
                    <input type="number" name="urutan" id="editUrutan" min="0"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-indigo-500 transition-all">
                </div>
            </div>

            <div id="editOptionsContainer" class="hidden">
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilihan Opsi (1 Baris = 1 Pilihan) <span class="text-red-500">*</span></label>
                <textarea name="options_raw" id="editOptionsRaw" rows="3"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-mono text-slate-800 outline-none focus:bg-white focus:border-indigo-500 transition-all"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Placeholder</label>
                    <input type="text" name="placeholder" id="editPlaceholder"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-indigo-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Petunjuk / Text Bantuan</label>
                    <input type="text" name="help_text" id="editHelpText"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-indigo-500 transition-all">
                </div>
            </div>

            <div class="flex items-center gap-6 pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_required" id="editIsRequired" value="1" class="w-4 h-4 text-indigo-600 rounded">
                    <span class="text-xs font-bold text-slate-800">Wajib Diisi Calon Siswa</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="aktif" id="editAktif" value="1" class="w-4 h-4 text-emerald-600 rounded">
                    <span class="text-xs font-bold text-slate-800">Status Aktif Tampil</span>
                </label>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
        document.getElementById('createModal').classList.add('flex');
        toggleOptionsVisibility('create');
    }
    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
        document.getElementById('createModal').classList.remove('flex');
    }

    function openEditModal(field) {
        document.getElementById('editForm').action = '/admin/ppdb/fields/' + field.id;
        document.getElementById('editLabel').value = field.label;
        document.getElementById('editTipeSelect').value = field.tipe;
        document.getElementById('editUrutan').value = field.urutan;
        document.getElementById('editPlaceholder').value = field.placeholder || '';
        document.getElementById('editHelpText').value = field.help_text || '';
        document.getElementById('editIsRequired').checked = !!field.is_required;
        document.getElementById('editAktif').checked = !!field.aktif;

        if (Array.isArray(field.options)) {
            document.getElementById('editOptionsRaw').value = field.options.join('\n');
        } else {
            document.getElementById('editOptionsRaw').value = '';
        }

        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
        toggleOptionsVisibility('edit');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    function toggleOptionsVisibility(mode) {
        var tipe = document.getElementById(mode + 'TipeSelect').value;
        var container = document.getElementById(mode + 'OptionsContainer');
        if (tipe === 'select' || tipe === 'checkbox') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endsection
