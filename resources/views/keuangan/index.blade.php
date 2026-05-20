@extends('layouts.app')

@section('content')
<!-- MAIN CONTENT - Dashboard Keuangan -->
<main class="space-y-6">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Dashboard Keuangan</h1>
        <p class="text-slate-500 text-sm mt-1">Pantau arus kas masuk dan keluar secara transparan</p>
    </div>
    <div class="flex gap-3">
        <!-- Tombol Export -->
        <a href="{{ route('keuangan.export.excel') }}" 
           class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg text-sm font-medium transition flex items-center gap-1">
            <span class="material-symbols-outlined text-base">table_chart</span>
            Export Excel
        </a>
        <a href="{{ route('keuangan.export.pdf') }}" 
           class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-medium transition flex items-center gap-1">
            <span class="material-symbols-outlined text-base">picture_as_pdf</span>
            Export PDF
        </a>
        <a href="{{ route('keuangan.create') }}" 
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-semibold transition">
            <span class="material-symbols-outlined text-lg">add</span>
            Tambah Transaksi
        </a>
    </div>
</div>

        <!-- FILTER TANGGAL -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-6">
            <form method="GET" action="{{ route('keuangan.index') }}" class="flex flex-wrap items-center gap-4">
                <div>
                    <label class="text-xs text-slate-500 block mb-1">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" 
                           class="px-4 py-2 border border-slate-200 rounded-lg text-sm">
                </div>
                <div>
                    <label class="text-xs text-slate-500 block mb-1">Sampai Tanggal</label>
                    <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" 
                           class="px-4 py-2 border border-slate-200 rounded-lg text-sm">
                </div>
                <button type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded-lg text-sm font-semibold mt-5">
                    Filter
                </button>
            </form>
        </div>

        <!-- STATISTIK KARTU -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Pemasukan -->
            <!-- Contoh untuk menampilkan angka 600000000 menjadi 600 Juta -->
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-500 text-xs font-semibold uppercase">Total Pemasukan</p>
                            <p class="text-2xl font-bold text-emerald-600 mt-1">
                                Rp {{ number_format($totalMasuk / 1000000, 0, ',', '.') }} Juta
                                <!-- Atau pakai cara ini: -->
                                <!-- Rp {{ number_format($totalMasuk, 0, ',', '.') }} -->
                            </p>
                        </div>
                    </div>
                </div>

            <!-- Total Pengeluaran -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase">Total Pengeluaran</p>
                        <p class="text-2xl font-bold text-red-500 mt-1">
                            Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-500">arrow_upward</span>
                    </div>
                </div>
            </div>

            <!-- Saldo -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase">Saldo Saat Ini</p>
                        <p class="text-2xl font-bold {{ $saldo >= 0 ? 'text-emerald-600' : 'text-red-500' }} mt-1">
                            Rp {{ number_format($saldo, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-500">account_balance</span>
                    </div>
                </div>
            </div>

            <!-- Total Transaksi -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase">Total Transaksi</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1">{{ $statistik['total_transaksi'] }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-500">receipt</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRAFIK ARUS KAS (Chart.js) -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Grafik Arus Kas</h2>
                    <p class="text-sm text-slate-500">Pemasukan vs Pengeluaran per hari</p>
                </div>
            </div>
            <canvas id="cashFlowChart" height="100"></canvas>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- KATEGORI PENGELUARAN TERBESAR -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Top 5 Kategori Pengeluaran</h2>
                <div class="space-y-4">
                    @foreach($kategoriKeluar as $kat)
                        @php $persen = $totalKeluar > 0 ? ($kat->total / $totalKeluar) * 100 : 0; @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-slate-700">{{ $kat->kategori }}</span>
                                <span class="text-slate-500">Rp {{ number_format($kat->total, 0, ',', '.') }} ({{ round($persen) }}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $persen }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- TRANSAKSI TERBARU -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-800">Transaksi Terbaru</h2>
                    <a href="{{ route('keuangan.laporan') }}" class="text-emerald-600 text-sm hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse($transaksiTerbaru as $trx)
                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-lg transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full {{ $trx->jenis == 'masuk' ? 'bg-emerald-100' : 'bg-red-100' }} flex items-center justify-center">
                                    <span class="material-symbols-outlined {{ $trx->jenis == 'masuk' ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $trx->jenis == 'masuk' ? 'arrow_downward' : 'arrow_upward' }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 text-sm">{{ $trx->kode_transaksi }}</p>
                                    <p class="text-xs text-slate-500">{{ $trx->kategori }} • {{ $trx->sumber_tujuan ?: '-' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold {{ $trx->jenis == 'masuk' ? 'text-emerald-600' : 'text-red-500' }}">
                                    {{ $trx->jenis == 'masuk' ? '+' : '-' }} Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-slate-400">{{ $trx->tanggal_transaksi->format('d M Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-500 py-8">Belum ada transaksi</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>

<!-- SCRIPT CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('cashFlowChart').getContext('2d');
        
        const chartData = @json($chartData);
        const labels = chartData.map(item => item.tanggal);
        const masukData = chartData.map(item => item.masuk);
        const keluarData = chartData.map(item => item.keluar);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: masukData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Pengeluaran',
                        data: keluarData,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection