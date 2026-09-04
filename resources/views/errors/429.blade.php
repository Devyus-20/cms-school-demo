@extends('errors.layout')

@section('title', '429 - Terlalu Banyak Permintaan')
@section('code', '429')
@section('gradient_class', 'from-amber-400 via-rose-400 to-indigo-500')

@section('icon')
<svg class="w-8 h-8 sm:w-10 sm:h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
</svg>
@endsection

@section('heading', 'Terlalu Banyak Permintaan')
@section('message')
Anda telah melakukan terlalu banyak permintaan dalam waktu singkat untuk menjaga stabilitas sistem. Silakan tunggu beberapa saat lalu coba kembali.
@endsection
