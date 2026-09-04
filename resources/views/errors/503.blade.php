@extends('errors.layout')

@section('title', '503 - Pemeliharaan Sistem')
@section('code', '503')
@section('gradient_class', 'from-cyan-400 via-blue-400 to-indigo-500')

@section('icon')
<svg class="w-8 h-8 sm:w-10 sm:h-10 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
</svg>
@endsection

@section('heading', 'Sistem Dalam Pemeliharaan Berkala')
@section('message')
{{ $exception->getMessage() ?: 'Website sedang dalam peningkatan sistem dan perawatan rutin. Kami akan kembali online dalam waktu singkat.' }}
@endsection
