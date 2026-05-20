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

        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Stok Mengendap</h1>
                <p class="text-slate-500 text-sm mt-1">Kelola bahan sisa produksi yang dapat digunakan kembali</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('produksi.index') }}" 
                   class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">production_quantity_limits</span>
                    Lihat Produksi
                </a>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase">Total Menunggu</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ number_format($totalMenunggu, 2) }} Unit</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-yellow-600">hourglass_empty</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-2">Bahan sisa yang belum digunakan</p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase">Total Terpakai</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($totalTerpakai, 2) }} Unit</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-2">Bahan sisa yang sudah digunakan kembali</p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500 text-xs font-semibold uppercase">Efisiensi</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">
                            {{ $totalMenunggu + $totalTerpakai > 0 ? round(($totalTerpakai / ($totalMenunggu + $totalTerpakai)) * 100) : 0 }}%
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600">analytics</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-2">Tingkat pemanfaatan stok mengendap</p>
            </div>
        </div>

        <!-- Tabel Stok Mengendap -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-h2 text-h2 text-slate-900">Daftar Stok Mengendap</h3>
                <p class="text-sm text-slate-500">Bahan sisa dari produksi yang dapat digunakan kembali</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase">Tanggal</th>
                            <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase">Menu Produksi</th>
                            <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase">Bahan</th>
                            <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase text-center">Kelebihan</th>
                            <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase text-center">Kekurangan</th>
                            <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase text-center">Terpakai</th>
                            <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase text-center">Sisa</th>
                            <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase">Status</th>
                            <th class="px-3 py-2 text-[10px] font-semibold text-slate-500 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($stokMengendap as $item)
                            @php
                                $sisa = $item->jumlah_kelebihan - $item->jumlah_terpakai;
                                $statusColor = $item->status == 'menunggu' ? 'bg-yellow-100 text-yellow-700' : ($item->status == 'terpakai' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700');
                                $statusText = $item->status == 'menunggu' ? 'Menunggu' : ($item->status == 'terpakai' ? 'Terpakai' : 'Kadaluarsa');
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-3 py-2 text-xs text-slate-600">
                                    {{ \Carbon\Carbon::parse($item->tanggal_mengendap)->format('d/m/Y') }}
                                </td>
                                <td class="px-3 py-2 text-xs text-slate-800">
                                    {{ $item->produksi->menu->nama ?? '-' }}
                                </td>
                                <td class="px-3 py-2 text-xs font-medium text-slate-900">
                                    {{ $item->bahan->nama ?? 'Bahan Dihapus' }}
                                </td>
                                <td class="px-3 py-2 text-center text-emerald-600 font-semibold text-xs">
                                    +{{ number_format($item->jumlah_kelebihan, 2) }} {{ $item->satuan }}
                                </td>
                                <td class="px-3 py-2 text-center text-red-500 font-semibold text-xs">
                                    {{ $item->jumlah_kekurangan > 0 ? '-' . number_format($item->jumlah_kekurangan, 2) . ' ' . $item->satuan : '-' }}
                                </td>
                                <td class="px-3 py-2 text-center text-blue-500 text-xs">
                                    {{ number_format($item->jumlah_terpakai, 2) }} {{ $item->satuan }}
                                </td>
                                <td class="px-3 py-2 text-center font-bold text-xs {{ $sisa > 0 ? 'text-orange-600' : 'text-slate-400' }}">
                                    {{ number_format($sisa, 2) }} {{ $item->satuan }}
                                </td>
                                <td class="px-3 py-2 text-xs">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $statusColor }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    @if($item->status == 'menunggu' && $sisa > 0)
                                        <button onclick="gunakanMengendap({{ $item->id }}, '{{ $item->bahan->nama ?? '' }}', {{ $sisa }}, '{{ $item->satuan }}')"
                                                class="px-2 py-1 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded text-[10px] font-semibold transition flex items-center gap-0.5 mx-auto">
                                            <span class="material-symbols-outlined text-sm">play_arrow</span>
                                            Pakai
                                        </button>
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 py-8 text-center text-slate-500 text-sm">
                                    <span class="material-symbols-outlined text-4xl mb-2">inventory</span>
                                    <p>Belum ada data stok mengendap</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($stokMengendap, 'links'))
                <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                    {{ $stokMengendap->links() }}
                </div>
            @endif
        </div>

        <!-- Informasi Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-symbols-outlined text-blue-600">info</span>
                    <h4 class="font-semibold text-blue-800">Apa Itu Stok Mengendap?</h4>
                </div>
                <p class="text-sm text-blue-700 leading-relaxed">
                    Stok mengendap adalah bahan sisa dari produksi yang masih layak pakai. 
                    Bahan ini dapat digunakan kembali untuk produksi berikutnya, sehingga menghemat biaya dan mengurangi pemborosan.
                </p>
            </div>

            <div class="bg-orange-50 rounded-xl p-6 border border-orange-100">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-symbols-outlined text-orange-600">tips_and_updates</span>
                    <h4 class="font-semibold text-orange-800">Tips Efisiensi</h4>
                </div>
                <ul class="text-sm text-orange-700 space-y-1 list-disc list-inside">
                    <li>Catat kelebihan bahan segera setelah produksi selesai</li>
                    <li>Prioritaskan penggunaan stok mengendap untuk produksi berikutnya</li>
                    <li>Bahan yang sudah > 3 hari sebaiknya dicek kualitasnya</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<script>
    function gunakanMengendap(id, nama, jumlah, satuan) {
        let penggunaan = prompt(`Masukkan jumlah ${nama} yang akan digunakan untuk produksi:\n(Maksimal ${jumlah} ${satuan})`, jumlah);
        
        if (penggunaan !== null && penggunaan !== '') {
            let jumlahAngka = parseFloat(penggunaan);
            if (isNaN(jumlahAngka) || jumlahAngka <= 0) {
                alert('Masukkan jumlah yang valid!');
                return;
            }
            if (jumlahAngka > jumlah) {
                alert(`Jumlah tidak boleh melebihi stok yang tersedia (${jumlah} ${satuan})`);
                return;
            }
            window.location.href = `/stok-mengendap/${id}/gunakan?jumlah=${jumlahAngka}`;
        }
    }
</script>
@endsection