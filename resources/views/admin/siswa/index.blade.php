@extends('admin.layouts.app')

@section('title', 'Manajemen Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')
<div class="space-y-5">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800">Manajemen Data Siswa</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftarkan Email & NIS Siswa untuk izin registrasi akun mandiri.</p>
        </div>
        <a href="{{ route('admin.siswa.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-emerald-600/20 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Siswa Baru (Whitelist Email)
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Filter Card --}}
    <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <select name="kelas" onchange="this.form.submit()" class="px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelases as $k)
                    <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>

            <div class="relative flex-1 sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIS, atau email..."
                       class="w-full pl-9 pr-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-[5px] text-xs sm:text-sm font-semibold hover:bg-slate-700 transition-colors">
                Filter
            </button>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-[5px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 uppercase text-[11px] font-bold text-slate-500">
                    <tr>
                        <th class="px-5 py-3.5">NIS / NISN</th>
                        <th class="px-5 py-3.5">Nama Siswa</th>
                        <th class="px-5 py-3.5">Email Terdaftar</th>
                        <th class="px-5 py-3.5">Kelas</th>
                        <th class="px-5 py-3.5">Status Registrasi</th>
                        <th class="px-5 py-3.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswa as $s)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-5 py-4 font-mono text-slate-700">
                            <div>{{ $s->nis }}</div>
                            <div class="text-[10px] text-slate-400">{{ $s->nisn ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-4 font-semibold text-slate-800">
                            {{ $s->nama_lengkap }}
                            <span class="ml-1 text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-500">{{ $s->jenis_kelamin }}</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600 font-mono text-xs">{{ $s->email }}</td>
                        <td class="px-5 py-4 font-bold text-emerald-700">{{ $s->kelas }}</td>
                        <td class="px-5 py-4">
                            @if($s->status === 'aktif')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                </span>
                                @if($s->user)
                                    <div class="text-[10px] text-slate-500 font-mono mt-0.5">@ {{ $s->user->username }}</div>
                                @endif
                            @elseif($s->status === 'pending_register')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Whitelisted (Belum Login)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                    {{ ucfirst($s->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="openResetModal('{{ $s->id_siswa }}', '{{ addslashes($s->nama_lengkap) }}', '{{ $s->user->username ?? '-' }}')"
                                        class="px-2.5 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold transition-colors flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    Reset Password
                                </button>
                                <a href="{{ route('admin.siswa.edit', $s->id_siswa) }}"
                                   class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-emerald-100 text-slate-600 hover:text-emerald-700 font-semibold transition-colors">
                                    Edit
                                </a>
                                <form action="{{ route('admin.siswa.delete', $s->id_siswa) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data siswa ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-semibold transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada data siswa. Silakan klik "Tambah Siswa Baru" untuk menambahkan data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($siswa->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $siswa->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Reset Password --}}
<div id="resetModal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-[5px] p-6 max-w-md w-full shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                Reset Password Akun Siswa
            </h3>
            <button onclick="closeResetModal()" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
        </div>

        <form id="resetForm" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase">Nama Siswa</label>
                <p id="modalSiswaName" class="text-sm font-bold text-slate-800 mt-1"></p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase">Username Akun</label>
                <p id="modalUsername" class="text-xs font-mono text-emerald-700 bg-emerald-50 px-2 py-1 rounded inline-block mt-1"></p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Password Baru *</label>
                <input type="password" name="password" required minlength="6" placeholder="Masukkan password baru..."
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-500 bg-slate-50">
                <p class="text-[11px] text-slate-400 mt-1">Minimal 6 karakter. Jika belum ada akun User, akun akan dibuatkan otomatis.</p>
            </div>

            <div class="pt-3 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="closeResetModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md shadow-amber-600/20">Simpan Password Baru</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openResetModal(siswaId, siswaName, username) {
        const form = document.getElementById('resetForm');
        form.action = '/admin/siswa/' + siswaId + '/reset-password';
        document.getElementById('modalSiswaName').innerText = siswaName;
        document.getElementById('modalUsername').innerText = username !== '-' ? username : '(Akan dibuatkan akun baru otomatis)';
        document.getElementById('resetModal').classList.remove('hidden');
    }

    function closeResetModal() {
        document.getElementById('resetModal').classList.add('hidden');
    }
</script>
@endsection
