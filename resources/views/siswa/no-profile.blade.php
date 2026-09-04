@extends('siswa.layouts.app')

@section('title', 'Profil Siswa Tidak Ditemukan')

@section('content')
<div class="max-w-xl mx-auto py-16 text-center space-y-4">
    <div class="w-16 h-16 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center mx-auto text-3xl">
        ⚠️
    </div>
    <h2 class="text-xl font-bold text-white">Data Profil Siswa Belum Dihubungkan</h2>
    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
        Akun Anda ({{ auth()->user()->email }}) belum terhubung dengan data induk siswa di sekolah. Silakan hubungi pihak Administrator atau Tata Usaha Sekolah.
    </p>
    <div class="pt-4">
        <a href="{{ route('logout') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs transition-colors">
            Keluar & Login Kembali
        </a>
    </div>
</div>
@endsection
