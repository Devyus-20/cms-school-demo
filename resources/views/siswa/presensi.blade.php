@extends('siswa.layouts.app')

@section('title', 'Presensi Saya')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Presensi Kehadiran Saya</h2>
            <p class="text-xs text-slate-500 mt-1">Riwayat presensi kelas {{ $siswa->kelas }} yang diinput oleh guru/admin.</p>
        </div>
        <a href="{{ route('siswa.dashboard') }}" class="px-3.5 py-2 rounded-[5px] bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-colors border border-slate-200">
            &larr; Kembali
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 p-5 rounded-[5px] text-center shadow-sm">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Hadir</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $statHadir }} Hari</div>
        </div>

        <div class="bg-white border border-slate-200 p-5 rounded-[5px] text-center shadow-sm">
            <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Sakit</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $statSakit }} Hari</div>
        </div>

        <div class="bg-white border border-slate-200 p-5 rounded-[5px] text-center shadow-sm">
            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Izin</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $statIzin }} Hari</div>
        </div>

        <div class="bg-white border border-slate-200 p-5 rounded-[5px] text-center shadow-sm">
            <span class="text-xs font-bold text-red-600 uppercase tracking-wider">Alpa</span>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $statAlpa }} Hari</div>
        </div>
    </div>

    {{-- Presensi Table --}}
    <div class="bg-white border border-slate-200 rounded-[5px] overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100 bg-slate-50">
            <h3 class="text-sm font-extrabold text-slate-900">Riwayat Presensi Kehadiran</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-sm text-left text-slate-700">
                <thead class="bg-slate-100 border-b border-slate-200 uppercase text-[11px] font-bold text-slate-500">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5">Keterangan Guru</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($presensiList as $p)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-4 font-bold text-slate-900">
                            {{ $p->tanggal->isoFormat('dddd, D MMMM Y') }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($p->status === 'hadir')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Hadir
                                </span>
                            @elseif($p->status === 'sakit')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Sakit
                                </span>
                            @elseif($p->status === 'izin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    Izin
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                    Alpa
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-xs text-slate-500">
                            {{ $p->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-5 py-8 text-center text-slate-400">Belum ada catatan presensi kehadiran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($presensiList->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $presensiList->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
