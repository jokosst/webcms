@extends('errors.layout')

@section('code', '500')
@section('badge', 'Kesalahan Server Internal')
@section('title', 'Terjadi Gangguan Pada Server')

@section('theme_styles')
<style>
    :root {
        --accent-color: #f43f5e;
        --accent-bg: rgba(244, 63, 94, 0.12);
        --accent-border: rgba(244, 63, 94, 0.35);
        --accent-glow: rgba(244, 63, 94, 0.16);
    }
</style>
@endsection

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 14.25h13.5m-13.5 0a3 3 0 01-3-3m3 3a3 3 0 100 6h13.5a3 3 0 100-6m-16.5-3a3 3 0 013-3h13.5a3 3 0 013 3m-19.5 0a4.5 4.5 0 01.9-2.7L5.737 5.1a3.375 3.375 0 012.7-1.35h7.126c1.062 0 2.062.5 2.7 1.35l2.587 3.45a4.5 4.5 0 01.9 2.7m0 0a3 3 0 01-3 3m0 3h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008zm-3 6h.008v.008h-.008v-.008zm0-6h.008v.008h-.008v-.008z" />
</svg>
@endsection

@section('message')
    Maaf, sistem mengalami kendala teknis internal saat memproses permintaan Anda. Tim teknis kami akan segera menanganinya.
@endsection

@section('hint')
    Silakan muat ulang halaman beberapa saat lagi. Jika kendala terus berulang, laporkan melalui formulir IT Helpdesk.
@endsection

@section('actions')
<button type="button" onclick="window.location.reload()" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
    </svg>
    <span>Muat Ulang</span>
</button>
<a href="{{ url('/') }}" class="btn btn-secondary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    <span>Kembali ke Beranda</span>
</a>
@endsection

