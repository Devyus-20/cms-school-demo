@extends('admin.layouts.app')

@section('title', 'Presensi Kehadiran Siswa')
@section('page-title', 'Presensi Siswa')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg sm:text-xl font-bold text-slate-800">Manajemen Presensi Siswa</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Input kehadiran harian siswa per kelas & tanggal atau cetak laporan bulanan.</p>
        </div>
        @php
            $currBulan = request('bulan', \Carbon\Carbon::parse($selectedTanggal)->format('n'));
            $currTahun = request('tahun', \Carbon\Carbon::parse($selectedTanggal)->format('Y'));
        @endphp
        <a href="{{ route('admin.presensi.print', ['kelas' => $selectedKelas, 'bulan' => $currBulan, 'tahun' => $currTahun]) }}" target="_blank"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold shadow-md shadow-blue-600/20 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Rekap Bulanan (A4)
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Filter Bar --}}
    <div class="bg-white p-4 rounded-[5px] border border-slate-200 shadow-sm">
        <form action="{{ route('admin.presensi.index') }}" method="GET" class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold uppercase text-slate-600">Pilih Kelas:</label>
                    <select name="kelas" onchange="this.form.submit()" class="px-3.5 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 font-semibold text-slate-700">
                        @foreach($kelases as $k)
                            <option value="{{ $k }}" {{ $selectedKelas == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold uppercase text-slate-600">Tanggal Input:</label>
                    <input type="date" name="tanggal" value="{{ $selectedTanggal }}" onchange="this.form.submit()"
                           class="px-3.5 py-2 rounded-[5px] border border-slate-200 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 bg-slate-50 font-semibold text-slate-700">
                </div>

                <button type="submit" class="px-4 py-2 rounded-[5px] bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors shadow-sm">
                    Tampilkan Presensi
                </button>
            </div>
        </form>
    </div>

    {{-- Presensi Form Table --}}
    <div class="bg-white rounded-[5px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="text-sm font-bold text-slate-800">
                Presensi Kelas <span class="text-emerald-700">{{ $selectedKelas }}</span> — <span class="text-slate-600">{{ \Carbon\Carbon::parse($selectedTanggal)->isoFormat('dddd, D MMMM Y') }}</span>
            </h3>
            <span class="text-xs text-slate-500">Total: {{ $daftarSiswa->count() }} Siswa</span>
        </div>

        <form action="{{ route('admin.presensi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">

            <div class="overflow-x-auto">
                <table class="w-full text-xs sm:text-sm text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 uppercase text-[11px] font-bold text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5">NIS</th>
                            <th class="px-5 py-3.5">Nama Siswa</th>
                            <th class="px-5 py-3.5 text-center">Status Kehadiran</th>
                            <th class="px-5 py-3.5">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($daftarSiswa as $siswa)
                        @php
                            $currentStatus = $existingPresensi[$siswa->id_siswa]->status ?? 'hadir';
                            $currentKet = $existingPresensi[$siswa->id_siswa]->keterangan ?? '';
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-slate-600">{{ $siswa->nis }}</td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800">
                                {{ $siswa->nama_lengkap }}
                                <span class="text-[10px] text-slate-400 font-normal ml-1">({{ $siswa->jenis_kelamin }})</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-2 sm:gap-4">
                                    <label class="flex items-center gap-1.5 cursor-pointer px-2.5 py-1 rounded-lg hover:bg-emerald-50 transition-colors">
                                        <input type="radio" name="presensi[{{ $siswa->id_siswa }}][status]" value="hadir" {{ $currentStatus == 'hadir' ? 'checked' : '' }}
                                               class="text-emerald-600 focus:ring-emerald-500">
                                        <span class="text-xs font-bold text-emerald-700">Hadir</span>
                                    </label>

                                    <label class="flex items-center gap-1.5 cursor-pointer px-2.5 py-1 rounded-lg hover:bg-amber-50 transition-colors">
                                        <input type="radio" name="presensi[{{ $siswa->id_siswa }}][status]" value="sakit" {{ $currentStatus == 'sakit' ? 'checked' : '' }}
                                               class="text-amber-600 focus:ring-amber-500">
                                        <span class="text-xs font-bold text-amber-700">Sakit</span>
                                    </label>

                                    <label class="flex items-center gap-1.5 cursor-pointer px-2.5 py-1 rounded-lg hover:bg-blue-50 transition-colors">
                                        <input type="radio" name="presensi[{{ $siswa->id_siswa }}][status]" value="izin" {{ $currentStatus == 'izin' ? 'checked' : '' }}
                                               class="text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-bold text-blue-700">Izin</span>
                                    </label>

                                    <label class="flex items-center gap-1.5 cursor-pointer px-2.5 py-1 rounded-lg hover:bg-red-50 transition-colors">
                                        <input type="radio" name="presensi[{{ $siswa->id_siswa }}][status]" value="alpa" {{ $currentStatus == 'alpa' ? 'checked' : '' }}
                                               class="text-red-600 focus:ring-red-500">
                                        <span class="text-xs font-bold text-red-700">Alpa</span>
                                    </label>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <input type="text" name="presensi[{{ $siswa->id_siswa }}][keterangan]" value="{{ $currentKet }}" placeholder="Catatan (opsional)..."
                                       class="w-full px-3 py-1.5 rounded-lg border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-500 bg-slate-50">
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-slate-400">Tidak ada siswa terdaftar di kelas {{ $selectedKelas }}. Silakan tambahkan siswa terlebih dahulu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($daftarSiswa->isNotEmpty())
            <div class="p-4 border-t border-slate-100 flex justify-end bg-slate-50/50">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/20 transition-colors">
                    Simpan Presensi Kelas {{ $selectedKelas }}
                </button>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
