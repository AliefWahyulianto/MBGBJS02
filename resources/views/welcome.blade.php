@extends('layouts.app')

@section('content')
<!-- Main Content -->
<main class="space-y-6">
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        
        <!-- Page Header -->
        <div class="flex flex-col gap-1">
            <h2 class="font-h1 text-h1 text-on-background">Stok Masuk</h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant">Kelola penambahan persediaan bahan baku dapur Anda secara efisien.</p>
        </div>

        <!-- Alert -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500">error</span>
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Form -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Input Form -->
                <section class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-primary-container"></div>
                    <h3 class="font-h2 text-h2 text-on-background mb-6">Input Stok Masuk</h3>
                    
                    <form action="{{ route('stok-masuk.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Select Bahan -->
                        <div class="space-y-2">
                            <label class="block font-label-caps text-label-caps text-on-surface-variant">Pilih Bahan</label>
                            <div class="relative">
                                <select name="bahan_id" required class="w-full pl-4 pr-10 py-3 bg-white border border-slate-200 rounded-lg text-sm appearance-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
                                    <option value="">Cari atau pilih bahan...</option>
                                    @foreach($bahans as $bahan)
                                        <option value="{{ $bahan->id }}">{{ $bahan->nama }} ({{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }})</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <!-- Jumlah -->
                        <div class="space-y-2">
                            <label class="block font-label-caps text-label-caps text-on-surface-variant">Jumlah</label>
                            <input type="text" name="jumlah" required
                                   class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none" 
                                   placeholder="0"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>

                        <!-- Tanggal Masuk -->
                        <div class="space-y-2">
                            <label class="block font-label-caps text-label-caps text-on-surface-variant">Tanggal Masuk</label>
                            <div class="relative">
                                <input type="date" name="tanggal_masuk" required value="{{ date('Y-m-d') }}"
                                       class="w-full pl-4 pr-10 py-3 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none">
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">calendar_today</span>
                            </div>
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="space-y-2">
                            <label class="block font-label-caps text-label-caps text-on-surface-variant">Catatan Tambahan</label>
                            <textarea name="catatan" rows="2" 
                                      class="w-full px-4 py-3 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none resize-none" 
                                      placeholder="Contoh: Supplier Baru, Grade A..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full py-4 bg-primary-container text-white font-semibold rounded-lg hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">add_circle</span>
                            Tambah Stok
                        </button>
                    </form>
                </section>

                <!-- Quick Insight Card -->
                <div class="bg-emerald-600 rounded-xl p-6 text-white shadow-lg relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="font-label-caps opacity-80 mb-2">Insight Hari Ini</p>
                        <h4 class="text-xl font-bold mb-4">Total Stok Masuk</h4>
                        <p class="text-3xl font-bold">{{ number_format($totalStokMasukHariIni, 2) }} Unit</p>
                        <p class="text-sm opacity-90 leading-relaxed mt-2">
                            dari {{ $totalTransaksiHariIni }} transaksi hari ini
                        </p>
                    </div>
                    <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-8xl opacity-10 rotate-12">inventory</span>
                </div>
            </div>

            <!-- Right Column: History Table -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    
                    <!-- Table Header -->
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="font-h2 text-h2 text-on-background">Riwayat Stok Masuk</h3>
                        <a href="{{ route('stok-masuk.history') }}" class="text-emerald-600 font-semibold text-sm flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-sm">filter_list</span>
                            Lihat Semua
                        </a>
                    </div>

                    <!-- Data Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white border-b border-slate-100">
                                    <th class="px-6 py-4 font-label-caps text-slate-500 uppercase tracking-wider">Bahan</th>
                                    <th class="px-6 py-4 font-label-caps text-slate-500 uppercase tracking-wider text-center">Jumlah</th>
                                    <th class="px-6 py-4 font-label-caps text-slate-500 uppercase tracking-wider text-center">Tanggal</th>
                                    <th class="px-6 py-4 font-label-caps text-slate-500 uppercase tracking-wider text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($stokMasukTerbaru as $item)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-slate-600">
                                                    <span class="material-symbols-outlined text-sm">inventory</span>
                                                </div>
                                                <span class="font-medium text-slate-900">{{ $item->bahan->nama ?? 'Bahan Dihapus' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center font-display-lg text-[18px] text-slate-700">{{ number_format($item->jumlah, 2) }} {{ $item->bahan->satuan ?? '' }}</td>
                                        <td class="px-6 py-4 text-center text-body-sm text-slate-500">{{ $item->tanggal_masuk->format('d M Y') }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                Verified
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                            <span class="material-symbols-outlined text-5xl mb-2">inventory</span>
                                            <p>Belum ada riwayat stok masuk</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($stokMasukTerbaru, 'links'))
                        <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                            {{ $stokMasukTerbaru->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection