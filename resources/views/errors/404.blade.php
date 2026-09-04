@extends('errors.layout')

@section('title', '404 - Halaman Tidak Ditemukan')
@section('code', '404')
@section('gradient_class', 'from-emerald-400 via-teal-300 to-cyan-400')

@section('icon')
<svg class="w-8 h-8 sm:w-10 sm:h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection

@section('heading', 'Halaman Tidak Ditemukan')
@section('message')
Mohon maaf, halaman yang Anda tuju tidak dapat ditemukan atau mungkin tautan telah dipindahkan/dihapus.
@endsection
