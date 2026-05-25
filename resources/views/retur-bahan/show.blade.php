@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8 fade-in-up">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('retur-bahan.index') }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Detail Retur Bahan</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Informasi lengkap retur bahan</p>
        </div>
        <div class="ml-auto flex gap-2">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">print</span>
                Print
            </button>
        </div>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    @php
        $jenisClass = match($returBahan->jenis) {
            'rusak' => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300',
            'kadaluarsa' => 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300',
            'tercecer' => 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300',
            default => 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400',
        };
        
        $jenisIcon = match($returBahan->jenis) {
            'rusak' => 'warning',
            'kadaluarsa' => 'schedule',
            'tercecer' => 'water_damage',
            default => 'info',
        };
    @endphp

    <!-- KARTU INFORMASI UTAMA -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-gradient-primary">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/70 text-xs font-semibold uppercase">Kode Retur</p>
                    <p class="text-white text-xl font-bold font-mono">{{ $returBahan->kode_retur }}</p>
                </div>
                <div class="px-3 py-1 rounded-lg bg-white/20 backdrop-blur-sm">
                    <span class="text-white text-xs font-semibold">{{ $returBahan->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Bahan -->
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center">
                            <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-sm">inventory</span>
                        </div>
                        <h3 class="font-semibold text-slate-800 dark:text-white">Informasi Bahan</h3>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 uppercase">Nama Bahan</p>
                            <p class="text-base font-semibold text-slate-800 dark:text-white">{{ $returBahan->bahan->nama ?? 'Bahan Dihapus' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-500 uppercase">Jumlah Retur</p>
                                <p class="text-lg font-bold text-red-600 dark:text-red-400">{{ number_format($returBahan->jumlah, 2) }} {{ $returBahan->satuan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 dark:text-slate-500 uppercase">Stok Saat Ini</p>
                                <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($returBahan->bahan->stok ?? 0, 2) }} {{ $returBahan->satuan }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Retur -->
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-full {{ $jenisClass }} flex items-center justify-center">
                            <span class="material-symbols-outlined text-sm">{{ $jenisIcon }}</span>
                        </div>
                        <h3 class="font-semibold text-slate-800 dark:text-white">Informasi Retur</h3>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 uppercase">Jenis Retur</p>
                            <p>
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $jenisClass }}">
                                    {{ ucfirst($returBahan->jenis) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 uppercase">Tanggal Retur</p>
                            <p class="text-base font-semibold text-slate-800 dark:text-white">{{ $returBahan->tanggal_retur->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 uppercase">Petugas</p>
                            <p class="text-base font-semibold text-slate-800 dark:text-white">{{ $returBahan->user->name ?? 'System' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            @if($returBahan->keterangan)
            <div class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">description</span>
                    <div>
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 uppercase font-semibold">Keterangan</p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">{{ $returBahan->keterangan }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- STATISTIK TAMBAHAN -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Retur Jenis Ini -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 uppercase">Total Retur {{ ucfirst($returBahan->jenis) }}</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">
                        {{ number_format(\App\Models\ReturBahan::where('jenis', $returBahan->jenis)->sum('jumlah'), 2) }} {{ $returBahan->satuan }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full {{ $jenisClass }} flex items-center justify-center">
                    <span class="material-symbols-outlined text-sm">{{ $jenisIcon }}</span>
                </div>
            </div>
        </div>

        <!-- Total Retur Bahan Ini -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 uppercase">Total Retur {{ $returBahan->bahan->nama ?? 'Bahan' }}</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white">
                        {{ number_format(\App\Models\ReturBahan::where('bahan_id', $returBahan->bahan_id)->sum('jumlah'), 2) }} {{ $returBahan->satuan }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-sm">inventory</span>
                </div>
            </div>
        </div>

        <!-- Persentase Kerugian -->
        @php
            $totalHarga = ($returBahan->bahan->harga_beli ?? 0) * $returBahan->jumlah;
        @endphp
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 uppercase">Estimasi Kerugian</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                        Rp {{ number_format($totalHarga, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-600 dark:text-red-400 text-sm">payments</span>
                </div>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">@ Rp {{ number_format($returBahan->bahan->harga_beli ?? 0, 0, ',', '.') }} / {{ $returBahan->satuan }}</p>
        </div>
    </div>

    <!-- RIWAYAT RETUR BAHAN INI -->
    <div class="mt-8 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-700/30">
            <h3 class="font-semibold text-slate-800 dark:text-white">Riwayat Retur {{ $returBahan->bahan->nama ?? 'Bahan' }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-600">
                    <tr>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Kode</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Jenis</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase text-center">Jumlah</th>
                        <th class="px-4 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @php
                        $riwayat = \App\Models\ReturBahan::where('bahan_id', $returBahan->bahan_id)
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();
                    @endphp
                    @forelse($riwayat as $item)
                        @php
                            $rowJenisClass = match($item->jenis) {
                                'rusak' => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300',
                                'kadaluarsa' => 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300',
                                'tercecer' => 'bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300',
                                default => 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $item->tanggal_retur->format('d/m/Y') }}</tr>
                            <td class="px-4 py-3 text-sm font-mono text-slate-600 dark:text-slate-300">{{ $item->kode_retur }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $rowJenisClass }}">
                                    {{ ucfirst($item->jenis) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-slate-700 dark:text-slate-300">{{ number_format($item->jumlah, 2) }} {{ $item->satuan }}</td>
                            <td class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400 max-w-xs truncate" title="{{ $item->keterangan }}">
                                {{ \Illuminate\Support\Str::limit($item->keterangan ?? '-', 40) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-400 dark:text-slate-500">
                                Belum ada riwayat retur untuk bahan ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TOMBOL AKSI -->
    <div class="mt-8 flex gap-3">
        <a href="{{ route('retur-bahan.index') }}" class="flex-1 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 py-3 rounded-xl text-center hover:bg-slate-50 dark:hover:bg-slate-700 transition">
            Kembali ke Daftar
        </a>
        <button onclick="confirmDelete({{ $returBahan->id }})" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold transition">
            Hapus Retur
        </button>
        <form id="delete-form-{{ $returBahan->id }}" action="{{ route('retur-bahan.destroy', $returBahan) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('⚠️ PERINGATAN! Menghapus retur akan mengembalikan stok bahan.\n\nApakah Anda yakin?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>

<style>
    @media print {
        .sidebar-slide-in, aside, header, .btn-print, .no-print {
            display: none !important;
        }
        .ml-64 {
            margin-left: 0 !important;
        }
        body {
            background: white !important;
        }
    }
</style>
@endsection