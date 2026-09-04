@extends('admin.layouts.app')

@section('title', 'Pusat Laporan & Rekapitulasi')
@section('page-title', 'Pusat Laporan')

@section('content')
@php
    $queryParams = request()->query();
    if ($type === 'ujian' && empty($queryParams['exam_id']) && isset($exams) && $exams->first()) {
        $queryParams['exam_id'] = $exams->first()->id_exam;
    }
    $printRoute = route('admin.reports.print.' . $type, $queryParams);
    $exportRoute = route('admin.reports.export.' . $type, $queryParams);
@endphp

<div class="space-y-5">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800">Pusat Laporan & Rekapitulasi Data</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pilih jenis laporan, terapkan filter pencarian, lalu cetak lembar resmi ber-KOP atau unduh format Excel/CSV.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ $exportRoute }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-[5px] bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Unduh Excel / CSV
            </a>

            <a href="{{ $printRoute }}" target="_blank"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-[5px] bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak / Simpan PDF
            </a>
        </div>
    </div>

    {{-- Kategori Selektor Laporan (Tabs) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        {{-- 1. Data Siswa --}}
        <a href="{{ route('admin.reports.index', ['type' => 'siswa']) }}"
           class="p-3.5 rounded-[5px] border transition-all flex flex-col items-center text-center
           {{ $type === 'siswa' ? 'bg-emerald-50 border-emerald-400 text-emerald-800 font-bold shadow-sm' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300' }}">
            <svg class="w-5 h-5 mb-1.5 {{ $type === 'siswa' ? 'text-emerald-700' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span class="text-xs font-bold leading-tight">Data Siswa</span>
            <span class="text-[10px] mt-0.5 {{ $type === 'siswa' ? 'text-emerald-700' : 'text-slate-400' }}">Buku Induk</span>
        </a>

        {{-- 2. Nilai & Ranking --}}
        <a href="{{ route('admin.reports.index', ['type' => 'nilai']) }}"
           class="p-3.5 rounded-[5px] border transition-all flex flex-col items-center text-center
           {{ $type === 'nilai' ? 'bg-emerald-50 border-emerald-400 text-emerald-800 font-bold shadow-sm' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300' }}">
            <svg class="w-5 h-5 mb-1.5 {{ $type === 'nilai' ? 'text-emerald-700' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="text-xs font-bold leading-tight">Nilai & Ranking</span>
            <span class="text-[10px] mt-0.5 {{ $type === 'nilai' ? 'text-emerald-700' : 'text-slate-400' }}">Rekap Kelas</span>
        </a>

        {{-- 3. Presensi --}}
        <a href="{{ route('admin.reports.index', ['type' => 'presensi']) }}"
           class="p-3.5 rounded-[5px] border transition-all flex flex-col items-center text-center
           {{ $type === 'presensi' ? 'bg-emerald-50 border-emerald-400 text-emerald-800 font-bold shadow-sm' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300' }}">
            <svg class="w-5 h-5 mb-1.5 {{ $type === 'presensi' ? 'text-emerald-700' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-xs font-bold leading-tight">Presensi Siswa</span>
            <span class="text-[10px] mt-0.5 {{ $type === 'presensi' ? 'text-emerald-700' : 'text-slate-400' }}">Rekap Bulanan</span>
        </a>

        {{-- 4. Ujian Online --}}
        <a href="{{ route('admin.reports.index', ['type' => 'ujian']) }}"
           class="p-3.5 rounded-[5px] border transition-all flex flex-col items-center text-center
           {{ $type === 'ujian' ? 'bg-emerald-50 border-emerald-400 text-emerald-800 font-bold shadow-sm' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300' }}">
            <svg class="w-5 h-5 mb-1.5 {{ $type === 'ujian' ? 'text-emerald-700' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs font-bold leading-tight">Hasil Ujian CBT</span>
            <span class="text-[10px] mt-0.5 {{ $type === 'ujian' ? 'text-emerald-700' : 'text-slate-400' }}">Berita Acara</span>
        </a>

        {{-- 5. PPDB --}}
        <a href="{{ route('admin.reports.index', ['type' => 'ppdb']) }}"
           class="p-3.5 rounded-[5px] border transition-all flex flex-col items-center text-center
           {{ $type === 'ppdb' ? 'bg-emerald-50 border-emerald-400 text-emerald-800 font-bold shadow-sm' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300' }}">
            <svg class="w-5 h-5 mb-1.5 {{ $type === 'ppdb' ? 'text-emerald-700' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span class="text-xs font-bold leading-tight">Pendaftar PPDB</span>
            <span class="text-[10px] mt-0.5 {{ $type === 'ppdb' ? 'text-emerald-700' : 'text-slate-400' }}">Calon Siswa</span>
        </a>

        {{-- 6. Activity Log --}}
        <a href="{{ route('admin.reports.index', ['type' => 'activity']) }}"
           class="p-3.5 rounded-[5px] border transition-all flex flex-col items-center text-center
           {{ $type === 'activity' ? 'bg-emerald-50 border-emerald-400 text-emerald-800 font-bold shadow-sm' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300' }}">
            <svg class="w-5 h-5 mb-1.5 {{ $type === 'activity' ? 'text-emerald-700' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs font-bold leading-tight">Log Aktivitas</span>
            <span class="text-[10px] mt-0.5 {{ $type === 'activity' ? 'text-emerald-700' : 'text-slate-400' }}">Audit Trail</span>
        </a>
    </div>

    {{-- Filter Card (Desain Seragam Data Siswa) --}}
    <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Filter Data: {{ strtoupper($type) }}
            </h3>
            <a href="{{ route('admin.reports.index', ['type' => $type]) }}" class="text-xs text-slate-500 hover:text-slate-800 font-medium underline">
                Reset Filter
            </a>
        </div>

        <form action="{{ route('admin.reports.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <input type="hidden" name="type" value="{{ $type }}">

            @if($type === 'siswa')
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Kelas</label>
                    <select name="kelas" class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelases as $k)
                            <option value="{{ $k }}" {{ request('kelas') == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Status Siswa</label>
                    <select name="status" class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        <option value="">-- Semua Status --</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Siswa Aktif</option>
                        <option value="pending_register" {{ request('status') == 'pending_register' ? 'selected' : '' }}>Pending Registrasi</option>
                        <option value="alumni" {{ request('status') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                        <option value="non_aktif" {{ request('status') == 'non_aktif' ? 'selected' : '' }}>Non-Aktif / Pindah</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        <option value="">-- Semua (L & P) --</option>
                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Pencarian</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIS / email..."
                               class="w-full pl-9 pr-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

            @elseif($type === 'nilai')
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Pilih Kelas</label>
                    <select name="kelas" class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        @foreach($kelases as $k)
                            <option value="{{ $k }}" {{ (request('kelas', $kelases->first()) == $k) ? 'selected' : '' }}>Kelas {{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Cari Siswa</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIS siswa..."
                               class="w-full pl-9 pr-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

            @elseif($type === 'presensi')
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Pilih Kelas</label>
                    <select name="kelas" class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        @foreach($kelases as $k)
                            <option value="{{ $k }}" {{ (request('kelas', $kelases->first()) == $k) ? 'selected' : '' }}>Kelas {{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Bulan</label>
                    @php
                        $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                    @endphp
                    <select name="bulan" class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        @foreach($months as $num => $m)
                            <option value="{{ $num }}" {{ (request('bulan', date('m')) == $num) ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Tahun</label>
                    <select name="tahun" class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        @for($y = date('Y') + 1; $y >= date('Y') - 4; $y--)
                            <option value="{{ $y }}" {{ (request('tahun', date('Y')) == $y) ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

            @elseif($type === 'ujian')
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Pilih Ujian / Ulangan Online</label>
                    <select name="exam_id" class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        @foreach($exams as $ex)
                            <option value="{{ $ex->id_exam }}" {{ (request('exam_id', $exams->first()?->id_exam) == $ex->id_exam) ? 'selected' : '' }}>
                                {{ $ex->judul }} ({{ strtoupper($ex->tipe_ujian ?? 'CBT') }} - KKM: {{ $ex->kkm ?? 75 }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Cari Peserta</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / NIS peserta..."
                               class="w-full pl-9 pr-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

            @elseif($type === 'ppdb')
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Status Pendaftar</label>
                    <select name="status" class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima (Lulus)</option>
                        <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Cadangan</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Tidak Diterima</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Pilihan Jurusan</label>
                    <select name="jurusan" class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        <option value="">-- Semua Jurusan --</option>
                        @foreach($jurusanList as $jur)
                            <option value="{{ $jur }}" {{ request('jurusan') == $jur ? 'selected' : '' }}>{{ $jur }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                </div>

            @elseif($type === 'activity')
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Pengguna / Admin</label>
                    <select name="user_id" class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                        <option value="">-- Semua Pengguna --</option>
                        @foreach($adminUsers as $u)
                            <option value="{{ $u->id_user }}" {{ request('user_id') == $u->id_user ? 'selected' : '' }}>{{ $u->name }} ({{ $u->username }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Jenis Aksi</label>
                    <input type="text" name="action" value="{{ request('action') }}" placeholder="Contoh: login, create, update..."
                           class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full px-3 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-800">
                </div>
            @endif

            <div>
                <button type="submit" class="w-full px-4 py-2 bg-slate-800 text-white rounded-[5px] text-xs sm:text-sm font-semibold hover:bg-slate-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Live Summary Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @if($type === 'siswa')
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Total Siswa</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-emerald-600 uppercase">Siswa Aktif</p>
                <p class="text-xl font-bold text-emerald-700 mt-1">{{ number_format($stats['aktif']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-amber-600 uppercase">Pending Registrasi</p>
                <p class="text-xl font-bold text-amber-700 mt-1">{{ number_format($stats['pending']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-indigo-600 uppercase">Alumni</p>
                <p class="text-xl font-bold text-indigo-700 mt-1">{{ number_format($stats['alumni']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Laki-laki (L)</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ number_format($stats['laki']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Perempuan (P)</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ number_format($stats['perempuan']) }}</p>
            </div>

        @elseif($type === 'nilai')
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Jumlah Siswa</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $stats['total_siswa'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Rata-rata Kelas</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $stats['rata_rata'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-emerald-600 uppercase">Nilai Tertinggi</p>
                <p class="text-xl font-bold text-emerald-700 mt-1">{{ $stats['tertinggi'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Nilai Terendah</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $stats['terendah'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm col-span-2">
                <p class="text-[11px] font-bold text-amber-600 uppercase">Peringkat 1 Kelas</p>
                <p class="text-sm font-bold text-slate-800 mt-1 truncate">{{ $stats['peringkat_1'] }}</p>
            </div>

        @elseif($type === 'presensi')
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Total Siswa</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $stats['total_siswa'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-emerald-600 uppercase">Total Hadir</p>
                <p class="text-xl font-bold text-emerald-700 mt-1">{{ $stats['total_hadir'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-indigo-600 uppercase">Total Izin</p>
                <p class="text-xl font-bold text-indigo-700 mt-1">{{ $stats['total_izin'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-amber-600 uppercase">Total Sakit</p>
                <p class="text-xl font-bold text-amber-700 mt-1">{{ $stats['total_sakit'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-rose-600 uppercase">Total Alpa</p>
                <p class="text-xl font-bold text-rose-700 mt-1">{{ $stats['total_alpa'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-emerald-600 uppercase">% Kehadiran</p>
                <p class="text-xl font-bold text-emerald-700 mt-1">{{ $stats['rata_kehadiran'] }}%</p>
            </div>

        @elseif($type === 'ujian')
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Total Peserta</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $stats['total_peserta'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-emerald-600 uppercase">Lulus KKM (>= {{ $stats['kkm'] }})</p>
                <p class="text-xl font-bold text-emerald-700 mt-1">{{ $stats['lulus'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-rose-600 uppercase">Remedial (< {{ $stats['kkm'] }})</p>
                <p class="text-xl font-bold text-rose-700 mt-1">{{ $stats['belum_lulus'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Rata-rata Skor</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $stats['rata_rata'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-emerald-600 uppercase">Skor Tertinggi</p>
                <p class="text-xl font-bold text-emerald-700 mt-1">{{ $stats['tertinggi'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Skor Terendah</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $stats['terendah'] }}</p>
            </div>

        @elseif($type === 'ppdb')
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Total Pendaftar</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-emerald-600 uppercase">Diterima</p>
                <p class="text-xl font-bold text-emerald-700 mt-1">{{ number_format($stats['accepted']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-cyan-600 uppercase">Terverifikasi</p>
                <p class="text-xl font-bold text-cyan-700 mt-1">{{ number_format($stats['verified']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-amber-600 uppercase">Pending</p>
                <p class="text-xl font-bold text-amber-700 mt-1">{{ number_format($stats['pending']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-indigo-600 uppercase">Cadangan</p>
                <p class="text-xl font-bold text-indigo-700 mt-1">{{ number_format($stats['reserved']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
                <p class="text-[11px] font-bold text-rose-600 uppercase">Ditolak</p>
                <p class="text-xl font-bold text-rose-700 mt-1">{{ number_format($stats['rejected']) }}</p>
            </div>

        @elseif($type === 'activity')
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm col-span-2">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Total Log Aktivitas</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm col-span-2">
                <p class="text-[11px] font-bold text-emerald-600 uppercase">Aktivitas Hari Ini</p>
                <p class="text-xl font-bold text-emerald-700 mt-1">{{ number_format($stats['today']) }}</p>
            </div>
            <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm col-span-2">
                <p class="text-[11px] font-bold text-slate-500 uppercase">Pengguna Aktif</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $stats['users_count'] }} Admin/User</p>
            </div>
        @endif
    </div>

    {{-- Table Card (Identik dengan Tabel Data Siswa) --}}
    <div class="bg-white rounded-[5px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
            <h3 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Pratinjau Data Laporan ({{ ucfirst($type) }})
            </h3>
            <span class="text-xs text-slate-500">Tabel Hasil Filter Aktif</span>
        </div>

        <div class="overflow-x-auto">
            @if($type === 'siswa')
                <table class="w-full text-xs sm:text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 uppercase text-[11px] font-bold text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5">NIS</th>
                            <th class="px-5 py-3.5">Nama Siswa</th>
                            <th class="px-5 py-3.5">L/P</th>
                            <th class="px-5 py-3.5">Kelas</th>
                            <th class="px-5 py-3.5">Angkatan</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Email & No. HP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $s)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-4 font-mono text-slate-700 font-bold">{{ $s->nis }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $s->nama_lengkap }}</td>
                                <td class="px-5 py-4 text-slate-600">
                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px] font-bold">{{ $s->jenis_kelamin }}</span>
                                </td>
                                <td class="px-5 py-4 font-bold text-emerald-700">{{ $s->kelas }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $s->tahun_masuk }}</td>
                                <td class="px-5 py-4">
                                    @if($s->status === 'aktif')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    @elseif($s->status === 'pending_register')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                        </span>
                                    @elseif($s->status === 'alumni')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Alumni
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                            {{ ucfirst($s->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    <div class="font-mono text-xs">{{ $s->email }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $s->telepon ?? '-' }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400">Tidak ada data siswa yang cocok dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if(method_exists($data, 'hasPages') && $data->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $data->withQueryString()->links() }}
                    </div>
                @endif

            @elseif($type === 'nilai')
                <table class="w-full text-xs sm:text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 uppercase text-[11px] font-bold text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5 w-16 text-center">Rank</th>
                            <th class="px-5 py-3.5">NIS</th>
                            <th class="px-5 py-3.5">Nama Siswa</th>
                            <th class="px-5 py-3.5 text-center">Tugas</th>
                            <th class="px-5 py-3.5 text-center">UH</th>
                            <th class="px-5 py-3.5 text-center">UTS</th>
                            <th class="px-5 py-3.5 text-center">UAS</th>
                            <th class="px-5 py-3.5 text-center font-bold text-slate-700">Nilai Akhir</th>
                            <th class="px-5 py-3.5 text-center">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $idx => $item)
                            @php
                                $na = $item['nilai_akhir'];
                                $predikat = $na >= 90 ? 'A (Sangat Baik)' : ($na >= 80 ? 'B (Baik)' : ($na >= 70 ? 'C (Cukup)' : 'D (Perlu Bimbingan)'));
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $idx === 0 ? 'bg-amber-50/40' : '' }}">
                                <td class="px-5 py-4 text-center font-bold text-slate-700">
                                    @if($idx === 0)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-100 text-amber-800 font-bold text-xs border border-amber-300">1</span>
                                    @elseif($idx === 1)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-700 font-bold text-xs border border-slate-300">2</span>
                                    @elseif($idx === 2)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-50 text-amber-900 font-bold text-xs border border-amber-200">3</span>
                                    @else
                                        <span class="text-slate-400">#{{ $idx + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-mono text-slate-700">{{ $item['siswa']->nis }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $item['siswa']->nama_lengkap }}</td>
                                <td class="px-5 py-4 text-center text-slate-700 font-semibold">{{ $item['nilai_tugas'] }}</td>
                                <td class="px-5 py-4 text-center text-slate-700 font-semibold">{{ $item['nilai_uh'] }}</td>
                                <td class="px-5 py-4 text-center text-slate-700 font-semibold">{{ $item['nilai_uts'] }}</td>
                                <td class="px-5 py-4 text-center text-slate-700 font-semibold">{{ $item['nilai_uas'] }}</td>
                                <td class="px-5 py-4 text-center font-bold text-emerald-700 text-base">{{ $na }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $na >= 80 ? 'bg-emerald-100 text-emerald-700' : ($na >= 70 ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                        {{ $predikat }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-8 text-center text-slate-400">Belum ada data nilai pada kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($type === 'presensi')
                <table class="w-full text-xs sm:text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 uppercase text-[11px] font-bold text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5 w-12 text-center">No</th>
                            <th class="px-5 py-3.5">NIS</th>
                            <th class="px-5 py-3.5">Nama Siswa</th>
                            <th class="px-5 py-3.5 text-center text-emerald-700">Hadir</th>
                            <th class="px-5 py-3.5 text-center text-indigo-700">Izin</th>
                            <th class="px-5 py-3.5 text-center text-amber-700">Sakit</th>
                            <th class="px-5 py-3.5 text-center text-rose-700">Alpa</th>
                            <th class="px-5 py-3.5 text-center font-bold text-slate-700">Total</th>
                            <th class="px-5 py-3.5 text-center font-bold text-emerald-700">% Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $idx => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-4 text-center text-slate-400 font-semibold">{{ $idx + 1 }}</td>
                                <td class="px-5 py-4 font-mono text-slate-700">{{ $item['siswa']->nis }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $item['siswa']->nama_lengkap }}</td>
                                <td class="px-5 py-4 text-center font-semibold text-emerald-700">{{ $item['hadir'] }}</td>
                                <td class="px-5 py-4 text-center font-semibold text-indigo-700">{{ $item['izin'] }}</td>
                                <td class="px-5 py-4 text-center font-semibold text-amber-700">{{ $item['sakit'] }}</td>
                                <td class="px-5 py-4 text-center font-semibold text-rose-700">{{ $item['alpa'] }}</td>
                                <td class="px-5 py-4 text-center font-bold text-slate-800">{{ $item['total'] }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $item['persen'] >= 85 ? 'bg-emerald-100 text-emerald-700' : ($item['persen'] >= 75 ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                        {{ $item['persen'] }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-8 text-center text-slate-400">Tidak ada data presensi pada periode kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($type === 'ujian')
                <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between text-xs sm:text-sm">
                    <div>
                        <span class="text-slate-500 font-semibold">Judul Ujian:</span>
                        <span class="font-bold text-slate-800 ml-1">{{ $data['exam']?->judul ?? 'Tidak Ada Ujian Dipilih' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 font-semibold">Standar KKM:</span>
                        <span class="font-bold text-emerald-700 ml-1">{{ $data['exam']?->kkm ?? 75 }} Poin</span>
                    </div>
                </div>

                <table class="w-full text-xs sm:text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 uppercase text-[11px] font-bold text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5 w-12 text-center">No</th>
                            <th class="px-5 py-3.5">NIS / Email</th>
                            <th class="px-5 py-3.5">Nama Peserta</th>
                            <th class="px-5 py-3.5 text-center">Waktu Mulai</th>
                            <th class="px-5 py-3.5 text-center">Waktu Selesai</th>
                            <th class="px-5 py-3.5 text-center font-bold text-slate-700">Skor Akhir</th>
                            <th class="px-5 py-3.5 text-center">Kelulusan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data['attempts'] as $idx => $att)
                            @php
                                $kkm = $data['exam']?->kkm ?? 75;
                                $isLulus = $att->skor_akhir >= $kkm;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-4 text-center text-slate-400 font-semibold">{{ $idx + 1 }}</td>
                                <td class="px-5 py-4 font-mono text-slate-700">{{ $att->nis_email }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $att->nama_peserta }}</td>
                                <td class="px-5 py-4 text-center text-slate-600">{{ $att->waktu_mulai ? \Carbon\Carbon::parse($att->waktu_mulai)->format('d/m/Y H:i') : '-' }}</td>
                                <td class="px-5 py-4 text-center text-slate-600">{{ $att->waktu_selesai ? \Carbon\Carbon::parse($att->waktu_selesai)->format('d/m/Y H:i') : '-' }}</td>
                                <td class="px-5 py-4 text-center font-bold text-base {{ $isLulus ? 'text-emerald-700' : 'text-rose-700' }}">{{ $att->skor_akhir }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $isLulus ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $isLulus ? 'LULUS KKM' : 'REMEDIAL' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400">Belum ada peserta yang menyelesaikan ujian ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            @elseif($type === 'ppdb')
                <table class="w-full text-xs sm:text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 uppercase text-[11px] font-bold text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5">No. Daftar</th>
                            <th class="px-5 py-3.5">Nama Calon Siswa</th>
                            <th class="px-5 py-3.5">NISN / L-P</th>
                            <th class="px-5 py-3.5">Sekolah Asal</th>
                            <th class="px-5 py-3.5">Pilihan Jurusan</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $p)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-4 font-mono text-slate-700 font-bold">{{ $p->no_pendaftaran }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $p->nama_lengkap }}</td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $p->nisn ?? '-' }} <span class="text-[11px] px-1.5 py-0.5 rounded bg-slate-100 font-bold">{{ $p->jenis_kelamin }}</span>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $p->sekolah_asal ?? '-' }}</td>
                                <td class="px-5 py-4 font-bold text-emerald-700">{{ $p->jurusan ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    @if($p->status === 'accepted')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Diterima</span>
                                    @elseif($p->status === 'verified')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-700">Terverifikasi</span>
                                    @elseif($p->status === 'reserved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">Cadangan</span>
                                    @elseif($p->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">Ditolak</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Pending</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-slate-600 text-xs">{{ $p->created_at ? $p->created_at->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400">Tidak ada data pendaftaran PPDB yang cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if(method_exists($data, 'hasPages') && $data->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $data->withQueryString()->links() }}
                    </div>
                @endif

            @elseif($type === 'activity')
                <table class="w-full text-xs sm:text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 uppercase text-[11px] font-bold text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5">Waktu</th>
                            <th class="px-5 py-3.5">Pengguna</th>
                            <th class="px-5 py-3.5">Aksi</th>
                            <th class="px-5 py-3.5">Deskripsi Aktivitas</th>
                            <th class="px-5 py-3.5">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $l)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-4 font-mono text-slate-600 text-xs">{{ $l->created_at ? $l->created_at->format('d/m/Y H:i:s') : '-' }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $l->user?->name ?? ($l->user?->username ?? 'System/Guest') }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[11px] font-mono font-bold text-slate-700 uppercase">
                                        {{ $l->action }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-slate-700">{{ $l->description }}</td>
                                <td class="px-5 py-4 font-mono text-xs text-slate-400">{{ $l->ip_address ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">Tidak ada riwayat aktivitas log.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if(method_exists($data, 'hasPages') && $data->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $data->withQueryString()->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
