@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 p-8 fade-in-up">

    <!-- Alert -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl flex items-center gap-2 card-stagger">
            <span class="material-symbols-outlined text-emerald-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 card-stagger">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Laporan Operasional</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Analisis stok, pengeluaran, dan tren penggunaan bahan dapur.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('laporan.export.excel') }}" 
               class="px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-lg text-sm font-medium transition flex items-center gap-1">
                <span class="material-symbols-outlined text-base">table_chart</span>
                Export Excel
            </a>
            <a href="{{ route('laporan.export.pdf') }}" 
               class="px-3 py-1.5 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-lg text-sm font-medium transition flex items-center gap-1">
                <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                Export PDF
            </a>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-5 card-stagger" style="animation-delay: 0.05s">
        <form id="filterForm" method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Rentang Tanggal</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">calendar_today</span>
                    <select name="range" id="rangeSelect" class="w-full pl-10 pr-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                        <option value="bulan_ini" {{ $range == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="7_hari" {{ $range == '7_hari' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30_hari" {{ $range == '30_hari' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="custom" {{ $range == 'custom' ? 'selected' : '' }}>Kustom...</option>
                    </select>
                </div>
            </div>
            
            <div id="customDateRange" class="flex-1 min-w-[240px] {{ $range != 'custom' ? 'hidden' : '' }}">
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Tanggal Kustom</label>
                <div class="flex gap-2">
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" 
                           class="flex-1 px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                    <span class="py-2 text-slate-400">s/d</span>
                    <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" 
                           class="flex-1 px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                </div>
            </div>
            
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Kategori Bahan</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">category</span>
                    <select name="kategori" class="w-full pl-10 pr-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                        <option value="semua">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat }}">{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Status Stok</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">filter_list</span>
                    <select name="status" class="w-full pl-10 pr-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                        <option value="semua">Semua Status</option>
                        <option value="aman">Stok Aman</option>
                        <option value="menipis">Stok Menipis</option>
                        <option value="habis">Stok Habis</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-gradient-primary text-white px-5 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2 shadow-md hover:shadow-lg">
                    <span class="material-symbols-outlined text-lg">refresh</span>
                    Update
                </button>
            </div>
        </form>
    </div>

    <!-- STATISTIK CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Pengeluaran -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm card-stagger">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase">Total Pengeluaran</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $persenPerubahan >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            <span class="material-symbols-outlined text-xs mr-0.5">{{ $persenPerubahan >= 0 ? 'trending_up' : 'trending_down' }}</span>
                            {{ $persenPerubahan >= 0 ? '+' : '' }}{{ $persenPerubahan }}%
                        </span>
                        <span class="text-[10px] text-slate-400">vs periode lalu</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-primary/10 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary text-2xl">payments</span>
                </div>
            </div>
        </div>

        <!-- Paling Banyak Digunakan -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm card-stagger" style="animation-delay: 0.05s">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase mb-4">Paling Banyak Digunakan</p>
            <div class="space-y-4">
                @forelse($palingBanyakDigunakan as $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 text-sm">inventory</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $item->bahan->nama ?? 'Bahan Dihapus' }}</p>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400">{{ number_format($item->total, 2) }} {{ $item->bahan->satuan ?? 'unit' }} / periode</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-emerald-500 text-sm">check_circle</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>

        <!-- Perlu Restock -->
        <div class="bg-gradient-primary rounded-xl p-6 text-white shadow-lg card-stagger" style="animation-delay: 0.1s">
            <div>
                <p class="text-xs font-semibold uppercase opacity-80">Perlu Re-Stock</p>
                <p class="text-3xl font-bold mt-1">{{ $perluRestock }} Items</p>
                <p class="text-xs opacity-75 mt-1">Stok Menipis</p>
                <p class="text-[10px] opacity-50 mt-1">{{ $stokHabis }} item sudah habis</p>
            </div>
            <button onclick="window.location.href='{{ route('bahan.index') }}?status=menipis'" class="mt-4 w-full py-2 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-semibold transition">
                Lihat Daftar
            </button>
        </div>
    </div>

    <!-- CHART & AKTIVITAS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 card-stagger" style="animation-delay: 0.15s">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-semibold text-slate-800 dark:text-white">Tren Penggunaan Bahan</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Frekuensi pengambilan stok keluar per minggu</p>
                </div>
                <div class="flex gap-3">
                    <span class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400">
                        <span class="w-2.5 h-2.5 bg-primary rounded-full"></span> Masuk
                    </span>
                    <span class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400">
                        <span class="w-2.5 h-2.5 bg-secondary-container rounded-full"></span> Keluar
                    </span>
                </div>
            </div>
            <div class="h-56 flex items-end justify-between gap-3 px-2">
                @foreach($trenData as $index => $data)
                    @php
                        $maxValue = max(array_column($trenData, 'masuk') ?: [1]) ?: 1;
                        $tinggiMasuk = ($data['masuk'] / $maxValue) * 100;
                        $tinggiKeluar = ($data['keluar'] / $maxValue) * 100;
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-1 group relative">
                        <div class="w-full flex gap-1 items-end justify-center h-40">
                            <div class="w-3 bg-primary/20 rounded-t transition-all group-hover:bg-primary/40" style="height: {{ $tinggiMasuk }}%"></div>
                            <div class="w-3 bg-primary rounded-t transition-all group-hover:bg-primary/60" style="height: {{ $tinggiKeluar }}%"></div>
                        </div>
                        <span class="text-[10px] text-slate-400">Mg {{ $index + 1 }}</span>
                        <div class="absolute hidden group-hover:block bg-slate-800 dark:bg-slate-700 text-white text-[10px] rounded px-2 py-1 -top-8 whitespace-nowrap z-10">
                            M:{{ number_format($data['masuk'], 0) }} | K:{{ number_format($data['keluar'], 0) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Aktivitas Terakhir -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 card-stagger" style="animation-delay: 0.2s">
            <h3 class="font-semibold text-slate-800 dark:text-white mb-5">Aktivitas Terakhir</h3>
            <div class="space-y-4">
                @forelse($aktivitasTerakhir as $activity)
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-full {{ $activity['icon_bg'] }} flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined {{ $activity['icon_color'] }} text-sm">{{ $activity['icon'] }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ $activity['title'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $activity['subtitle'] }} • {{ $activity['time'] }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500 dark:text-slate-400 py-6 text-sm">Belum ada aktivitas</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- TABEL RINCIAN -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden card-stagger" style="animation-delay: 0.25s">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-700/30">
            <h3 class="font-semibold text-slate-800 dark:text-white">Rincian Penggunaan Bahan</h3>
            <button onclick="window.location.href='{{ route('stok-keluar.index') }}'" class="text-primary text-xs font-semibold hover:underline">
                Lihat Semua
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-600">
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Nama Bahan</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Kategori</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase text-center">Total Keluar</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase text-right">Nilai (Rp)</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($rincianBahan as $index => $item)
                        @php $status = $item->status; @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors table-row-stagger" 
                            style="animation-delay: {{ 0.02 * ($index + 1) }}s">
                            <td class="px-4 py-3 text-sm font-medium text-slate-800 dark:text-white">{{ $item->nama }}</td>
                            <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $item->kategori }}</td>
                            <td class="px-4 py-3 text-center text-sm text-slate-600 dark:text-slate-300">
                                {{ number_format($item->total_keluar, 2) }} {{ $item->satuan }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-slate-800 dark:text-white">
                                Rp {{ number_format($item->total_keluar * $item->harga_beli, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $status['badge'] }}">
                                    {{ $status['text'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-slate-400 dark:text-slate-500">
                                <span class="material-symbols-outlined text-4xl mb-2">assessment</span>
                                <p>Belum ada data penggunaan bahan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($rincianBahan, 'links'))
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
                {{ $rincianBahan->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    // Tampilkan/sembunyikan custom date range
    const rangeSelect = document.getElementById('rangeSelect');
    const customDateRange = document.getElementById('customDateRange');
    
    rangeSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customDateRange.classList.remove('hidden');
        } else {
            customDateRange.classList.add('hidden');
        }
    });
    
    // Export Excel
    document.getElementById('btnExportExcel').addEventListener('click', function() {
        alert('Fitur Export Excel akan segera hadir');
    });
    
    // Export PDF
    document.getElementById('btnExportPDF').addEventListener('click', function() {
        alert('Fitur Export PDF akan segera hadir');
    });
    
    // Filter via AJAX untuk tabel rincian (opsional)
    const kategoriFilter = document.getElementById('kategoriFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    function filterTable() {
        const kategori = kategoriFilter.value;
        const status = statusFilter.value;
        const startDate = document.querySelector('[name="tanggal_mulai"]')?.value || '{{ $tanggalMulai }}';
        const endDate = document.querySelector('[name="tanggal_selesai"]')?.value || '{{ $tanggalSelesai }}';
        
        fetch(`{{ route('laporan.filter') }}?kategori=${kategori}&status=${status}&tanggal_mulai=${startDate}&tanggal_selesai=${endDate}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('rincianTableBody');
                tbody.innerHTML = '';
                
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-12 text-center text-slate-500">Tidak ada data</td></tr>';
                } else {
                    data.forEach(item => {
                        let statusClass = '';
                        if (item.stok <= 0) statusClass = 'bg-red-100 text-red-700';
                        else if (item.stok <= item.stok_minimal) statusClass = 'bg-orange-100 text-orange-700';
                        else statusClass = 'bg-emerald-100 text-emerald-700';
                        
                        let statusText = '';
                        if (item.stok <= 0) statusText = 'Habis';
                        else if (item.stok <= item.stok_minimal) statusText = 'Menipis';
                        else statusText = 'Aman';
                        
                        tbody.innerHTML += `
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900">${item.nama}</td>
                                <td class="px-6 py-4 text-slate-500">${item.kategori}</td>
                                <td class="px-6 py-4 text-slate-600">${parseFloat(item.total_keluar || 0).toLocaleString('id-ID')} ${item.satuan}</td>
                                <td class="px-6 py-4 text-slate-900 font-semibold">Rp ${((item.total_keluar || 0) * item.harga_beli).toLocaleString('id-ID')}</td>
                                <td class="px-6 py-4"><span class="px-3 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span></td>
                            </tr>
                        `;
                    });
                }
            });
    }
    
    // Optional: panggil filter saat kategori atau status berubah
    // kategoriFilter.addEventListener('change', filterTable);
    // statusFilter.addEventListener('change', filterTable);
</script>
@endsection