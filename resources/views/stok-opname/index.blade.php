@extends('layouts.app')

@section('content')
<main class="space-y-6">
    <div class="p-8 max-w-7xl mx-auto space-y-8">
        
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

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Stok Opname</h1>
                <p class="text-slate-500 text-sm mt-1">Cocokkan stok fisik dengan stok di sistem</p>
            </div>
            <a href="{{ route('stok-opname.create') }}" 
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-semibold transition shadow-sm">
                <span class="material-symbols-outlined text-lg">inventory</span>
                Opname Baru
            </a>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase">Total Selisih</p>
                        <p class="text-2xl font-bold {{ $totalSelisih >= 0 ? 'text-emerald-600' : 'text-red-500' }} mt-1">
                            {{ $totalSelisih >= 0 ? '+' : '' }}{{ number_format($totalSelisih, 2) }} Unit
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600">difference</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase">Opname Hari Ini</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalOpnameHariIni }}</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-emerald-600">today</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase">Bahan Diopname</p>
                        <p class="text-2xl font-bold text-slate-800 mt-1">{{ $bahanPernahOpname }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600">inventory_2</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Opname -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-h2 text-h2 text-on-background">Riwayat Stok Opname</h3>
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
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase">Bahan</th>
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase text-center">Stok Sistem</th>
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase text-center">Stok Fisik</th>
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase text-center">Selisih</th>
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase">Keterangan</th>
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="stokOpnameTableBody">
                        @forelse($stokOpname as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 text-slate-600">
                                    {{ \Carbon\Carbon::parse($item->tanggal_opname)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="font-medium text-slate-900">{{ $item->bahan->nama ?? 'Bahan Dihapus' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-slate-600">
                                    {{ number_format($item->stok_sistem, 2) }} {{ $item->bahan->satuan ?? '' }}
                                </td>
                                <td class="px-6 py-4 text-center text-slate-600">
                                    {{ number_format($item->stok_fisik, 2) }} {{ $item->bahan->satuan ?? '' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold 
                                        {{ $item->selisih >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $item->selisih >= 0 ? '+' : '' }}{{ number_format($item->selisih, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 max-w-xs truncate">
                                    {{ $item->keterangan ?: '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-500">
                                    {{ $item->user->name ?? 'System' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    <span class="material-symbols-outlined text-5xl mb-2">inventory</span>
                                    <p>Belum ada riwayat stok opname</p>
                                    <a href="{{ route('stok-opname.create') }}" class="text-emerald-600 hover:underline mt-2 inline-block">Mulai opname sekarang</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($stokOpname, 'links'))
                <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                    {{ $stokOpname->links() }}
                </div>
            @endif
        </div>
    </div>
</main>

<script>
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
                let url = '{{ route("stok-opname.filter") }}?';
                if (startDate) url += `start_date=${startDate}&`;
                if (endDate) url += `end_date=${endDate}&`;
                if (bahanId) url += `bahan_id=${bahanId}&`;

                const response = await fetch(url);
                const data = await response.json();

                const tbody = document.getElementById('stokOpnameTableBody');
                
                if (!tbody) return;
                
                if (data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <span class="material-symbols-outlined text-5xl mb-2">inventory</span>
                                <p>Tidak ada data yang sesuai dengan filter</p>
                            </td>
                        </tr>
                    `;
                } else {
                    tbody.innerHTML = '';
                    data.forEach(item => {
                        const selisihClass = item.selisih >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700';
                        tbody.innerHTML += `
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 text-slate-600">${new Date(item.tanggal_opname).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'})}</td>
                                <td class="px-6 py-4"><span class="font-medium text-slate-900">${item.bahan ? item.bahan.nama : 'Bahan Dihapus'}</span></td>
                                <td class="px-6 py-4 text-center text-slate-600">${parseFloat(item.stok_sistem).toLocaleString('id-ID')} ${item.bahan ? item.bahan.satuan : ''}</td>
                                <td class="px-6 py-4 text-center text-slate-600">${parseFloat(item.stok_fisik).toLocaleString('id-ID')} ${item.bahan ? item.bahan.satuan : ''}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold ${selisihClass}">
                                        ${item.selisih >= 0 ? '+' : ''}${parseFloat(item.selisih).toLocaleString('id-ID')}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">${item.keterangan || '-'}</td>
                                <td class="px-6 py-4 text-slate-500">${item.user ? item.user.name : 'System'}</td>
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