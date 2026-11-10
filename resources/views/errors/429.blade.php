@extends('errors.layout')

@section('code', '429')
@section('badge', 'Batas Permintaan Tercapai')
@section('title', 'Terlalu Banyak Permintaan')

@section('theme_styles')
<style>
    :root {
        --accent-color: #fb923c;
        --accent-bg: rgba(249, 115, 22, 0.12);
        --accent-border: rgba(251, 146, 60, 0.35);
        --accent-glow: rgba(249, 115, 22, 0.15);
    }
</style>
@endsection

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
</svg>
@endsection

@section('message')
    Anda telah mengirim terlalu banyak permintaan dalam waktu singkat. Sistem keamanan membatasi akses sementara untuk menjaga stabilitas layanan.
@endsection

@section('hint')
    Mohon tunggu sekitar <strong>1 menit</strong> sebelum mencoba mengirim permintaan kembali.
@endsection

@section('actions')
<button type="button" onclick="window.location.reload()" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
    </svg>
    <span>Coba Lagi</span>
</button>
<a href="{{ url('/') }}" class="btn btn-secondary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    <span>Kembali ke Beranda</span>
</a>
@endsection

