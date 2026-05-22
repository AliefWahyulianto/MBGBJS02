@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('supplier.index') }}" class="p-2 hover:bg-slate-100 rounded-lg">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Supplier</h1>
            <p class="text-slate-500 text-sm">Informasi lengkap supplier</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="p-6 border-b bg-slate-50/50">
            <h3 class="font-semibold text-lg">Informasi Supplier</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-slate-400">Kode Supplier</p>
                <p class="font-mono">{{ $supplier->kode_supplier }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Nama Supplier</p>
                <p class="font-bold">{{ $supplier->nama }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Kontak Person</p>
                <p>{{ $supplier->kontak ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Telepon</p>
                <p>{{ $supplier->telepon ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Email</p>
                <p>{{ $supplier->email ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Kategori</p>
                <p>{{ $supplier->kategori ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400">Status</p>
                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold {{ $supplier->status == 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $supplier->status == 'aktif' ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-slate-400">Alamat</p>
                <p>{{ $supplier->alamat ?: '-' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-slate-400">Keterangan</p>
                <p>{{ $supplier->keterangan ?: '-' }}</p>
            </div>
        </div>

        <div class="p-6 border-t border-b bg-slate-50/50">
            <h3 class="font-semibold text-lg">Statistik</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <p class="text-xs text-slate-400">Rating</p>
                <p class="text-2xl font-bold text-yellow-500">{{ number_format($supplier->rating, 1) }} / 5</p>
            </div>
            <div class="text-center">
                <p class="text-xs text-slate-400">Total Transaksi</p>
                <p class="text-2xl font-bold">{{ number_format($supplier->total_transaksi) }}</p>
            </div>
            <div class="text-center">
                <p class="text-xs text-slate-400">Total Pembelian</p>
                <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($supplier->total_pembelian, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="p-6 border-t bg-slate-50/50">
            <h3 class="font-semibold text-lg mb-4">Riwayat Pembelian</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b">
                        <tr>
                            <th class="py-2">Tanggal</th>
                            <th class="py-2">Bahan</th>
                            <th class="py-2 text-center">Jumlah</th>
                            <th class="py-2 text-right">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historiPembelian as $item)
                        <tr class="border-b">
                            <td class="py-2">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                            <td class="py-2">{{ $item->bahan->nama ?? '-' }}</td>
                            <td class="py-2 text-center">{{ number_format($item->jumlah, 2) }} {{ $item->bahan->satuan ?? '' }}</td>
                            <td class="py-2 text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-slate-500">Belum ada riwayat pembelian</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $historiPembelian->links() }}
        </div>
    </div>
</div>
@endsection