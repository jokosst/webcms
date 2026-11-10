@extends('errors.layout')

@php
    $rawMsg = isset($exception) ? trim((string) $exception->getMessage()) : '';
    $displayMessage = ($rawMsg !== '' && !in_array(strtolower($rawMsg), ['forbidden', 'this action is unauthorized.'], true))
        ? $rawMsg
        : 'Maaf, Anda tidak memiliki hak akses atau izin peran untuk membuka halaman maupun menjalankan tindakan ini.';
@endphp

@section('code', '403')
@section('badge', 'Akses Ditolak')
@section('title', 'Anda Tidak Memiliki Izin')

@section('theme_styles')
<style>
    :root {
        --accent-color: #fb7185;
        --accent-bg: rgba(244, 63, 94, 0.12);
        --accent-border: rgba(251, 113, 133, 0.35);
        --accent-glow: rgba(244, 63, 94, 0.15);
    }
</style>
@endsection

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
</svg>
@endsection

@section('message')
    {{ $displayMessage }}
@endsection

@section('hint')
    Jika Anda merasa seharusnya memiliki akses ke menu ini, silakan hubungi Superadmin atau pengelola UPA TIK UNTAN.
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
    <span>Halaman Sebelumnya</span>
</button>
@endsection

