@extends('admin.layouts.app')

@section('title', 'Kelola Peserta Terdaftar')
@section('page-title', 'Peserta Terdaftar: ' . $exam->judul)

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
            <h1 class="text-xl font-bold text-slate-800 mt-1">Daftar Peserta Terdaftar (Whitelist)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Ujian: <strong>{{ $exam->judul }}</strong> | Status Pembatasan: <strong class="{{ $exam->batasi_peserta ? 'text-emerald-600' : 'text-amber-600' }}">{{ $exam->batasi_peserta ? 'Aktif (Dibatasi)' : 'Terbuka Untuk Umum' }}</strong></p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form Side --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Form Single --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Tambah Peserta Tunggal</h2>
                <form action="{{ route('admin.exams.participants.store', $exam) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIS / Email / NIK <span class="text-red-500">*</span></label>
                        <input type="text" name="nis_email" required placeholder="Contoh: 12345678"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:border-emerald-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap (Opsional)</label>
                        <input type="text" name="nama" placeholder="Nama siswa..."
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:border-emerald-400 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kelas (Opsional)</label>
                        <input type="text" name="kelas" placeholder="Contoh: X IPA 1"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:border-emerald-400 outline-none">
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors cursor-pointer">
                        + Tambah Peserta
                    </button>
                </form>
            </div>

            {{-- Form Bulk --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <h2 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Import Banyak Peserta (Bulk Paste)</h2>
                <form action="{{ route('admin.exams.participants.store', $exam) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Daftar NIS / Nama per Baris</label>
                        <textarea name="bulk_data" rows="5" placeholder="Format per baris: NIS, Nama, Kelas&#10;Contoh:&#10;1001, Budi Santoso, X IPA 1&#10;1002, Ani Wijaya, X IPA 1"
                                  class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono focus:border-emerald-400 outline-none"></textarea>
                        <p class="text-[11px] text-slate-400 mt-1">Pisahkan NIS, Nama, dan Kelas menggunakan koma (,).</p>
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-colors cursor-pointer">
                        📥 Import Daftar Peserta
                    </button>
                </form>
            </div>
        </div>

        {{-- Table List Side --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800">Daftar Peserta Terdaftar (Total: {{ $participants->total() }})</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 font-bold uppercase text-slate-500">
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">NIS / Email</th>
                                <th class="py-3 px-4">Nama Peserta</th>
                                <th class="py-3 px-4">Kelas</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($participants as $index => $p)
                                <tr class="hover:bg-slate-50/80">
                                    <td class="py-3 px-4 text-slate-400 font-bold">{{ $participants->firstItem() + $index }}</td>
                                    <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $p->nis_email }}</td>
                                    <td class="py-3 px-4 font-semibold text-slate-700">{{ $p->nama ?? '-' }}</td>
                                    <td class="py-3 px-4"><span class="px-2 py-0.5 bg-slate-100 rounded text-slate-600 font-bold">{{ $p->kelas ?? '-' }}</span></td>
                                    <td class="py-3 px-4 text-right">
                                        <form action="{{ route('admin.exams.participants.delete', $p) }}" method="POST" class="inline" onsubmit="return confirm('Hapus peserta dari whitelist?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded text-red-500 hover:bg-red-50 cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400">
                                        Belum ada peserta yang terdaftar dalam whitelist ujian ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($participants->hasPages())
                    <div class="p-3 border-t border-slate-100">
                        {{ $participants->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
