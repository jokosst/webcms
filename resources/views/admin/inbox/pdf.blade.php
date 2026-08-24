<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inbox Pesan Masuk - {{ date('d M Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 p-4 sm:p-8">

    <!-- Top Action Bar (Hidden when printing) -->
    <div class="no-print max-w-5xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-xl shadow border border-slate-200">
        <a href="{{ route('admin.inbox.index', request()->all()) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Admin Inbox</span>
        </a>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-wider rounded-lg shadow transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak / Simpan Sebagai PDF</span>
            </button>
        </div>
    </div>

    <!-- Printable Report Container -->
    <div class="print-container max-w-5xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-slate-200 space-y-6">
        
        <!-- Header Document -->
        <div class="flex items-center justify-between border-b-2 border-slate-800 pb-4">
            <div class="space-y-1">
                <h1 class="text-xl font-black uppercase tracking-tight text-slate-900">{{ $profile->company_name ?? 'UNIT TIK & INFRASTRUKTUR' }}</h1>
                <p class="text-xs font-semibold text-slate-600">{{ $profile->tagline ?? 'Pusat Pengelolaan Sistem Informasi, Jaringan & Cloud' }}</p>
                <p class="text-xs text-slate-500">Laporan Resmi Inbox Pesan Masuk & IT Helpdesk</p>
            </div>
            <div class="text-right text-xs text-slate-500 space-y-1">
                <p><span class="font-bold text-slate-700">Tanggal Cetak:</span> {{ date('d F Y, H:i') }} WIB</p>
                <p><span class="font-bold text-slate-700">Total Pesan:</span> {{ $leads->count() }} Data</p>
                @if($search || $status)
                    <p><span class="font-bold text-slate-700">Filter:</span> 
                        {{ $search ? 'Kata Kunci: "'.$search.'"' : '' }}
                        {{ $status ? 'Status: '.strtoupper($status) : '' }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse border border-slate-300">
                <thead class="bg-slate-100 text-slate-800 uppercase font-extrabold border-b border-slate-300">
                    <tr>
                        <th class="p-2.5 border border-slate-300 w-10 text-center">No</th>
                        <th class="p-2.5 border border-slate-300 w-28">Tanggal</th>
                        <th class="p-2.5 border border-slate-300">Pengirim & Kontak</th>
                        <th class="p-2.5 border border-slate-300">Subjek / Kategori</th>
                        <th class="p-2.5 border border-slate-300">Rincian Pesan</th>
                        <th class="p-2.5 border border-slate-300 w-24 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @forelse($leads as $index => $lead)
                        <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/50' }}">
                            <td class="p-2.5 border border-slate-300 text-center font-bold">{{ $index + 1 }}</td>
                            <td class="p-2.5 border border-slate-300 whitespace-nowrap">{{ $lead->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-2.5 border border-slate-300 space-y-0.5">
                                <p class="font-bold text-slate-900">{{ $lead->name }}</p>
                                <p class="text-[11px] text-slate-600">{{ $lead->email }}</p>
                                <p class="text-[11px] text-slate-500">{{ $lead->phone ?? '-' }}</p>
                            </td>
                            <td class="p-2.5 border border-slate-300 font-semibold text-slate-800">{{ $lead->subject ?? '-' }}</td>
                            <td class="p-2.5 border border-slate-300 text-slate-700 leading-relaxed max-w-xs">{{ $lead->message }}</td>
                            <td class="p-2.5 border border-slate-300 text-center font-bold uppercase text-[10px]">
                                @if($lead->status === 'unread')
                                    <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-800 border border-rose-300">Belum Dibaca</span>
                                @elseif($lead->status === 'read')
                                    <span class="px-2 py-0.5 rounded bg-sky-100 text-sky-800 border border-sky-300">Sudah Dibaca</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-300">Dibalas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-500 italic">Tidak ada pesan masuk yang memenuhi kriteria filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Sign Off -->
        <div class="pt-6 border-t border-slate-200 flex justify-between items-end text-xs text-slate-500">
            <p>Dokumen ini dihasilkan secara otomatis oleh Sistem Administrator UPA TIK UNTAN.</p>
            <div class="text-center space-y-12">
                <p>Petugas Administrator,</p>
                <p class="font-bold text-slate-800 underline">( _______________________ )</p>
            </div>
        </div>

    </div>

    <!-- Auto Print Trigger -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        };
    </script>
</body>
</html>
