@extends('errors.layout')

@section('code', '402')
@section('badge', 'Akses Terbatas')
@section('title', 'Layanan Memerlukan Otorisasi Khusus')

@section('theme_styles')
<style>
    :root {
        --accent-color: #22d3ee;
        --accent-bg: rgba(6, 182, 212, 0.12);
        --accent-border: rgba(34, 211, 238, 0.35);
        --accent-glow: rgba(6, 182, 212, 0.14);
    }
</style>
@endsection

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
</svg>
@endsection

@section('message')
    Permintaan pada resource ini saat ini tidak dapat diproses hingga persyaratan akses atau otorisasi layanan terpenuhi.
@endsection

@section('actions')
<a href="{{ url('/') }}" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    <span>Kembali ke Beranda</span>
</a>
<a href="{{ url('/#helpdesk') }}" class="btn btn-secondary">
    <span>Hubungi IT Helpdesk</span>
</a>
@endsection

