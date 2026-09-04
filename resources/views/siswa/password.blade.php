@extends('siswa.layouts.app')

@section('title', 'Ubah Password Akun Siswa')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Ubah Password Akun Mandiri</h2>
            <p class="text-xs text-slate-500 mt-1">Perbarui password akun Anda demi keamanan data dan kenyamanan akses portal.</p>
        </div>
        <a href="{{ route('siswa.dashboard') }}" class="px-3.5 py-2 rounded-[5px] bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition-colors border border-slate-200">
            &larr; Kembali
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="p-4 rounded-[5px] bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <div>
                <strong class="block text-emerald-900">Berhasil!</strong>
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white border border-slate-200 rounded-[5px] p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
            <div class="w-10 h-10 rounded-[5px] bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center font-bold shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Form Pengaturan Password Baru</h3>
                <p class="text-[11px] text-slate-500">Pastikan password baru Anda kuat dan mudah diingat.</p>
            </div>
        </div>

        <form action="{{ route('siswa.password.update') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Password Lama --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Password Lama <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="current_password" name="current_password" required placeholder="Masukkan password Anda saat ini"
                           class="w-full px-4 py-3 rounded-[5px] bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-900 placeholder-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-amber-500 transition-all pr-10">
                    <button type="button" onclick="togglePass('current_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                @error('current_password')
                    <p class="text-rose-500 text-[11px] font-medium mt-1.5 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-slate-100 my-2">

            {{-- Password Baru --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Password Baru <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter"
                           class="w-full px-4 py-3 rounded-[5px] bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-900 placeholder-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-amber-500 transition-all pr-10">
                    <button type="button" onclick="togglePass('password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-rose-500 text-[11px] font-medium mt-1.5 flex items-center gap-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password Baru --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Konfirmasi Password Baru <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ketik ulang password baru"
                           class="w-full px-4 py-3 rounded-[5px] bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-900 placeholder-slate-400 outline-none focus:bg-white focus:ring-2 focus:ring-amber-500 transition-all pr-10">
                    <button type="button" onclick="togglePass('password_confirmation')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>

            {{-- Info Tip --}}
            <div class="p-4 rounded-[5px] bg-slate-50 border border-slate-200 text-[11px] text-slate-600 space-y-1">
                <span class="font-extrabold text-amber-700 block">Petunjuk Keamanan Akun:</span>
                <ul class="list-disc list-inside space-y-0.5 text-slate-500">
                    <li>Kombinasikan huruf besar, huruf kecil, dan angka.</li>
                    <li>Jangan berikan password Anda kepada siapa pun demi keamanan nilai & tugas.</li>
                </ul>
            </div>

            {{-- Submit Button --}}
            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 px-6 rounded-[5px] bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-md transition-all uppercase tracking-wider flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Password Baru</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePass(id) {
        var input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }
</script>
@endsection
