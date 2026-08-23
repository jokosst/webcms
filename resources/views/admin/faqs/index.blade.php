@extends('layouts.admin')

@section('title', 'Kelola FAQ / Tanya Jawab')
@section('header_title', 'Kelola FAQ / Tanya Jawab Warga')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, formUrl: '{{ route('admin.faqs.store') }}', faqId: null, question: '', answer: '', sort_order: 1, is_active: true }">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 p-4 rounded-2xl border border-slate-800 shadow-md">
        <div>
            <h2 class="text-base font-bold text-white">Daftar Pertanyaan Umum Warga (FAQ)</h2>
            <p class="text-xs text-slate-400">Pertanyaan dan jawaban yang tampil pada website profil desa (`desa1.blade.php`).</p>
        </div>
        <button @click="
            editMode = false;
            formUrl = '{{ route('admin.faqs.store') }}';
            question = '';
            answer = '';
            sort_order = {{ $faqs->count() + 1 }};
            is_active = true;
            showModal = true;
        " class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center gap-2 self-start sm:self-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Pertanyaan FAQ</span>
        </button>
    </div>

    <!-- FAQ List Table -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-lg">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="bg-slate-800/60 uppercase font-semibold text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-3.5 w-16 text-center">Urutan</th>
                        <th class="px-6 py-3.5">Pertanyaan</th>
                        <th class="px-6 py-3.5">Jawaban</th>
                        <th class="px-6 py-3.5 w-28 text-center">Status</th>
                        <th class="px-6 py-3.5 w-32 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-6 py-4 text-center font-bold text-slate-400">
                                {{ $faq->sort_order }}
                            </td>
                            <td class="px-6 py-4 font-bold text-white max-w-xs">
                                {{ $faq->question }}
                            </td>
                            <td class="px-6 py-4 text-slate-400 max-w-md leading-relaxed">
                                {{ Str::limit($faq->answer, 120) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($faq->is_active)
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 font-semibold text-[11px]">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-slate-800 text-slate-400 border border-slate-700 font-semibold text-[11px]">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="
                                        editMode = true;
                                        formUrl = '{{ route('admin.faqs.update', $faq->id) }}';
                                        faqId = {{ $faq->id }};
                                        question = {{ json_encode($faq->question) }};
                                        answer = {{ json_encode($faq->answer) }};
                                        sort_order = {{ $faq->sort_order }};
                                        is_active = {{ $faq->is_active ? 'true' : 'false' }};
                                        showModal = true;
                                    " class="p-2 text-indigo-400 hover:text-indigo-300 hover:bg-indigo-500/10 rounded-lg transition-colors" title="Edit FAQ">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>

                                    <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan FAQ ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 rounded-lg transition-colors" title="Hapus FAQ">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic">
                                Belum ada data FAQ. Klik tombol "Tambah Pertanyaan FAQ" di atas untuk menambahkan data baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (Tambah / Edit FAQ) -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         style="display: none;">
        
        <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-xl w-full p-6 space-y-5 shadow-2xl relative" @click.away="showModal = false">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-base font-bold text-white" x-text="editMode ? 'Edit Pertanyaan FAQ' : 'Tambah Pertanyaan FAQ Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form :action="formUrl" method="POST" class="space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Pertanyaan <span class="text-rose-400">*</span></label>
                    <input type="text" name="question" x-model="question" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Contoh: Berapa lama proses pembuatan SKU?">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Jawaban Lengkap <span class="text-rose-400">*</span></label>
                    <textarea name="answer" x-model="answer" rows="4" required
                        class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" placeholder="Jawaban sejelas-jelasnya untuk warga..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 items-center">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Urutan Tampilan</label>
                        <input type="number" name="sort_order" x-model="sort_order" min="0"
                            class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div class="pt-6">
                        <label class="flex items-center gap-3 cursor-pointer text-sm text-slate-300">
                            <input type="checkbox" name="is_active" value="1" x-model="is_active" class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                            <span class="font-medium">Tampilkan (Aktif)</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl shadow-lg shadow-indigo-600/30 flex items-center gap-1.5">
                        <i data-lucide="check" class="w-4 h-4"></i>
                        <span x-text="editMode ? 'Update FAQ' : 'Simpan FAQ'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
