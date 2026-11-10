@extends('errors.layout')

@section('code', '405')
@section('badge', 'Metode Tidak Diizinkan')
@section('title', 'Metode Akses Tidak Didukung')

@section('theme_styles')
<style>
    :root {
        --accent-color: #fbbf24;
        --accent-bg: rgba(245, 158, 11, 0.12);
        --accent-border: rgba(245, 158, 11, 0.35);
        --accent-glow: rgba(245, 158, 11, 0.14);
    }
</style>
@endsection

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
</svg>
@endsection

@section('message')
    Halaman ini hanya dapat diakses melalui pengiriman formulir atau metode HTTP tertentu, bukan dibuka secara langsung di bilah alamat.
@endsection

@section('actions')
<a href="{{ url('/') }}" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    <span>Kembali ke Beranda</span>
</a>
<button type="button" onclick="window.history.back()" class="btn btn-secondary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <span>Kembali</span>
</button>
@endsection

