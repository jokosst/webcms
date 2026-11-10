@extends('errors.layout')

@section('code', '401')
@section('badge', 'Autentikasi Diperlukan')
@section('title', 'Akses Memerlukan Login')

@section('theme_styles')
<style>
    :root {
        --accent-color: #38bdf8;
        --accent-bg: rgba(14, 165, 233, 0.12);
        --accent-border: rgba(56, 189, 248, 0.35);
        --accent-glow: rgba(14, 165, 233, 0.15);
    }
</style>
@endsection

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
</svg>
@endsection

@section('message')
    Anda perlu melakukan autentikasi atau masuk terlebih dahulu dengan akun yang sah untuk dapat mengakses halaman ini.
@endsection

@section('actions')
<a href="{{ url('/') }}" class="btn btn-secondary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    <span>Kembali ke Beranda</span>
</a>
@endsection

