@extends('admin.layouts.app')

@section('title', 'Data Pendaftar PPDB')
@section('page-title', 'Pengelolaan Pendaftar PPDB Online')

@section('content')
<div class="space-y-6">

    @php $ppdbOpen = isset($setting) && $setting->ppdb_aktif; @endphp

    @if(!$ppdbOpen)
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-semibold flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                <span><strong>Status Pendaftaran PPDB Nonaktif (Ditutup):</strong> Fitur pendaftaran online & manual offline saat ini dikunci. Untuk membuka pendaftaran dan menambah data offline, aktifkan centang PPDB di menu Settings.</span>
            </div>
            <a href="{{ route('admin.settings') }}" class="px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shrink-0 transition-colors shadow-sm">
                Buka Setting PPDB &rarr;
            </a>
        </div>
    @endif

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <a href="{{ route('admin.ppdb.index') }}" class="bg-white p-5 rounded-[5px] border border-slate-200 shadow-sm flex items-center space-x-4 hover:border-emerald-500 transition-all">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium">Total Pendaftar</div>
                <div class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.ppdb.index', ['status' => 'pending']) }}" class="bg-white p-5 rounded-[5px] border border-slate-200 shadow-sm flex items-center space-x-4 hover:border-amber-500 transition-all">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium">Menunggu Verifikasi</div>
                <div class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.ppdb.index', ['status' => 'diterima']) }}" class="bg-white p-5 rounded-[5px] border border-slate-200 shadow-sm flex items-center space-x-4 hover:border-emerald-500 transition-all">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium">Diterima</div>
                <div class="text-2xl font-bold text-emerald-600">{{ $stats['diterima'] }}</div>
            </div>
        </a>

        <a href="{{ route('admin.ppdb.index', ['status' => 'ditolak']) }}" class="bg-white p-5 rounded-[5px] border border-slate-200 shadow-sm flex items-center space-x-4 hover:border-red-500 transition-all">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold text-xl shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="text-xs text-slate-500 font-medium">Ditolak</div>
                <div class="text-2xl font-bold text-red-600">{{ $stats['ditolak'] }}</div>
            </div>
        </a>
    </div>

    {{-- Filter & Search Header --}}
    <div class="bg-white p-6 rounded-[5px] border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        {{-- Filter Tabs --}}
        <div class="flex items-center gap-2 overflow-x-auto whitespace-nowrap pb-2 md:pb-0 text-xs font-semibold scrollbar-none">
            <a href="{{ route('admin.ppdb.index') }}"
                class="px-4 py-2 rounded-xl transition-all shrink-0 {{ !request('status') ? 'bg-slate-900 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua ({{ $stats['total'] }})
            </a>
            <a href="{{ route('admin.ppdb.index', ['status' => 'pending']) }}"
                class="px-4 py-2 rounded-xl transition-all shrink-0 {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-md' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                Pending ({{ $stats['pending'] }})
            </a>
            <a href="{{ route('admin.ppdb.index', ['status' => 'diterima']) }}"
                class="px-4 py-2 rounded-xl transition-all shrink-0 {{ request('status') === 'diterima' ? 'bg-emerald-600 text-white shadow-md' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                Diterima ({{ $stats['diterima'] }})
            </a>
            <a href="{{ route('admin.ppdb.index', ['status' => 'ditolak']) }}"
                class="px-4 py-2 rounded-xl transition-all {{ request('status') === 'ditolak' ? 'bg-red-600 text-white shadow-md' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                Ditolak ({{ $stats['ditolak'] }})
            </a>
        </div>

        {{-- Search & Actions Box --}}
            <a href="{{ route('admin.ppdb.fields.index') }}"
               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Pengaturan Form (Tambah Data)</span>
            </a>
            @if($ppdbOpen)
                <a href="{{ route('admin.ppdb.create') }}"
                   class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Pendaftar Offline</span>
                </a>
            @else
                <button type="button" disabled title="Pendaftaran PPDB saat ini sedang ditutup di Pengaturan Website. Aktifkan PPDB di menu Setting terlebih dahulu."
                        class="px-4 py-2 bg-slate-200 text-slate-400 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 shrink-0 cursor-not-allowed border border-slate-300">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>+ Tambah Offline (PPDB Ditutup)</span>
                </button>
            @endif

            <form method="GET" action="{{ route('admin.ppdb.index') }}" class="flex items-center gap-2">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, no reg, WA, sekolah..."
                    class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none w-full md:w-64">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition-colors shadow-sm">
                    Cari
                </button>
                @if(request('q'))
                    <a href="{{ route('admin.ppdb.index', array_filter(['status' => request('status')])) }}" class="px-3 py-2 bg-slate-100 text-slate-500 rounded-xl text-xs hover:bg-slate-200">
                        Reset
                    </a>
                @endif
            </form>

            <a href="{{ route('admin.ppdb.print-all', ['status' => request('status'), 'q' => request('q')]) }}" target="_blank"
                class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5 shrink-0">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Rekap (PDF)</span>
            </a>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-[5px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No. Pendaftaran</th>
                        <th class="px-6 py-4">Nama Calon Siswa</th>
                        <th class="px-6 py-4">L/P</th>
                        <th class="px-6 py-4">Sekolah Asal</th>
                        <th class="px-6 py-4">No. HP / WA</th>
                        <th class="px-6 py-4">Jurusan</th>
                        <th class="px-6 py-4">Tanggal Daftar</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendaftar as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $item->no_pendaftaran }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 text-sm">{{ $item->nama_lengkap }}</div>
                            <div class="text-[11px] text-slate-400">NISN: {{ $item->nisn ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $item->jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                {{ $item->jenis_kelamin }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">
                            {{ $item->sekolah_asal ?? '-' }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-emerald-700">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_hp) }}" target="_blank" class="hover:underline flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                <span>{{ $item->no_hp }}</span>
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-bold text-slate-700 text-[11px]">
                                {{ $item->jurusan ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $item->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($item->status === 'pending')
                                <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 font-bold text-[10px] uppercase tracking-wider">Pending</span>
                            @elseif($item->status === 'diterima')
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px] uppercase tracking-wider">Diterima</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 font-bold text-[10px] uppercase tracking-wider">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Tombol Cetak Perorangan --}}
                                <a href="{{ route('admin.ppdb.print-single', $item->id) }}" target="_blank" title="Cetak Bukti & Biodata Pendaftar"
                                    class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>

                                {{-- Tombol Detail --}}
                                <button type="button" onclick="showDetail({{ json_encode($item) }})"
                                    class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition-colors">
                                    Detail
                                </button>

                                {{-- Tombol Verifikasi Status --}}
                                <button type="button" onclick="openStatusModal({{ json_encode($item) }})"
                                    class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold transition-colors">
                                    Status
                                </button>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.ppdb.delete', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pendaftar {{ $item->nama_lengkap }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                            Belum ada pendaftar PPDB yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pendaftar->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $pendaftar->links() }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL DETAIL PENDAFTAR --}}
<div id="detailModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 space-y-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <span id="detailNoReg" class="text-xs font-bold text-emerald-600 uppercase tracking-wider block"></span>
                <h3 id="detailNama" class="text-xl font-bold text-slate-800"></h3>
            </div>
            <button onclick="closeDetail()" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl">✕</button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <span class="text-slate-400 font-semibold block">Jenis Kelamin</span>
                <span id="detailJK" class="font-bold text-slate-800"></span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <span class="text-slate-400 font-semibold block">NISN</span>
                <span id="detailNisn" class="font-bold text-slate-800"></span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <span class="text-slate-400 font-semibold block">Tempat, Tanggal Lahir</span>
                <span id="detailTTL" class="font-bold text-slate-800"></span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <span class="text-slate-400 font-semibold block">Agama</span>
                <span id="detailAgama" class="font-bold text-slate-800"></span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <span class="text-slate-400 font-semibold block">Sekolah Asal</span>
                <span id="detailSekolah" class="font-bold text-slate-800"></span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <span class="text-slate-400 font-semibold block">Pilihan Jurusan</span>
                <span id="detailJurusan" class="font-bold text-slate-800"></span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <span class="text-slate-400 font-semibold block">Nama Orang Tua / Wali</span>
                <span id="detailOrangTua" class="font-bold text-slate-800"></span>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                <span class="text-slate-400 font-semibold block">No. HP / WhatsApp</span>
                <span id="detailNoHp" class="font-bold text-emerald-700"></span>
            </div>
            <div class="sm:col-span-2 p-3 bg-slate-50 rounded-xl space-y-1">
                <span class="text-slate-400 font-semibold block">Alamat Rumah</span>
                <span id="detailAlamat" class="font-medium text-slate-800"></span>
            </div>
            <div id="detailBerkasContainer" class="sm:col-span-2 p-3 bg-slate-50 rounded-xl space-y-1 hidden">
                <span class="text-slate-400 font-semibold block">Berkas Upload</span>
                <a id="detailBerkasLink" href="#" target="_blank" class="inline-flex items-center gap-1 font-bold text-emerald-700 hover:underline">
                    📄 Lihat / Download Berkas Pendaftaran
                </a>
            </div>
            <div id="detailCustomContainer" class="sm:col-span-2 p-3.5 bg-indigo-50/60 rounded-xl space-y-2 border border-indigo-100 hidden">
                <span class="text-indigo-900 font-extrabold block text-[11px] uppercase tracking-wider">📋 Data Tambahan Khusus Sekolah</span>
                <div id="detailCustomList" class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs"></div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
            <a id="detailPrintBtn" href="#" target="_blank" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Biodata Siswa Ini</span>
            </a>

            <button onclick="closeDetail()" class="px-6 py-2.5 bg-slate-800 text-white text-xs font-bold rounded-xl hover:bg-slate-900">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- MODAL UBAH STATUS --}}
<div id="statusModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-lg font-bold text-slate-800">Ubah Status Pendaftaran</h3>
            <button onclick="closeStatusModal()" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <form id="statusForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Status Pendaftaran
                </label>
                <select id="modalStatusSelect" name="status" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="pending">⏳ Pending (Menunggu Verifikasi)</option>
                    <option value="diterima">✅ Diterima</option>
                    <option value="ditolak">❌ Ditolak</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Catatan / Pesan untuk Pendaftar (Opsional)
                </label>
                <textarea id="modalCatatan" name="catatan" rows="3" placeholder="Contoh: Berkas lengkap, silakan daftar ulang pada tanggal 10 Agustus."
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeStatusModal()" class="px-5 py-2.5 bg-slate-100 text-slate-600 text-xs font-semibold rounded-xl hover:bg-slate-200">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showDetail(item) {
    document.getElementById('detailNoReg').innerText = item.no_pendaftaran;
    document.getElementById('detailNama').innerText = item.nama_lengkap;
    document.getElementById('detailJK').innerText = item.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    document.getElementById('detailNisn').innerText = item.nisn || '-';
    document.getElementById('detailTTL').innerText = (item.tempat_lahir || '-') + ', ' + (item.tanggal_lahir || '-');
    document.getElementById('detailAgama').innerText = item.agama || '-';
    document.getElementById('detailSekolah').innerText = item.sekolah_asal || '-';
    document.getElementById('detailJurusan').innerText = item.jurusan || '-';
    document.getElementById('detailOrangTua').innerText = item.nama_orang_tua || '-';
    document.getElementById('detailNoHp').innerText = item.no_hp || '-';
    document.getElementById('detailAlamat').innerText = item.alamat || '-';
    document.getElementById('detailPrintBtn').href = '/admin/ppdb/' + item.id + '/print';

    var berkasContainer = document.getElementById('detailBerkasContainer');
    if (item.berkas) {
        document.getElementById('detailBerkasLink').href = '/storage/' + item.berkas;
        berkasContainer.classList.remove('hidden');
    } else {
        berkasContainer.classList.add('hidden');
    }

    var customContainer = document.getElementById('detailCustomContainer');
    var customList = document.getElementById('detailCustomList');
    customList.innerHTML = '';

    if (item.data_tambahan && typeof item.data_tambahan === 'object' && Object.keys(item.data_tambahan).length > 0) {
        var hasValid = false;
        for (var k in item.data_tambahan) {
            var val = item.data_tambahan[k];
            if (val !== null && val !== undefined && val !== '') {
                hasValid = true;
                var valStr = Array.isArray(val) ? val.join(', ') : val;
                var keyFormatted = k.replace(/_/g, ' ').toUpperCase();
                customList.innerHTML += '<div class="p-2 bg-white rounded-lg border border-slate-200"><span class="text-slate-500 font-semibold block text-[10px]">' + keyFormatted + '</span><span class="font-bold text-slate-800">' + valStr + '</span></div>';
            }
        }
        if (hasValid) {
            customContainer.classList.remove('hidden');
        } else {
            customContainer.classList.add('hidden');
        }
    } else {
        customContainer.classList.add('hidden');
    }

    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetail() {
    document.getElementById('detailModal').classList.add('hidden');
}

function openStatusModal(item) {
    var form = document.getElementById('statusForm');
    form.action = '/admin/ppdb/' + item.id + '/status';
    document.getElementById('modalStatusSelect').value = item.status;
    document.getElementById('modalCatatan').value = item.catatan || '';
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}
</script>
@endsection
