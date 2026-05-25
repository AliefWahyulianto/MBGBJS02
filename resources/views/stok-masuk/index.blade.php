@extends('layouts.app')

@section('content')
<div class="p-8 max-w-7xl mx-auto space-y-8 fade-in-up">
    
    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl flex items-center gap-2 card-stagger">
            <span class="material-symbols-outlined text-emerald-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Alert Error -->
    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl flex items-center gap-2 card-stagger">
            <span class="material-symbols-outlined text-red-500">error</span>
            {{ session('error') }}
        </div>
    @endif

    <!-- Page Header -->
    <div class="flex flex-col gap-1 card-stagger">
        <div class="flex justify-between items-center flex-wrap gap-3">
            <div>
                <h2 class="font-h1 text-h1 text-slate-800 dark:text-white">Stok Masuk</h2>
                <p class="font-body-sm text-body-sm text-slate-500 dark:text-slate-400">Kelola penambahan persediaan bahan baku dapur Anda secara efisien.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('stok-masuk.export.excel') }}" 
                   class="px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-lg text-sm font-medium transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">table_chart</span>
                    Export Excel
                </a>
                <a href="{{ route('stok-masuk.export.pdf') }}" 
                   class="px-3 py-1.5 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-lg text-sm font-medium transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                    Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Form + Insight Card -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Input Form -->
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 overflow-hidden relative card-stagger">
                <div class="absolute top-0 left-0 w-full h-1 bg-primary-container"></div>
                <h3 class="font-h2 text-h2 text-slate-800 dark:text-white mb-6">Input Stok Masuk</h3>
                
                <form action="{{ route('stok-masuk.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Select Bahan -->
                    <div class="space-y-2">
                        <label class="block font-label-caps text-label-caps text-slate-600 dark:text-slate-400">Pilih Bahan</label>
                        <div class="relative">
                            <select name="bahan_id" id="bahan_id" required 
                                    class="w-full pl-4 pr-10 py-3 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm appearance-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none text-slate-700 dark:text-slate-200">
                                <option value="">Cari atau pilih bahan...</option>
                                @foreach($bahans as $bahan)
                                    <option value="{{ $bahan->id }}" data-satuan="{{ $bahan->satuan }}">
                                        {{ $bahan->nama }} (Stok: {{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                        @error('bahan_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jumlah & Satuan -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block font-label-caps text-label-caps text-slate-600 dark:text-slate-400">Jumlah</label>
                            <input type="text" name="jumlah" id="jumlah" required
                                   class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none text-slate-700 dark:text-slate-200" 
                                   placeholder="0"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            @error('jumlah')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block font-label-caps text-label-caps text-slate-600 dark:text-slate-400">Satuan</label>
                            <div id="satuanDisplay" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-500 dark:text-slate-400 flex items-center">
                                Kg / Unit
                            </div>
                        </div>
                    </div>

                    <!-- Harga Satuan & Total Harga -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block font-label-caps text-label-caps text-slate-600 dark:text-slate-400">Harga Satuan (Rp)</label>
                            <input type="text" name="harga_satuan" id="harga_satuan" required
                                   class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none text-slate-700 dark:text-slate-200" 
                                   placeholder="0"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            @error('harga_satuan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block font-label-caps text-label-caps text-slate-600 dark:text-slate-400">Total Harga</label>
                            <div id="total_harga_display" class="w-full px-4 py-3 bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-emerald-700 dark:text-emerald-400 font-semibold">
                                Rp 0
                            </div>
                            <input type="hidden" name="total_harga" id="total_harga">
                        </div>
                    </div>

                    <!-- Tanggal Masuk -->
                    <div class="space-y-2">
                        <label class="block font-label-caps text-label-caps text-slate-600 dark:text-slate-400">Tanggal Masuk</label>
                        <div class="relative">
                            <input type="date" name="tanggal_masuk" required value="{{ date('Y-m-d') }}"
                                   class="w-full pl-4 pr-10 py-3 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none text-slate-700 dark:text-slate-200">
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">calendar_today</span>
                        </div>
                        @error('tanggal_masuk')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilih Supplier -->
                    <div class="space-y-2">
                        <label class="block font-label-caps text-label-caps text-slate-600 dark:text-slate-400">Supplier</label>
                        <select name="supplier_id" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-700 dark:text-slate-200">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- No Invoice -->
                    <div class="space-y-2">
                        <label class="block font-label-caps text-label-caps text-slate-600 dark:text-slate-400">No. Invoice</label>
                        <input type="text" name="no_invoice" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm text-slate-700 dark:text-slate-200" placeholder="INV-2025-001">
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="space-y-2">
                        <label class="block font-label-caps text-label-caps text-slate-600 dark:text-slate-400">Catatan Tambahan</label>
                        <textarea name="catatan" rows="2" 
                                  class="w-full px-4 py-3 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all outline-none resize-none text-slate-700 dark:text-slate-200" 
                                  placeholder="Contoh: Supplier Baru, Grade A..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-4 bg-primary-container hover:bg-primary-container/90 text-white font-semibold rounded-lg transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">add_circle</span>
                        Tambah Stok
                    </button>
                </form>
            </div>

            <!-- Quick Insight Card -->
            <div class="bg-emerald-600 rounded-xl p-6 text-white shadow-lg relative overflow-hidden card-stagger" style="animation-delay: 0.05s">
                <div class="relative z-10">
                    <p class="font-label-caps opacity-80 mb-2">Insight Hari Ini</p>
                    <h4 class="text-xl font-bold mb-2">Total Stok Masuk</h4>
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
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden card-stagger" style="animation-delay: 0.1s">
                
                <!-- Table Header -->
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-700/30">
                    <h3 class="font-h2 text-h2 text-slate-800 dark:text-white">Riwayat Stok Masuk</h3>
                    <div class="flex gap-2">
                        <button id="btnFilter" class="text-emerald-600 dark:text-emerald-400 font-semibold text-sm flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-sm">filter_list</span>
                            Filter
                        </button>
                        <button id="btnRefresh" class="text-emerald-600 dark:text-emerald-400 font-semibold text-sm flex items-center gap-1 hover:underline">
                            <span class="material-symbols-outlined text-sm">refresh</span>
                            Refresh
                        </button>
                    </div>
                </div>

                <!-- Modal Filter -->
                <div id="filterModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 w-96">
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white mb-4">Filter Riwayat</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Dari Tanggal</label>
                                <input type="date" id="filterStartDate" class="w-full border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sampai Tanggal</label>
                                <input type="date" id="filterEndDate" class="w-full border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Bahan</label>
                                <select id="filterBahanId" class="w-full border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                                    <option value="">Semua Bahan</option>
                                    @foreach($bahans as $bahan)
                                        <option value="{{ $bahan->id }}">{{ $bahan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Supplier</label>
                                <select id="filterSupplierId" class="w-full border border-slate-200 dark:border-slate-600 rounded-lg px-3 py-2 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                                    <option value="">Semua Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-6">
                            <button id="btnCloseFilter" class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700">Batal</button>
                            <button id="btnApplyFilter" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Terapkan</button>
                        </div>
                    </div>
                </div>

               <!-- Data Table -->
                <div class="overflow-x-auto overflow-y-visible">
                    <table class="w-full text-left table-auto border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-200">Bahan</th>
                                <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-200">Supplier</th>
                                <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider text-center border-b border-slate-200">Jumlah</th>
                                <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider text-center border-b border-slate-200">Harga</th>
                                <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider text-center border-b border-slate-200">Tanggal</th>
                                <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider text-center border-b border-slate-200">Status</th>
                                <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase tracking-wider text-center border-b border-slate-200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stokMasuk as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition-colors table-row-stagger border-b border-slate-100" style="animation-delay: {{ 0.03 * ($index + 1) }}s">
                                    <td class="px-2 py-2 align-middle border-b border-slate-100">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-7 h-7 rounded bg-slate-100 flex items-center justify-center flex-shrink-0">
                                                <span class="material-symbols-outlined text-sm text-slate-500">inventory</span>
                                            </div>
                                            <span class="font-medium text-slate-800 text-xs truncate max-w-[120px]" title="{{ $item->bahan->nama ?? 'Bahan Dihapus' }}">
                                                {{ \Illuminate\Support\Str::limit($item->bahan->nama ?? 'Bahan Dihapus', 20) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-2 text-xs text-slate-600 align-middle truncate max-w-[100px] border-b border-slate-100" title="{{ $item->supplier->nama ?? '-' }}">
                                        {{ \Illuminate\Support\Str::limit($item->supplier->nama ?? '-', 18) }}
                                    </td>
                                    <td class="px-2 py-2 text-center text-xs text-slate-700 align-middle whitespace-nowrap border-b border-slate-100">
                                        {{ number_format($item->jumlah, 0) }} <span class="text-[10px] text-slate-400">{{ $item->bahan->satuan ?? '' }}</span>
                                    </td>
                                    <td class="px-2 py-2 text-center text-xs text-slate-600 align-middle whitespace-nowrap border-b border-slate-100">
                                        {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-2 py-2 text-center text-xs text-slate-500 align-middle whitespace-nowrap border-b border-slate-100">
                                        {{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-2 py-2 text-center align-middle border-b border-slate-100">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-medium bg-emerald-100 text-emerald-700 whitespace-nowrap">
                                            Verified
                                        </span>
                                    </td>
                                    <td class="px-2 py-2 text-center align-middle border-b border-slate-100">
                                        <div class="flex items-center justify-center gap-0.5">
                                            <a href="{{ route('stok-masuk.show', $item) }}" 
                                            class="p-1 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded transition-colors"
                                            title="Detail">
                                                <span class="material-symbols-outlined text-sm">visibility</span>
                                            </a>
                                            <button onclick="confirmDelete({{ $item->id }})" 
                                                    class="p-1 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition-colors"
                                                    title="Hapus">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-8 text-center text-slate-400 text-sm border-b border-slate-100">
                                        <span class="material-symbols-outlined text-4xl mb-2">inventory</span>
                                        <p>Belum ada riwayat stok masuk</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if(method_exists($stokMasuk, 'links'))
                    <div class="p-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-700/30">
                        {{ $stokMasuk->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Update satuan saat bahan dipilih
    const bahanSelect = document.getElementById('bahan_id');
    const satuanDisplay = document.getElementById('satuanDisplay');

    if (bahanSelect) {
        bahanSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const satuan = selectedOption.getAttribute('data-satuan');
            if (satuan) {
                satuanDisplay.innerHTML = satuan;
            } else {
                satuanDisplay.innerHTML = 'Kg / Unit';
            }
        });
    }

    // Hitung total harga otomatis
    const jumlahInput = document.getElementById('jumlah');
    const hargaSatuanInput = document.getElementById('harga_satuan');
    const totalHargaDisplay = document.getElementById('total_harga_display');
    const totalHargaHidden = document.getElementById('total_harga');

    function hitungTotalHarga() {
        const jumlah = parseFloat(jumlahInput?.value) || 0;
        const hargaSatuan = parseFloat(hargaSatuanInput?.value) || 0;
        const total = jumlah * hargaSatuan;
        
        if (totalHargaDisplay) {
            totalHargaDisplay.innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }
        if (totalHargaHidden) {
            totalHargaHidden.value = total;
        }
    }

    if (jumlahInput) jumlahInput.addEventListener('input', hitungTotalHarga);
    if (hargaSatuanInput) hargaSatuanInput.addEventListener('input', hitungTotalHarga);

    // Filter modal
    const btnFilter = document.getElementById('btnFilter');
    const filterModal = document.getElementById('filterModal');
    const btnCloseFilter = document.getElementById('btnCloseFilter');
    const btnApplyFilter = document.getElementById('btnApplyFilter');
    const btnRefresh = document.getElementById('btnRefresh');

    if (btnFilter) {
        btnFilter.addEventListener('click', () => {
            filterModal.classList.remove('hidden');
        });
    }

    if (btnCloseFilter) {
        btnCloseFilter.addEventListener('click', () => {
            filterModal.classList.add('hidden');
        });
    }

    if (filterModal) {
        filterModal.addEventListener('click', (e) => {
            if (e.target === filterModal) {
                filterModal.classList.add('hidden');
            }
        });
    }

    if (btnApplyFilter) {
        btnApplyFilter.addEventListener('click', async () => {
            const startDate = document.getElementById('filterStartDate').value;
            const endDate = document.getElementById('filterEndDate').value;
            const bahanId = document.getElementById('filterBahanId').value;
            const supplierId = document.getElementById('filterSupplierId').value;

            btnApplyFilter.innerHTML = 'Memuat...';
            btnApplyFilter.disabled = true;

            try {
                let url = '{{ route("stok-masuk.filter") }}?';
                if (startDate) url += `start_date=${startDate}&`;
                if (endDate) url += `end_date=${endDate}&`;
                if (bahanId) url += `bahan_id=${bahanId}&`;
                if (supplierId) url += `supplier_id=${supplierId}&`;

                const response = await fetch(url);
                const data = await response.json();

                const tbody = document.getElementById('stokMasukTableBody');
                
                if (!tbody) return;
                
                if (data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" class="px-3 py-8 text-center text-slate-500 dark:text-slate-400 text-sm">
                                <span class="material-symbols-outlined text-4xl mb-2">inventory</span>
                                <p>Tidak ada data yang sesuai dengan filter</p>
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = '';
                    data.forEach(item => {
                        tbody.innerHTML += `
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-400">
                                            <span class="material-symbols-outlined text-sm">inventory</span>
                                        </div>
                                        <span class="font-medium text-slate-900 dark:text-white text-xs">${item.bahan ? item.bahan.nama : 'Bahan Dihapus'}</span>
                                    </div>
                                 </td>
                                <td class="px-3 py-2 text-xs text-slate-600 dark:text-slate-400">
                                    ${item.supplier ? item.supplier.nama : '-'}
                                 </td>
                                <td class="px-3 py-2 text-center text-xs text-slate-700 dark:text-slate-300">
                                    ${parseFloat(item.jumlah).toLocaleString('id-ID')} ${item.bahan ? item.bahan.satuan : ''}
                                 </td>
                                <td class="px-3 py-2 text-center text-xs text-slate-600 dark:text-slate-400">
                                    Rp ${parseFloat(item.harga_satuan || 0).toLocaleString('id-ID')}
                                 </td>
                                <td class="px-3 py-2 text-center text-xs text-slate-500 dark:text-slate-400">
                                    ${new Date(item.tanggal_masuk).toLocaleDateString('id-ID')}
                                 </td>
                                <td class="px-3 py-2 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300">
                                        Verified
                                    </span>
                                 </td>
                                <td class="px-3 py-2 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="/stok-masuk/${item.id}" class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300" title="Detail">
                                            <span class="material-symbols-outlined text-base">visibility</span>
                                        </a>
                                        <button onclick="confirmDelete(${item.id})" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300" title="Hapus">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </div>
                                 </td>
                            </tr>
                        `;
                    });
                }

                filterModal.classList.add('hidden');
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data. Silakan coba lagi.');
            } finally {
                btnApplyFilter.innerHTML = 'Terapkan';
                btnApplyFilter.disabled = false;
            }
        });
    }

    if (btnRefresh) {
        btnRefresh.addEventListener('click', () => {
            window.location.reload();
        });
    }

    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data stok masuk ini? Stok bahan akan dikembalikan.')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/stok-masuk/${id}`;
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection