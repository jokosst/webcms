@extends('layouts.admin')

@section('title', 'Dashboard Overview')
@section('header_title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Stat Card 1 -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between shadow-lg">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Layanan</p>
                <h3 class="text-2xl font-bold text-white">{{ $stats['total_services'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 flex items-center justify-center">
                <i data-lucide="briefcase" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between shadow-lg">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Portofolio</p>
                <h3 class="text-2xl font-bold text-white">{{ $stats['total_portfolios'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-violet-500/10 text-violet-400 border border-violet-500/20 flex items-center justify-center">
                <i data-lucide="folder-kanban" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between shadow-lg">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Artikel</p>
                <h3 class="text-2xl font-bold text-white">{{ $stats['total_articles'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center">
                <i data-lucide="newspaper" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between shadow-lg">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Komentar</p>
                <h3 class="text-2xl font-bold text-white">{{ $stats['total_comments'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center">
                <i data-lucide="message-square" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Stat Card 5 -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-between shadow-lg">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pesan Unread</p>
                <h3 class="text-2xl font-bold text-white">{{ $stats['unread_leads'] }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20 flex items-center justify-center">
                <i data-lucide="mail" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    <!-- Section: Comments & Top Articles Widgets -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Widget: Komentar Terakhir dari Artikel -->
        <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="message-square" class="w-5 h-5 text-indigo-400"></i>
                    <h3 class="text-base font-bold text-white">Komentar Terakhir dari Artikel</h3>
                </div>
            </div>

            <div class="space-y-3">
                @forelse($recent_comments as $c)
                    <div class="p-3.5 rounded-xl bg-slate-800/50 border border-slate-800 space-y-1.5 hover:bg-slate-800/80 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-white">{{ $c->name }}</span>
                                @if($c->is_admin_reply)
                                    <span class="px-2 py-0.5 text-[10px] font-bold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 rounded-full">Admin</span>
                                @endif
                            </div>
                            <span class="text-[11px] text-slate-500">{{ $c->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-slate-300 italic line-clamp-2">"{{ $c->comment }}"</p>
                        @if($c->article)
                            <div class="pt-1 flex items-center justify-between text-[11px] border-t border-slate-800/60">
                                <span class="text-slate-400 truncate max-w-xs">Artikel: <strong class="text-slate-300 font-semibold">{{ $c->article->title }}</strong></span>
                                <a href="{{ route('admin.articles.show', $c->article->id) }}" class="text-indigo-400 hover:underline flex items-center gap-1 font-medium">
                                    <span>Lihat & Balas</span>
                                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500 text-sm">Belum ada komentar artikel.</div>
                @endforelse
            </div>
        </div>

        <!-- Widget: Artikel Paling Banyak Komentar -->
        <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="flame" class="w-5 h-5 text-amber-400"></i>
                    <h3 class="text-base font-bold text-white">Artikel Paling Banyak Komentar</h3>
                </div>
            </div>

            <div class="space-y-3">
                @forelse($top_commented_articles as $art)
                    <div class="p-3.5 rounded-xl bg-slate-800/50 border border-slate-800 flex items-center justify-between hover:bg-slate-800/80 transition-colors">
                        <div class="space-y-1 max-w-xs sm:max-w-sm">
                            <a href="{{ route('admin.articles.show', $art->id) }}" class="font-bold text-sm text-white hover:text-indigo-400 transition-colors line-clamp-1">
                                {{ $art->title }}
                            </a>
                            <span class="text-[11px] text-slate-400 block">Kategori: {{ $art->category->name ?? 'Uncategorized' }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-full flex items-center gap-1">
                                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                                {{ number_format($art->all_comments_count) }} Komen
                            </span>
                            <a href="{{ route('admin.articles.show', $art->id) }}" class="p-1.5 text-slate-400 hover:text-white bg-slate-800 rounded-lg">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-500 text-sm">Belum ada data artikel.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions & Inbox Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Inbox Leads Table -->
        <div class="lg:col-span-2 bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-white">Inbox Pesan Terbaru</h3>
                    <p class="text-xs text-slate-400">Pesan dari form kontak web profil publik</p>
                </div>
                <a href="{{ route('admin.inbox.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-800/50 border-b border-slate-800">
                        <tr>
                            <th class="px-4 py-3">Pengirim</th>
                            <th class="px-4 py-3">Subjek</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse($recent_leads as $lead)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 py-3 font-medium text-white">
                                    {{ $lead->name }}
                                    <span class="block text-xs text-slate-400 font-normal">{{ $lead->email }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-300 truncate max-w-xs">{{ $lead->subject ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($lead->status === 'unread')
                                        <span class="px-2.5 py-0.5 text-xs font-semibold bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-full">Unread</span>
                                    @elseif($lead->status === 'read')
                                        <span class="px-2.5 py-0.5 text-xs font-semibold bg-sky-500/20 text-sky-400 border border-sky-500/30 rounded-full">Read</span>
                                    @else
                                        <span class="px-2.5 py-0.5 text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full">Replied</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-slate-400">{{ $lead->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-500 text-sm">Belum ada pesan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Links & System Status -->
        <div class="space-y-6">
            <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 space-y-4 shadow-lg">
                <h3 class="text-base font-bold text-white">Pintasan Cepat</h3>

                <div class="space-y-2.5">
                    <a href="{{ route('admin.company-profile.edit') }}" class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <div class="text-sm font-medium text-white">Edit Profil Perusahaan</div>
                    </a>

                    <a href="{{ route('admin.articles.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                        </div>
                        <div class="text-sm font-medium text-white">Tulis Artikel Baru</div>
                    </a>

                    <a href="{{ route('admin.portfolios.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/60 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-violet-500/10 text-violet-400 flex items-center justify-center">
                            <i data-lucide="folder-plus" class="w-4 h-4"></i>
                        </div>
                        <div class="text-sm font-medium text-white">Tambah Portofolio Proyek</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
