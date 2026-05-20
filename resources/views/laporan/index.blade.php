@extends('layouts.app')

@section('content')

<!-- Main Content -->
<main class="space-y-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Alert -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="font-h1 text-h1 text-on-surface">Laporan Operasional</h1>
            <p class="font-body-sm text-body-sm text-slate-500 mt-1">Analisis stok, pengeluaran, dan tren penggunaan bahan dapur.</p>
        </div>
        <!-- Tombol Export -->
        <div class="flex items-center gap-3">
            <a href="{{ route('laporan.export.excel') }}" 
            class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg text-sm font-medium transition flex items-center gap-1">
                <span class="material-symbols-outlined text-base">table_chart</span>
                 Export Excel
                 
            <a href="{{ route('laporan.export.pdf') }}" 
            class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-medium transition flex items-center gap-1">
                <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                 Export PDF
            </a>
        </div>
    </div>

        <!-- Filters Section -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <form id="filterForm" method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap items-center gap-6">
                <div class="space-y-1.5 flex-1 min-w-[240px]">
                    <label class="font-label-caps text-label-caps text-slate-500 block uppercase">Rentang Tanggal</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">calendar_today</span>
                        <select name="range" id="rangeSelect" class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-container outline-none appearance-none">
                            <option value="bulan_ini" {{ $range == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="7_hari" {{ $range == '7_hari' ? 'selected' : '' }}>7 Hari Terakhir</option>
                            <option value="30_hari" {{ $range == '30_hari' ? 'selected' : '' }}>30 Hari Terakhir</option>
                            <option value="custom" {{ $range == 'custom' ? 'selected' : '' }}>Kustom...</option>
                        </select>
                    </div>
                </div>
                
                <div id="customDateRange" class="space-y-1.5 flex-1 min-w-[240px] {{ $range != 'custom' ? 'hidden' : '' }}">
                    <label class="font-label-caps text-label-caps text-slate-500 block uppercase">Tanggal Kustom</label>
                    <div class="flex gap-2">
                        <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="flex-1 px-4 py-2 border border-slate-200 rounded-lg">
                        <span class="py-2">s/d</span>
                        <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" class="flex-1 px-4 py-2 border border-slate-200 rounded-lg">
                    </div>
                </div>
                
                <div class="space-y-1.5 flex-1 min-w-[200px]">
                    <label class="font-label-caps text-label-caps text-slate-500 block uppercase">Kategori Bahan</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">category</span>
                        <select id="kategoriFilter" name="kategori" class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-container outline-none appearance-none">
                            <option value="semua">Semua Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat }}">{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="space-y-1.5 flex-1 min-w-[200px]">
                    <label class="font-label-caps text-label-caps text-slate-500 block uppercase">Status Stok</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">filter_list</span>
                        <select id="statusFilter" name="status" class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary-container outline-none appearance-none">
                            <option value="semua">Semua Status</option>
                            <option value="aman">Stok Aman</option>
                            <option value="menipis">Stok Menipis</option>
                            <option value="habis">Stok Habis</option>
                        </select>
                    </div>
                </div>
                
                <div class="pt-6">
                    <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-opacity-90 transition-all flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-lg">refresh</span>
                        Update
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Total Pengeluaran -->
            <div class="md:col-span-2 bg-white p-8 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between overflow-hidden relative group">
                <div class="z-10">
                    <p class="font-label-caps text-label-caps text-slate-500 uppercase">Total Pengeluaran Periode Ini</p>
                    <h3 class="font-display-lg text-display-lg text-slate-900 mt-2">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $persenPerubahan >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            <span class="material-symbols-outlined text-xs mr-1">{{ $persenPerubahan >= 0 ? 'trending_up' : 'trending_down' }}</span>
                            {{ $persenPerubahan >= 0 ? '+' : '' }}{{ $persenPerubahan }}%
                        </span>
                        <span class="text-xs text-slate-400">vs periode lalu</span>
                    </div>
                </div>
                <div class="opacity-10 group-hover:opacity-20 transition-opacity absolute right-[-20px] bottom-[-20px]">
                    <span class="material-symbols-outlined text-[160px]">payments</span>
                </div>
            </div>

            <!-- Paling Banyak Digunakan -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <p class="font-label-caps text-label-caps text-slate-500 uppercase mb-4">Paling Banyak Digunakan</p>
                <div class="space-y-4">
                    @forelse($palingBanyakDigunakan as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                                    <span class="material-symbols-outlined text-slate-600">inventory</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $item->bahan->nama ?? 'Bahan Dihapus' }}</p>
                                    <p class="text-xs text-slate-500">{{ number_format($item->total, 2) }} {{ $item->bahan->satuan ?? 'unit' }} / periode</p>
                                </div>
                            </div>
                            <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 text-center">Belum ada data</p>
                    @endforelse
                </div>
            </div>

            <!-- Perlu Atensi -->
            <div class="bg-primary text-white p-6 rounded-xl shadow-lg flex flex-col justify-between">
                <div>
                    <p class="font-label-caps text-label-caps text-primary-fixed uppercase opacity-80">Perlu Re-Stock</p>
                    <h4 class="text-3xl font-bold mt-2">{{ $perluRestock }} Items</h4>
                    <p class="text-sm opacity-75 mt-1">Stok Menipis</p>
                    <p class="text-xs opacity-50 mt-2">{{ $stokHabis }} item sudah habis</p>
                </div>
                <button onclick="window.location.href='{{ route('bahan.index') }}?status=menipis'" class="mt-6 w-full py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-semibold transition-all">
                    Lihat Daftar
                </button>
            </div>
        </div>

        <!-- Chart & Activity Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Usage Trends Chart -->
            <div class="lg:col-span-2 bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="font-h2 text-h2 text-on-surface">Tren Penggunaan Bahan</h3>
                        <p class="text-sm text-slate-500">Frekuensi pengambilan stok keluar per minggu</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="flex items-center gap-1.5 text-xs text-slate-500">
                            <span class="w-3 h-3 bg-primary rounded-full"></span> Masuk
                        </span>
                        <span class="flex items-center gap-1.5 text-xs text-slate-500">
                            <span class="w-3 h-3 bg-secondary-container rounded-full"></span> Keluar
                        </span>
                    </div>
                </div>
                <div class="h-64 flex items-end justify-between gap-4 px-4" id="chartContainer">
                    @foreach($trenData as $index => $data)
                        @php
                            $maxValue = max(array_column($trenData, 'masuk') ?: [1]) ?: 1;
                            $tinggiMasuk = ($data['masuk'] / $maxValue) * 100;
                            $tinggiKeluar = ($data['keluar'] / $maxValue) * 100;
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-2 group">
                            <div class="w-full flex gap-1 items-end justify-center h-full">
                                <div class="w-4 bg-primary/20 rounded-t transition-all" style="height: {{ $tinggiMasuk }}%"></div>
                                <div class="w-4 bg-primary rounded-t transition-all" style="height: {{ $tinggiKeluar }}%"></div>
                            </div>
                            <span class="text-xs text-slate-400">Mg {{ $index + 1 }}</span>
                            <div class="absolute hidden group-hover:block bg-slate-800 text-white text-xs rounded px-2 py-1 -mt-8">
                                Masuk: {{ number_format($data['masuk'], 2) }}<br>
                                Keluar: {{ number_format($data['keluar'], 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col">
                <h3 class="font-h2 text-h2 text-on-surface mb-6">Aktivitas Terakhir</h3>
                <div class="space-y-6 flex-1 overflow-y-auto">
                    @forelse($aktivitasTerakhir as $activity)
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full {{ $activity['icon_bg'] }} flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined {{ $activity['icon_color'] }} text-sm">{{ $activity['icon'] }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $activity['title'] }}</p>
                                <p class="text-xs text-slate-500">{{ $activity['subtitle'] }} • {{ $activity['time'] }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-500 py-8">Belum ada aktivitas</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Detailed Data Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-h2 text-h2 text-on-surface">Rincian Penggunaan Bahan</h3>
                <button class="text-primary text-sm font-semibold hover:underline" onclick="window.location.href='{{ route('stok-keluar.index') }}'">
                    Lihat Semua
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500 uppercase">Nama Bahan</th>
                            <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500 uppercase">Kategori</th>
                            <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500 uppercase">Total Keluar</th>
                            <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500 uppercase">Nilai (Rp)</th>
                            <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="rincianTableBody">
                        @forelse($rincianBahan as $item)
                            @php $status = $item->status; @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $item->nama }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $item->kategori }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ number_format($item->total_keluar, 2) }} {{ $item->satuan }}</td>
                                <td class="px-6 py-4 text-slate-900 font-semibold">
                                    Rp {{ number_format($item->total_keluar * $item->harga_beli, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full font-status-badge text-status-badge {{ $status['badge'] }}">
                                        {{ $status['text'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <span class="material-symbols-outlined text-5xl mb-2">assessment</span>
                                    <p>Belum ada data penggunaan bahan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

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