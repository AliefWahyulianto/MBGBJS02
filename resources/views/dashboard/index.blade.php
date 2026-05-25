@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 p-8">
    
    <!-- Filter Tahun & Bulan -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-5 card-stagger">
        <form method="GET" action="{{ route('dashboard.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400 dark:text-slate-500">calendar_today</span>
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tahun:</label>
                <select name="tahun" class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Bulan:</label>
                <select name="bulan" class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-emerald-500">
                    <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                    <option value="1" {{ $bulan == 1 ? 'selected' : '' }}>Januari</option>
                    <option value="2" {{ $bulan == 2 ? 'selected' : '' }}>Februari</option>
                    <option value="3" {{ $bulan == 3 ? 'selected' : '' }}>Maret</option>
                    <option value="4" {{ $bulan == 4 ? 'selected' : '' }}>April</option>
                    <option value="5" {{ $bulan == 5 ? 'selected' : '' }}>Mei</option>
                    <option value="6" {{ $bulan == 6 ? 'selected' : '' }}>Juni</option>
                    <option value="7" {{ $bulan == 7 ? 'selected' : '' }}>Juli</option>
                    <option value="8" {{ $bulan == 8 ? 'selected' : '' }}>Agustus</option>
                    <option value="9" {{ $bulan == 9 ? 'selected' : '' }}>September</option>
                    <option value="10" {{ $bulan == 10 ? 'selected' : '' }}>Oktober</option>
                    <option value="11" {{ $bulan == 11 ? 'selected' : '' }}>November</option>
                    <option value="12" {{ $bulan == 12 ? 'selected' : '' }}>Desember</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2 bg-gradient-to-r from-emerald-500 to-emerald-700 hover:from-emerald-600 hover:to-emerald-800 text-white rounded-xl text-sm font-semibold shadow-md transition-all duration-300">
                <span class="material-symbols-outlined text-sm align-middle mr-1">refresh</span>
                Tampilkan
            </button>
            <a href="{{ route('dashboard.index') }}" class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 transition">Reset</a>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.export-pdf', ['tahun' => $tahun, 'bulan' => $bulan]) }}" 
                class="px-3 py-1.5 bg-red-50 dark:bg-red-900/30 hover:bg-red-100 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 rounded-lg text-sm font-medium transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                    Export PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Row 1: Welcome + Statistik -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Welcome Card -->
        <div class="lg:col-span-1 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-white shadow-xl card-stagger">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-3xl">restaurant</span>
                </div>
                <h2 class="text-xl font-bold">Dapur MBG</h2>
                <p class="text-emerald-100 text-sm mt-1">Bojongsari 02</p>
                <p class="text-emerald-100/80 text-xs mt-4 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">schedule</span>
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>

        <!-- Total Bahan -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm card-stagger">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 dark:text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Bahan</p>
                    <p class="text-3xl font-bold text-slate-800 dark:text-white mt-1">{{ $totalBahan }}</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">{{ $stokHabis }} bahan habis</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-2xl">inventory</span>
                </div>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm card-stagger">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-slate-400 dark:text-slate-500 text-xs font-semibold uppercase tracking-wider">Stok Menipis</p>
                    <p class="text-3xl font-bold text-orange-500 dark:text-orange-400 mt-1">{{ $stokMenipis }}</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">Segera restock</p>
                </div>
                <div class="w-12 h-12 bg-orange-50 dark:bg-orange-900/30 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-orange-500 dark:text-orange-400 text-2xl">warning</span>
                </div>
            </div>
        </div>

        <!-- Saldo -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl p-5 text-white shadow-xl card-stagger">
            <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Saldo Akhir</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                <div class="mt-3 flex items-center gap-2 text-emerald-400 text-xs">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    <span>Pemasukan - Pengeluaran</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Keuangan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Pemasukan -->
        <div class="bg-gradient-to-r from-emerald-50 to-white dark:from-emerald-950/30 dark:to-slate-800 rounded-2xl p-6 border border-emerald-100 dark:border-emerald-800/50 shadow-sm card-stagger">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-emerald-600 dark:text-emerald-400 text-xs font-semibold uppercase tracking-wider">Pemasukan {{ $tahun }}</p>
                    <p class="text-2xl font-bold text-emerald-700 dark:text-emerald-300 mt-1">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                    <div class="mt-3 flex items-center gap-2 text-emerald-600 dark:text-emerald-400 text-xs">
                        <span class="material-symbols-outlined text-sm">arrow_downward</span>
                        <span>Dari transaksi masuk</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/50 rounded-2xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-3xl">payments</span>
                </div>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-gradient-to-r from-red-50 to-white dark:from-red-950/30 dark:to-slate-800 rounded-2xl p-6 border border-red-100 dark:border-red-800/50 shadow-sm card-stagger">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-red-500 dark:text-red-400 text-xs font-semibold uppercase tracking-wider">Pengeluaran {{ $tahun }}</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-300 mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    <div class="mt-3 flex items-center gap-2 text-red-500 dark:text-red-400 text-xs">
                        <span class="material-symbols-outlined text-sm">arrow_upward</span>
                        <span>{{ $persenPerubahan >= 0 ? '+' : '' }}{{ $persenPerubahan }}% dari bulan lalu</span>
                    </div>
                </div>
                <div class="w-14 h-14 bg-red-100 dark:bg-red-900/50 rounded-2xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-500 dark:text-red-400 text-3xl">receipt</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 card-stagger">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-sm">show_chart</span>
                </div>
                <h3 class="font-semibold text-slate-800 dark:text-slate-200">Grafik Pengeluaran {{ $tahun }}</h3>
            </div>
            <canvas id="expensesChart" height="250"></canvas>
        </div>

       <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 card-stagger">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-sm">bar_chart</span>
                </div>
                <h3 class="font-semibold text-slate-800 dark:text-slate-200">Grafik Produksi {{ $tahun }}</h3>
            </div>
            <canvas id="productionChart" height="250"></canvas>
        </div>
    </div>

    <!-- Row 4: Top Lists -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top 5 Bahan -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 card-stagger">
            <div class="flex items-center gap-2 mb-6">
                 <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 card-stagger">
                    <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-sm">leaderboard</span>
                </div>
                <h3 class="font-semibold text-slate-800 dark:text-slate-200">🏆 Top 5 Bahan Terpakai {{ $tahun }}</h3>
            </div>
            <div class="space-y-5">
                @forelse($topBahan as $index => $item)
                    <div class="group">
                        <div class="flex justify-between text-sm mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center justify-center">{{ $index + 1 }}</span>
                                <span class="font-medium text-slate-700 dark:text-slate-300">{{ $item->bahan->nama ?? 'Bahan Dihapus' }}</span>
                            </div>
                            <span class="text-slate-600 dark:text-slate-400">{{ number_format($item->total, 2) }} {{ $item->bahan->satuan ?? '' }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                            @php $max = $topBahan->first()->total ?? 1; $persen = ($item->total / $max) * 100; @endphp
                            <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-2 rounded-full transition-all duration-500" style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500 dark:text-slate-400 text-center py-8">Belum ada data</p>
                @endforelse
            </div>
        </div>

        <!-- Menu Terlaris -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 card-stagger">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/50 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-orange-600 dark:text-orange-400 text-sm">restaurant_menu</span>
                </div>
                <h3 class="font-semibold text-slate-800 dark:text-slate-200">🍽️ Menu Terlaris {{ $tahun }}</h3>
            </div>
            <div class="space-y-5">
                @forelse($menuTerlaris as $index => $item)
                    <div class="group">
                        <div class="flex justify-between text-sm mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 text-xs font-bold flex items-center justify-center">{{ $index + 1 }}</span>
                                <span class="font-medium text-slate-700 dark:text-slate-300">{{ $item->menu->nama ?? 'Menu Dihapus' }}</span>
                            </div>
                            <span class="text-slate-600 dark:text-slate-400">{{ number_format($item->total) }} porsi</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                            @php $max = $menuTerlaris->first()->total ?? 1; $persen = ($item->total / $max) * 100; @endphp
                            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500 dark:text-slate-400 text-center py-8">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Row 5: Stok Terendah & Notifikasi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Stok Terendah -->
         <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 card-stagger">
            <div class="flex items-center gap-2 mb-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 card-stagger">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-sm">warning</span>
                </div>
                <h3 class="font-semibold text-slate-800 dark:text-slate-200">⚠️ Stok Kritis (Terendah)</h3>
            </div>
            <div class="space-y-4">
                @forelse($stokTerendah as $bahan)
                    <div class="flex justify-between items-center p-4 bg-gradient-to-r from-red-50 to-white dark:from-red-950/20 dark:to-slate-800 rounded-xl border border-red-100 dark:border-red-800/30 hover:shadow-md transition-all duration-300">
                        <div>
                            <p class="font-medium text-slate-800 dark:text-slate-200">{{ $bahan->nama }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Minimal: {{ number_format($bahan->stok_minimal, 2) }} {{ $bahan->satuan }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-red-500 dark:text-red-400">{{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }}</p>
                            <a href="{{ route('bahan.edit', $bahan) }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1 justify-end">
                                <span class="material-symbols-outlined text-xs">add</span>
                                Tambah Stok
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500 dark:text-slate-400 text-center py-8">Semua stok aman ✅</p>
                @endforelse
            </div>
        </div>

        <!-- Notifikasi -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 card-stagger">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/50 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-sm">notifications</span>
                    </div>
                    <h3 class="font-semibold text-slate-800 dark:text-slate-200">🔔 Notifikasi Terbaru</h3>
                </div>
                <a href="{{ route('notification.index') }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($notifikasiTerbaru as $notif)
                    <div class="flex items-start gap-3 p-3 bg-white dark:bg-slate-800/50 rounded-xl shadow-sm border-l-4 {{ $notif->is_read ? 'border-l-slate-200 dark:border-l-slate-700' : 'border-l-emerald-500' }}">
                        <span class="material-symbols-outlined text-slate-400 dark:text-slate-500 text-lg">circle_notifications</span>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $notif->title }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ Str::limit($notif->message, 60) }}</p>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$notif->is_read)
                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600">notifications_off</span>
                        <p class="text-slate-400 dark:text-slate-500 text-sm mt-2">Tidak ada notifikasi baru</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Row 6: Aktivitas Terbaru -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6 card-stagger">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-sm">history</span>
                </div>
                <h3 class="font-semibold text-slate-800 dark:text-slate-200">📋 Aktivitas Terbaru</h3>
            </div>
            <a href="{{ route('activity-log.index') }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-3">
            @forelse($aktivitasTerbaru as $activity)
                <div class="flex items-center gap-3 p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-xl transition-all duration-300">
                    <div class="w-10 h-10 rounded-full {{ $activity['icon_bg'] }} dark:bg-opacity-20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm {{ $activity['icon_color'] }}">{{ $activity['icon'] }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $activity['title'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ number_format($activity['jumlah'], 2) }} {{ $activity['satuan'] }} • {{ $activity['time'] }}</p>
                    </div>
                    <span class="text-xs text-slate-400 dark:text-slate-500">{{ $activity['time'] }}</span>
                </div>
            @empty
                <p class="text-slate-500 dark:text-slate-400 text-center py-8">Belum ada aktivitas</p>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const expensesData = @json($monthlyExpenses);
    const productionData = @json($monthlyProduction);
    
    const months = expensesData.map(item => item.month);
    const expensesAmounts = expensesData.map(item => item.amount);
    const productionAmounts = productionData.map(item => item.total);
    
    // Chart Pengeluaran
    const ctx1 = document.getElementById('expensesChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Pengeluaran (Rp)',
                data: expensesAmounts,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.05)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                },
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    ticks: { callback: value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value) },
                    grid: { color: '#e2e8f0' }
                },
                x: { grid: { display: false } }
            }
        }
    });
    
    // Chart Produksi
    const ctx2 = document.getElementById('productionChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Jumlah Porsi',
                data: productionAmounts,
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderRadius: 8,
                barPercentage: 0.65
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('id-ID').format(context.raw) + ' porsi';
                        }
                    }
                },
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    ticks: { callback: value => new Intl.NumberFormat('id-ID').format(value) },
                    grid: { color: '#e2e8f0' }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endsection