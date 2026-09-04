@extends('public.layouts.exam')

@section('title', 'Konfirmasi Data Peserta Ujian - ' . $exam->judul)

@section('content')
<main class="mx-auto max-w-xl px-6 py-12">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-sm space-y-6">
        <div>
            <div class="flex items-center justify-between text-xs font-semibold text-slate-400">
                <a href="{{ route('siswa.dashboard') }}" class="text-amber-600 font-bold hover:underline">&larr; Dashboard Siswa</a>
                <a href="{{ route('public.ujian.index') }}" class="hover:text-amber-600">Daftar Ujian</a>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-snug mt-3">{{ $exam->judul }}</h1>
            <div class="flex items-center gap-3 pt-2 text-xs">
                <span class="px-3 py-1 rounded-xl bg-amber-50 text-amber-700 font-bold border border-amber-200">{{ $exam->mata_pelajaran }}</span>
                <span class="text-slate-500 font-medium">Durasi: {{ $exam->durasi_menit }} Menit</span>
            </div>
        </div>

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('public.ujian.start', $exam) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                <input type="text" name="nama_peserta" required value="{{ old('nama_peserta') }}" placeholder="Masukkan nama lengkap Anda..."
                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-500 text-sm text-slate-900 outline-none transition-all">
                @error('nama_peserta')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">NIS / Email / NIK <span class="text-red-500">*</span></label>
                <input type="text" name="nis_email" required value="{{ old('nis_email') }}" placeholder="Contoh: 12345678"
                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-500 text-sm text-slate-900 outline-none transition-all">
                @error('nis_email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kelas / Jurusan <span class="text-red-500">*</span></label>
                <input type="text" name="kelas" required value="{{ old('kelas') }}" placeholder="Contoh: X IPA 1"
                       class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-500 text-sm text-slate-900 outline-none transition-all">
                @error('kelas')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            @if($exam->token)
                <div class="pt-2">
                    <label class="block text-xs font-bold text-amber-700 uppercase tracking-wider mb-1.5">Token Ujian <span class="text-red-500">*</span></label>
                    <input type="text" name="token" required value="{{ old('token') }}" placeholder="Masukkan token pengawas..."
                           class="w-full px-4 py-3 rounded-2xl border border-amber-300 bg-amber-50 focus:bg-white focus:border-amber-500 text-sm font-mono font-bold tracking-widest text-slate-900 outline-none uppercase transition-all">
                </div>
            @endif

            @if($exam->deskripsi)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 leading-relaxed space-y-1">
                    <div class="font-bold text-slate-800">Petunjuk Ujian:</div>
                    <p>{{ $exam->deskripsi }}</p>
                </div>
            @endif

            <button type="submit"
                    class="w-full py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-sm transition-all shadow-md cursor-pointer mt-4 uppercase tracking-wider">
                Mulai Kerjakan Ujian &rarr;
            </button>
        </form>
    </div>
</main>
@endsection
