@extends('layouts.app')

@section('content')
<main class="space-y-6 fade-in-up">
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

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Produksi Harian</h1>
                <p class="text-slate-500 text-sm mt-1">Catat produksi menu dan kurangi stok bahan secara otomatis</p>
            </div>
            <a href="{{ route('produksi.create') }}" 
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-semibold transition shadow-sm">
                <span class="material-symbols-outlined text-lg">add</span>
                Produksi Baru
            </a>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-slate-500 text-xs font-semibold uppercase">Produksi Hari Ini</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($statistik['hari_ini']) }} Porsi</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-slate-500 text-xs font-semibold uppercase">Produksi Minggu Ini</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($statistik['minggu_ini']) }} Porsi</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-slate-500 text-xs font-semibold uppercase">Produksi Bulan Ini</p>
                <p class="text-2xl font-bold text-slate-800">{{ number_format($statistik['bulan_ini']) }} Porsi</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-slate-500 text-xs font-semibold uppercase">Total Produksi</p>
                <p class="text-2xl font-bold text-emerald-600">{{ number_format($statistik['total_produksi']) }} Porsi</p>
            </div>
        </div>

        <!-- Riwayat Produksi -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-h2 text-h2 text-on-background">Riwayat Produksi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase">Menu</th>
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase text-center">Porsi</th>
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase">Petugas</th>
                            <th class="px-6 py-4 font-label-caps text-slate-500 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($produksi as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 text-slate-600">{{ $item->tanggal_produksi->format('d M Y') }}</td>
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $item->menu->nama ?? 'Menu Dihapus' }}</td>
                                <td class="px-6 py-4 text-center font-bold">{{ number_format($item->jumlah_porsi) }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold 
                                        {{ $item->status == 'selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $item->status == 'selesai' ? 'Selesai' : ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $item->user->name ?? 'System' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('produksi.show', $item) }}" class="text-blue-500 hover:text-blue-700" title="Detail">
                                        <span class="material-symbols-outlined text-base">visibility</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <span class="material-symbols-outlined text-5xl mb-2">restaurant</span>
                                    <p>Belum ada data produksi</p>
                                    <a href="{{ route('produksi.create') }}" class="text-emerald-600 hover:underline mt-2 inline-block">Mulai produksi sekarang</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $produksi->links() }}
            </div>
        </div>
    </div>
</main>

<style>
    .fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection