@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8 p-8 fade-in-up">

    <!-- HEADER -->
    <div class="flex flex-wrap justify-between items-center gap-4 card-stagger">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Retur & Bahan Rusak</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Catat bahan yang rusak, kadaluarsa, atau tercecer</p>
        </div>
        <a href="{{ route('retur-bahan.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-primary text-white rounded-xl font-semibold text-sm transition shadow-md hover:shadow-lg">
            <span class="material-symbols-outlined text-lg">add</span>
            Tambah Retur
        </a>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl card-stagger">
            <span class="material-symbols-outlined text-emerald-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- STATISTIK CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 text-center card-stagger">
            <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ number_format($statistik['total'], 0) }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Total Item Retur</p>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800 p-4 text-center card-stagger" style="animation-delay: 0.05s">
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($statistik['total_rusak'], 2) }}</p>
            <p class="text-xs text-red-500 dark:text-red-400">Rusak</p>
        </div>
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-800 p-4 text-center card-stagger" style="animation-delay: 0.1s">
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($statistik['total_kadaluarsa'], 2) }}</p>
            <p class="text-xs text-yellow-500 dark:text-yellow-400">Kadaluarsa</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-4 text-center card-stagger" style="animation-delay: 0.15s">
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($statistik['total_tercecer'], 2) }}</p>
            <p class="text-xs text-blue-500 dark:text-blue-400">Tercecer</p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-800 p-4 text-center card-stagger" style="animation-delay: 0.2s">
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($statistik['bulan_ini'], 2) }}</p>
            <p class="text-xs text-purple-500 dark:text-purple-400">Bulan Ini</p>
        </div>
    </div>

    <!-- FILTER -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-4 card-stagger" style="animation-delay: 0.25s">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[180px]">
                <input type="text" name="search" placeholder="Cari kode retur atau bahan..." value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
            </div>
            <select name="jenis" class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                <option value="semua">Semua Jenis</option>
                <option value="rusak" {{ request('jenis') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="kadaluarsa" {{ request('jenis') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                <option value="tercecer" {{ request('jenis') == 'tercecer' ? 'selected' : '' }}>Tercecer</option>
                <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            <input type="date" name="start_date" value="{{ request('start_date') }}" placeholder="Dari" 
                   class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
            <input type="date" name="end_date" value="{{ request('end_date') }}" placeholder="Sampai"
                   class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
            <button type="submit" class="px-5 py-2 bg-gradient-primary text-white rounded-lg text-sm font-semibold transition shadow-md hover:shadow-lg">
                <span class="material-symbols-outlined text-base align-middle mr-1">filter_list</span>
                Filter
            </button>
            @if(request('search') || request('jenis') || request('start_date') || request('end_date'))
                <a href="{{ route('retur-bahan.index') }}" class="text-red-500 dark:text-red-400 text-sm hover:underline">Reset</a>
            @endif
        </form>
    </div>

    <!-- TABEL RETUR -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden card-stagger" style="animation-delay: 0.3s">
        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-600">
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Kode</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Bahan</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase text-center">Jumlah</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase text-center">Jenis</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Keterangan</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($returs as $index => $item)
                        @php
                            $jenisClass = match($item->jenis) {
                                'rusak' => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300',
                                'kadaluarsa' => 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300',
                                'tercecer' => 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300',
                                default => 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors table-row-stagger" 
                            style="animation-delay: {{ 0.02 * ($index + 1) }}s">
                            <td class="px-4 py-3 text-sm font-mono text-slate-600 dark:text-slate-300">{{ $item->kode_retur }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $item->tanggal_retur->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-800 dark:text-white">{{ $item->bahan->nama ?? '-' }}</td>
                            <td class="px-4 py-3 text-center text-sm text-slate-700 dark:text-slate-300">{{ number_format($item->jumlah, 2) }} {{ $item->satuan }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $jenisClass }}">
                                    {{ ucfirst($item->jenis) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400 max-w-xs truncate" title="{{ $item->keterangan }}">
                                {{ \Illuminate\Support\Str::limit($item->keterangan ?? '-', 40) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('retur-bahan.show', $item) }}" 
                                       class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition" title="Detail">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                    </a>
                                    <button onclick="confirmDelete({{ $item->id }})" 
                                            class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition" title="Hapus">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('retur-bahan.destroy', $item) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-400 dark:text-slate-500">
                                <span class="material-symbols-outlined text-5xl mb-2">inventory</span>
                                <p>Belum ada data retur bahan</p>
                                <a href="{{ route('retur-bahan.create') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline text-sm mt-2 inline-block">Catat retur pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            {{ $returs->links() }}
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data retur ini? Stok akan dikembalikan.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection