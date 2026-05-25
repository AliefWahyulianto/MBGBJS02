@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6 fade-in-up">
    
    <!-- Alert -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <!-- Page Header -->
    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Stok Keluar</h1>
            <p class="text-slate-500 text-sm">Catat pengeluaran bahan untuk produksi</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('stok-keluar.export.excel') }}" class="px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-lg text-sm font-medium transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">table_chart</span>
                    Export Excel
                </a>
            <a href="{{ route('stok-keluar.export.pdf') }}" class="px-3 py-1.5 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-lg text-sm font-medium transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                    Export PDF
                </a>
        </div>
    </div>

    <!-- 2 Kolom: Form Kiri + Tabel Kanan -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- FORM KIRI -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-semibold text-slate-800 mb-4">Input Stok Keluar</h2>
                <form action="{{ route('stok-keluar.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Pilih Bahan</label>
                        <select name="bahan_id" id="bahan_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">-- Pilih Bahan --</option>
                            @foreach($bahans as $bahan)
                                <option value="{{ $bahan->id }}" data-satuan="{{ $bahan->satuan }}" data-stok="{{ $bahan->stok }}">
                                    {{ $bahan->nama }} (Stok: {{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Jumlah Keluar</label>
                        <div class="relative">
                            <input type="text" name="jumlah" id="jumlah" required class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <span id="satuanText" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">UNIT</span>
                        </div>
                        <p id="stokWarning" class="text-xs text-red-500 hidden mt-1"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Tanggal Pengeluaran</label>
                        <input type="date" name="tanggal_keluar" value="{{ date('Y-m-d') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Contoh: Untuk produksi Nasi Goreng"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-2 rounded-lg font-semibold text-sm hover:bg-primary/90">📤 Simpan Pengeluaran</button>
                </form>
            </div>

            <!-- Summary Card -->
            <div class="bg-primary rounded-xl p-5 text-white">
                <p class="text-xs opacity-80">TOTAL KELUAR HARI INI</p>
                <p class="text-2xl font-bold">{{ number_format($totalStokKeluarHariIni, 2) }} <span class="text-sm font-normal">UNIT</span></p>
                <p class="text-xs opacity-80 mt-1">Dari {{ $totalTransaksiHariIni }} transaksi</p>
            </div>
        </div>

        <!-- TABEL KANAN -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b bg-slate-50 flex justify-between items-center flex-wrap gap-2">
                    <h3 class="font-semibold text-slate-800">Riwayat Stok Keluar</h3>
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

                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left table-auto border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase border-b border-slate-200">Bahan</th>
                                <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase text-center border-b border-slate-200">Jumlah</th>
                                <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase text-center border-b border-slate-200">Tanggal</th>
                                <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase border-b border-slate-200">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stokKeluar as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition-colors table-row-stagger border-b border-slate-100" style="animation-delay: {{ 0.03 * ($index + 1) }}s">
                                    <td class="px-2 py-2 align-middle border-b border-slate-100">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-6 h-6 rounded bg-orange-100 flex items-center justify-center flex-shrink-0">
                                                <span class="material-symbols-outlined text-sm text-orange-600">outbox</span>
                                            </div>
                                            <span class="font-medium text-slate-800 text-xs truncate max-w-[140px]" title="{{ $item->bahan->nama ?? 'Bahan Dihapus' }}">
                                                {{ $item->bahan->nama ?? 'Bahan Dihapus' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-2 text-center text-xs text-slate-700 align-middle border-b border-slate-100 whitespace-nowrap">
                                        {{ number_format($item->jumlah, 0) }} <span class="text-[10px] text-slate-400">{{ $item->bahan->satuan ?? '' }}</span>
                                    </td>
                                    <td class="px-2 py-2 text-center text-xs text-slate-500 align-middle border-b border-slate-100 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-2 py-2 text-xs text-slate-500 align-middle border-b border-slate-100 truncate max-w-[180px]" title="{{ $item->keterangan ?? '-' }}">
                                        {{ \Illuminate\Support\Str::limit($item->keterangan ?? '-', 30) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-2 py-8 text-center text-slate-400 text-sm border-b border-slate-100">
                                        <span class="material-symbols-outlined text-4xl mb-2">outbox</span>
                                        <p>Belum ada riwayat stok keluar</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($stokKeluar, 'links'))
                    <div class="px-4 py-3 border-t bg-slate-50">
                        {{ $stokKeluar->links() }}
                    </div>
                @endif
            </div>

            <!-- Info Cards -->
            <div class="mt-6 grid grid-cols-2 gap-4">
                <div class="bg-white border rounded-xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-orange-600 text-lg">warning</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold text-slate-500">REKOMENDASI BELI</p>
                        <p class="font-bold text-slate-800 text-sm">{{ $stokMenipis }} Bahan Stok Rendah</p>
                    </div>
                </div>
                <div class="bg-white border rounded-xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-emerald-600 text-lg">analytics</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold text-slate-500">TOTAL STOK KELUAR</p>
                        <p class="font-bold text-slate-800 text-sm">{{ number_format($totalStokKeluarSemua, 2) }} Unit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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