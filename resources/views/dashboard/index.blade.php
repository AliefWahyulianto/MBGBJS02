@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto space-y-8">
    
    <!-- Welcome Section -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="font-h1 text-h1 text-on-background">Dashboard Operasional</h2>
            <p class="font-body-sm text-body-sm text-slate-500">Pantau stok, pengeluaran, dan aktivitas dapur secara real-time.</p>
        </div>
        <div class="flex gap-3">
            <button id="btn7Hari" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-semibold hover:bg-slate-50 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">calendar_today</span>
                7 Hari Terakhir
            </button>
            <a href="{{ route('stok-masuk.index') }}" class="px-4 py-2 bg-primary-container text-white rounded-lg text-sm font-semibold shadow-sm hover:bg-primary transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">add</span>
                Stok Baru
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1: Total Bahan -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-emerald-50 rounded-lg text-primary">
                    <span class="material-symbols-outlined">inventory</span>
                </div>
                <span class="font-status-badge text-status-badge px-2 py-1 bg-emerald-50 text-primary rounded-full uppercase tracking-wider font-bold">Safe</span>
            </div>
            <div class="mt-4">
                <p class="font-label-caps text-label-caps text-slate-400 uppercase">Total Bahan</p>
                <h3 class="font-display-lg text-display-lg text-on-background">{{ $totalBahan }}</h3>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs {{ $persenStokMenipis <= 0 ? 'text-primary' : 'text-error' }}">{{ $persenStokMenipis <= 0 ? 'trending_down' : 'trending_up' }}</span>
                    <span class="font-bold {{ $persenStokMenipis <= 0 ? 'text-primary' : 'text-error' }}">{{ $persenStokMenipis >= 0 ? '+' : '' }}{{ $persenStokMenipis }}%</span> 
                    dari periode lalu
                </p>
            </div>
        </div>

        <!-- Card 2: Stok Menipis -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-orange-50 rounded-lg text-orange-500">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                <span class="font-status-badge text-status-badge px-2 py-1 bg-orange-50 text-orange-600 rounded-full uppercase tracking-wider font-bold">Low Stock</span>
            </div>
            <div class="mt-4">
                <p class="font-label-caps text-label-caps text-slate-400 uppercase">Stok Menipis</p>
                <h3 class="font-display-lg text-display-lg text-orange-600">{{ $stokMenipis }}</h3>
                <p class="text-xs text-slate-500 mt-1">Segera lakukan pemesanan ulang</p>
            </div>
        </div>

        <!-- Card 3: Pengeluaran -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-blue-50 rounded-lg text-blue-500">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <span class="font-status-badge text-status-badge px-2 py-1 bg-blue-50 text-blue-600 rounded-full uppercase tracking-wider font-bold">Financial</span>
            </div>
            <div class="mt-4">
                <p class="font-label-caps text-label-caps text-slate-400 uppercase">Pengeluaran Hari Ini</p>
                <h3 class="font-display-lg text-display-lg text-on-background" id="pengeluaranHariIni">Rp {{ number_format($pengeluaranHariIni, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs {{ $persenPerubahanPengeluaran <= 0 ? 'text-error' : 'text-primary' }}">{{ $persenPerubahanPengeluaran <= 0 ? 'trending_down' : 'trending_up' }}</span>
                    <span class="font-bold {{ $persenPerubahanPengeluaran <= 0 ? 'text-error' : 'text-primary' }}" id="persenPerubahan">{{ $persenPerubahanPengeluaran >= 0 ? '+' : '' }}{{ $persenPerubahanPengeluaran }}%</span> 
                    dari kemarin
                </p>
            </div>
        </div>
    </div>

    <!-- Chart & Popular Categories -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Weekly Expenses Chart -->
        <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="font-h2 text-h2 text-on-background">Pengeluaran Mingguan</h3>
                    <p class="text-sm text-slate-500">Analisis biaya bahan baku operasional 7 hari terakhir.</p>
                </div>
                <div class="flex gap-2">
                    <span class="w-3 h-3 rounded-full bg-primary-container"></span>
                    <span class="text-xs font-bold text-slate-400">EXPENSES (IDR)</span>
                </div>
            </div>
            <div class="p-6 flex-1 relative min-h-[300px]">
                <div class="absolute inset-x-8 bottom-12 top-8 flex items-end justify-between gap-4">
                    @foreach($weeklyExpenses as $expense)
                        <div class="flex-1 bg-slate-50 relative group rounded-t-lg transition-all hover:bg-emerald-50" style="height: {{ $expense['height'] }}%;">
                            <div class="absolute bottom-0 w-full bg-primary-container/20 rounded-t-lg transition-all" style="height: 100%;"></div>
                            <div class="absolute bottom-0 w-full bg-primary-container rounded-t-lg transition-all opacity-0 group-hover:opacity-100" style="height: {{ $expense['height'] }}%;"></div>
                            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-slate-400">{{ $expense['day'] }}</span>
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-xs rounded px-2 py-1 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                Rp {{ number_format($expense['amount'], 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Popular Categories & Tips -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">
            <h3 class="font-h2 text-h2 text-on-background">Kategori Bahan</h3>
            <div class="space-y-4">
                @forelse($kategoriTerpopuler as $kat)
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-sm">
                                        @if($kat->kategori == 'Daging & Protein') egg
                                        @elseif($kat->kategori == 'Sayuran') eco
                                        @elseif($kat->kategori == 'Bumbu & Rempah') opacity
                                        @else inventory_2
                                        @endif
                                    </span>
                                </div>
                                <span class="text-sm font-medium">{{ $kat->kategori }}</span>
                            </div>
                            <span class="text-sm font-bold text-slate-600">{{ $kat->persen }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-2">
                            <div class="bg-primary h-full rounded-full" style="width: {{ $kat->persen }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Belum ada data kategori</p>
                @endforelse
            </div>

            <!-- Kitchen Tip -->
            <div class="pt-6 border-t border-slate-100">
                <div class="bg-primary/5 p-4 rounded-xl border border-primary/10">
                    <div class="flex items-center gap-2 text-primary font-bold mb-1">
                        <span class="material-symbols-outlined text-lg">tips_and_updates</span>
                        <span class="text-xs uppercase tracking-wider">MBG Kitchen Tips</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">Pastikan menu memenuhi standar gizi seimbang: Karbohidrat, Protein Hewani, Protein Nabati, Sayur, dan Buah.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-h2 text-h2 text-on-background">Aktivitas Terkini</h3>
            <a href="{{ route('stok-masuk.index') }}" class="text-sm font-bold text-primary hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500 uppercase">Item Bahan</th>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500 uppercase">Tipe</th>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500 uppercase text-center">Jumlah</th>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500 uppercase">Waktu</th>
                        <th class="px-6 py-4 font-label-caps text-label-caps text-slate-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($aktivitasTerbaru as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded {{ $item['tipe_bg'] }} flex items-center justify-center">
                                        <span class="material-symbols-outlined text-sm {{ $item['tipe_color'] }}">restaurant</span>
                                    </div>
                                    <span class="text-sm font-bold text-slate-900">{{ $item['nama'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 {{ $item['tipe_color'] }}">
                                    <span class="material-symbols-outlined text-sm">{{ $item['icon'] }}</span>
                                    <span class="text-sm font-medium">{{ $item['tipe_text'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-sm text-slate-700">{{ $item['jumlah'] }}</td>
                            <td class="px-6 py-4 text-sm text-slate-500">{{ $item['waktu'] }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 {{ $item['status_color'] }} text-[10px] font-bold rounded uppercase">{{ $item['status'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <span class="material-symbols-outlined text-5xl mb-2">inbox</span>
                                <p>Belum ada aktivitas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Tombol 7 Hari Terakhir
    document.getElementById('btn7Hari')?.addEventListener('click', async function() {
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="material-symbols-outlined text-lg">refresh</span> Memuat...';
        btn.disabled = true;
        
        try {
            const response = await fetch('{{ route("dashboard.filter-7-hari") }}');
            const data = await response.json();
            
            document.getElementById('pengeluaranHariIni').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total);
            
            const persenElement = document.getElementById('persenPerubahan');
            persenElement.innerText = (data.persen >= 0 ? '+' : '') + data.persen + '%';
            persenElement.className = 'font-bold ' + (data.persen <= 0 ? 'text-error' : 'text-primary');
            
            const iconElement = persenElement.previousElementSibling;
            iconElement.innerText = data.persen <= 0 ? 'trending_down' : 'trending_up';
            iconElement.className = 'material-symbols-outlined text-xs ' + (data.persen <= 0 ? 'text-error' : 'text-primary');
            
        } catch (error) {
            console.error('Error:', error);
            alert('Gagal memuat data');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });
</script>

@endsection