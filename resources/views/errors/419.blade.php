@extends('errors.layout')

@section('code', '419')
@section('badge', 'Sesi Halaman Kedaluwarsa')
@section('title', 'Sesi Keamanan Telah Berakhir')

@section('theme_styles')
<style>
    :root {
        --accent-color: #fbbf24;
        --accent-bg: rgba(245, 158, 11, 0.12);
        --accent-border: rgba(251, 191, 36, 0.35);
        --accent-glow: rgba(245, 158, 11, 0.15);
    }
</style>
@endsection

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection

@section('message')
    Halaman terlalu lama tidak aktif sehingga token keamanan formulir (CSRF) telah kedaluwarsa demi melindungi data Anda.
@endsection

@section('hint')
    Klik <strong>Kembali &amp; Muat Ulang</strong> atau segarkan halaman formulir sebelum mengirimkan ulang data Anda.
@endsection

@section('actions')
<button type="button" onclick="window.location.href = (document.referrer && document.referrer.indexOf(window.location.origin) === 0) ? document.referrer : '{{ url('/') }}'" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
    </svg>
    <span>Muat Ulang Halaman</span>
</button>
<a href="{{ url('/') }}" class="btn btn-secondary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    <span>Ke Beranda</span>
</a>
@endsection
