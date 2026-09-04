@extends('errors.layout')

@section('title', '419 - Sesi Kedaluwarsa')
@section('code', '419')
@section('gradient_class', 'from-amber-300 via-orange-400 to-amber-500')

@section('icon')
<svg class="w-8 h-8 sm:w-10 sm:h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection

@section('heading', 'Sesi Halaman Telah Kedaluwarsa')
@section('message')
Sesi keamanan formulir Anda telah berakhir karena tidak ada aktivitas dalam beberapa saat. Silakan muat ulang halaman atau kembali login untuk melanjutkan.
@endsection
