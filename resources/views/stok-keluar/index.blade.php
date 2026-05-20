@extends('layouts.app')

@section('content')
<!-- Main Content -->
<main class="space-y-6">
    <div class="grid grid-cols-12 gap-8 p-8">
        
        <!-- Alert Success -->
        @if(session('success'))
            <div class="col-span-12 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Alert Error -->
        @if(session('error'))
            <div class="col-span-12 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-red-500">error</span>
                {{ session('error') }}
            </div>
        @endif

        <!-- Left Column: Input Form + Summary Card -->
        <section class="col-span-12 lg:col-span-4 flex flex-col gap-6">
            
            <!-- Input Form Card -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-secondary-container/5 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <h2 class="font-h2 text-h2 text-slate-900 mb-1">Input Stok Keluar</h2>
                    <p class="font-body-sm text-body-sm text-slate-500 mb-6">Catat pengeluaran bahan untuk produksi hari ini.</p>
                    
                    <form action="{{ route('stok-keluar.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <!-- Pilih Bahan -->
                        <div class="space-y-2">
                            <label class="font-label-caps text-label-caps text-slate-500">Pilih Bahan</label>
                            <div class="relative">
                                <select name="bahan_id" id="bahan_id" required 
                                        class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none">
                                    <option value="">Cari bahan makanan...</option>
                                    @foreach($bahans as $bahan)
                                        <option value="{{ $bahan->id }}" data-satuan="{{ $bahan->satuan }}" data-stok="{{ $bahan->stok }}">
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

                        <!-- Jumlah Keluar -->
                        <div class="space-y-2">
                            <label class="font-label-caps text-label-caps text-slate-500">Jumlah Keluar</label>
                            <div class="relative">
                                <input type="text" name="jumlah" id="jumlah" required
                                       class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" 
                                       placeholder="0"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <span id="satuanText" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">UNIT</span>
                            </div>
                            <p id="stokWarning" class="text-xs text-red-500 hidden mt-1"></p>
                            @error('jumlah')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Pengeluaran -->
                        <div class="space-y-2">
                            <label class="font-label-caps text-label-caps text-slate-500">Tanggal Pengeluaran</label>
                            <div class="relative">
                                <input type="date" name="tanggal_keluar" required value="{{ date('Y-m-d') }}"
                                       class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                            @error('tanggal_keluar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div class="space-y-2">
                            <label class="font-label-caps text-label-caps text-slate-500">Keterangan</label>
                            <textarea name="keterangan" rows="2" 
                                      class="w-full bg-white border border-slate-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"
                                      placeholder="Contoh: Untuk produksi menu Nasi Goreng"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2 group">
                            <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">outbox</span>
                            Simpan Pengeluaran
                        </button>
                    </form>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="bg-primary border border-primary-container rounded-xl p-6 text-white overflow-hidden relative group">
                <div class="absolute -bottom-8 -right-8 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <span class="material-symbols-outlined text-9xl">trending_down</span>
                </div>
                <p class="font-label-caps text-label-caps text-primary-fixed/80">TOTAL KELUAR HARI INI</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <h3 class="font-display-lg text-display-lg">{{ number_format($totalStokKeluarHariIni, 2) }}</h3>
                    <span class="text-primary-fixed/80 font-medium">UNIT</span>
                </div>
                <div class="mt-4 flex items-center gap-2 text-primary-fixed text-xs">
                    <span class="material-symbols-outlined text-xs">inventory</span>
                    <span>Dari {{ $totalTransaksiHariIni }} transaksi hari ini</span>
                </div>
            </div>
        </section>

        <!-- Right Column: History Table -->
        <section class="col-span-12 lg:col-span-8">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                
                <!-- Table Header -->
                    <div class="px-6 py-5 border-b border-slate-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="font-h2 text-h2 text-slate-900">Riwayat Stok Keluar</h2>
                                <p class="font-body-sm text-body-sm text-slate-500 mt-1">Menampilkan data pengeluaran terakhir.</p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <div class="flex gap-2">
                                    <button id="btnFilter" class="text-emerald-600 font-semibold text-sm flex items-center gap-1 hover:underline">
                                        <span class="material-symbols-outlined text-sm">filter_list</span>
                                         Filter
                                     </button>
                                    <button id="btnRefresh" class="text-emerald-600 font-semibold text-sm flex items-center gap-1 hover:underline">
                                        <span class="material-symbols-outlined text-sm">refresh</span>
                                        Refresh
                                    </button>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('stok-keluar.export.excel') }}" 
                                    class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg text-sm font-medium transition flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">table_chart</span>
                                        Export Excel
                                    </a>
                                    <a href="{{ route('stok-keluar.export.pdf') }}" 
                                    class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-medium transition flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                                        Export PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Modal Filter -->
                <div id="filterModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                    <div class="bg-white rounded-xl p-6 w-96">
                        <h3 class="font-bold text-lg mb-4">Filter Riwayat</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Dari Tanggal</label>
                                <input type="date" id="filterStartDate" class="w-full border border-slate-200 rounded-lg px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Sampai Tanggal</label>
                                <input type="date" id="filterEndDate" class="w-full border border-slate-200 rounded-lg px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Bahan</label>
                                <select id="filterBahanId" class="w-full border border-slate-200 rounded-lg px-3 py-2">
                                    <option value="">Semua Bahan</option>
                                    @foreach($bahans as $bahan)
                                        <option value="{{ $bahan->id }}">{{ $bahan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-6">
                            <button id="btnCloseFilter" class="px-4 py-2 border border-slate-200 rounded-lg hover:bg-slate-50">Batal</button>
                            <button id="btnApplyFilter" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Terapkan</button>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500">Bahan Makanan</th>
                                <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500">Jumlah</th>
                                <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500">Tanggal</th>
                                <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="stokKeluarTableBody">
                            @forelse($stokKeluar as $item)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded bg-orange-100 flex items-center justify-center text-orange-600">
                                                <span class="material-symbols-outlined text-sm">outbox</span>
                                            </div>
                                            <span class="font-semibold text-slate-900">{{ $item->bahan->nama ?? 'Bahan Dihapus' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-700">
                                        {{ number_format($item->jumlah, 2) }} {{ $item->bahan->satuan ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500">
                                        {{ $item->keterangan ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                        <span class="material-symbols-outlined text-5xl mb-2">outbox</span>
                                        <p>Belum ada riwayat stok keluar</p>
                                        <p class="text-xs mt-1">Silakan catat pengeluaran stok pertama Anda</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($stokKeluar, 'links'))
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                        {{ $stokKeluar->links() }}
                    </div>
                @endif
            </div>

            <!-- Info Cards -->
            <div class="mt-8 grid grid-cols-2 gap-6">
                <!-- Rekomendasi Beli -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                        <span class="material-symbols-outlined">warning</span>
                    </div>
                    <div>
                        <p class="font-label-caps text-label-caps text-slate-500">REKOMENDASI BELI</p>
                        <p class="font-bold text-slate-900 mt-1">{{ $stokMenipis }} Bahan Stok Rendah</p>
                        <p class="text-[10px] text-slate-400">Segera lakukan pemesanan ulang</p>
                    </div>
                </div>

                <!-- Total Stok Keluar -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <span class="material-symbols-outlined">analytics</span>
                    </div>
                    <div>
                        <p class="font-label-caps text-label-caps text-slate-500">TOTAL STOK KELUAR</p>
                        <p class="font-bold text-slate-900 mt-1">{{ number_format($totalStokKeluarSemua, 2) }} Unit</p>
                        <p class="text-[10px] text-slate-400">Akumulasi semua pengeluaran</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<script>
    // Update satuan dan cek stok saat bahan dipilih
    const bahanSelect = document.getElementById('bahan_id');
    const satuanText = document.getElementById('satuanText');
    const jumlahInput = document.getElementById('jumlah');
    const stokWarning = document.getElementById('stokWarning');

    if (bahanSelect) {
        bahanSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const satuan = selectedOption.getAttribute('data-satuan');
            const stokTersedia = parseFloat(selectedOption.getAttribute('data-stok'));
            
            if (satuan) {
                satuanText.innerHTML = satuan;
            } else {
                satuanText.innerHTML = 'UNIT';
            }
            
            // Validasi stok saat jumlah diinput
            jumlahInput.addEventListener('input', function() {
                const jumlah = parseFloat(this.value) || 0;
                if (stokTersedia < jumlah) {
                    stokWarning.innerHTML = `Stok tidak mencukupi! Tersedia: ${stokTersedia} ${satuan}`;
                    stokWarning.classList.remove('hidden');
                } else {
                    stokWarning.classList.add('hidden');
                }
            });
        });
    }

    // ========== FILTER MODAL ==========
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

            btnApplyFilter.innerHTML = 'Memuat...';
            btnApplyFilter.disabled = true;

            try {
                let url = '{{ route("stok-keluar.filter") }}?';
                if (startDate) url += `start_date=${startDate}&`;
                if (endDate) url += `end_date=${endDate}&`;
                if (bahanId) url += `bahan_id=${bahanId}&`;

                const response = await fetch(url);
                const data = await response.json();

                const tbody = document.getElementById('stokKeluarTableBody');
                
                if (!tbody) return;
                
                if (data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                <span class="material-symbols-outlined text-5xl mb-2">outbox</span>
                                <p>Tidak ada data yang sesuai dengan filter</p>
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = '';
                    data.forEach(item => {
                        tbody.innerHTML += `
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-orange-100 flex items-center justify-center text-orange-600">
                                            <span class="material-symbols-outlined text-sm">outbox</span>
                                        </div>
                                        <span class="font-semibold text-slate-900">${item.bahan ? item.bahan.nama : 'Bahan Dihapus'}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">
                                    ${parseFloat(item.jumlah).toLocaleString('id-ID')} ${item.bahan ? item.bahan.satuan : ''}
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    ${new Date(item.tanggal_keluar).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'})}
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    ${item.keterangan || '-'}
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
</script>
@endsection