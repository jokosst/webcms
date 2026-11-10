@extends('errors.layout')

@php
    $rawMsg = isset($exception) ? trim((string) $exception->getMessage()) : '';
    $displayMessage = ($rawMsg !== '' && strtolower($rawMsg) !== 'service unavailable')
        ? $rawMsg
        : 'Portal layanan saat ini sedang dalam pemeliharaan atau peningkatan sistem terjadwal agar layanan lebih optimal.';
@endphp

@section('code', '503')
@section('badge', 'Sedang Pemeliharaan')
@section('title', 'Layanan Tidak Tersedia Sementara')

@section('theme_styles')
<style>
    :root {
        --accent-color: #22d3ee;
        --accent-bg: rgba(6, 182, 212, 0.12);
        --accent-border: rgba(34, 211, 238, 0.35);
        --accent-glow: rgba(6, 182, 212, 0.16);
    }
</style>
@endsection

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
</svg>
@endsection

@section('message')
    {{ $displayMessage }}
@endsection

@section('hint')
    Kami akan segera kembali online setelah proses pemeliharaan selesai. Silakan cek kembali dalam beberapa menit.
@endsection

@section('actions')
<button type="button" onclick="window.location.reload()" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
    </svg>
    <span>Cek Kembali</span>
</button>
@endsection

