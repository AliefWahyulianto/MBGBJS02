@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('stok-masuk.index') }}" class="p-2 hover:bg-slate-100 rounded-lg">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Stok Masuk</h1>
            <p class="text-slate-500 text-sm">Informasi lengkap transaksi pembelian</p>
        </div>
        <div class="ml-auto flex gap-2">
            <a href="{{ route('stok-masuk.export.pdf') }}" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">picture_as_pdf</span>
                PDF
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">print</span>
                Print
            </button>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Informasi Transaksi -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-h2 text-h2 text-slate-900">Informasi Transaksi</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-slate-400 uppercase">ID Transaksi</p>
                <p class="text-lg font-bold text-slate-800">#{{ $stokMasuk->id }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Status</p>
                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold 
                    {{ $stokMasuk->status == 'verified' ? 'bg-emerald-100 text-emerald-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $stokMasuk->status == 'verified' ? 'Selesai' : 'Pending' }}
                </span>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Tanggal Masuk</p>
                <p class="text-slate-800">{{ $stokMasuk->tanggal_masuk->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase">Dibuat Pada</p>
                <p class="text-slate-800">{{ $stokMasuk->created_at->format('d F Y H:i:s') }}</p>
            </div>
            @if($stokMasuk->no_invoice)
            <div>
                <p class="text-xs text-slate-400 uppercase">No. Invoice</p>
                <p class="text-slate-800 font-mono">{{ $stokMasuk->no_invoice }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Informasi Bahan & Supplier -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Informasi Bahan -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-h2 text-h2 text-slate-900">Informasi Bahan</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-xs text-slate-400 uppercase">Nama Bahan</p>
                    <p class="text-lg font-semibold text-slate-800">{{ $stokMasuk->bahan->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase">Jumlah</p>
                    <p class="text-lg font-semibold text-slate-800">{{ number_format($stokMasuk->jumlah, 2) }} {{ $stokMasuk->bahan->satuan ?? '' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase">Harga Satuan</p>
                    <p class="text-lg font-semibold text-emerald-600">Rp {{ number_format($stokMasuk->harga_satuan, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase">Total Harga</p>
                    <p class="text-xl font-bold text-emerald-700">Rp {{ number_format($stokMasuk->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Informasi Supplier -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-h2 text-h2 text-slate-900">Informasi Supplier</h3>
            </div>
            <div class="p-6 space-y-4">
                @if($stokMasuk->supplier)
                <div>
                    <p class="text-xs text-slate-400 uppercase">Nama Supplier</p>
                    <p class="text-lg font-semibold text-slate-800">{{ $stokMasuk->supplier->nama }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase">Kontak</p>
                    <p class="text-slate-800">{{ $stokMasuk->supplier->kontak ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase">Telepon</p>
                    <p class="text-slate-800">{{ $stokMasuk->supplier->telepon ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase">Kategori Supplier</p>
                    <p class="text-slate-800">{{ $stokMasuk->supplier->kategori ?: '-' }}</p>
                </div>
                @else
                <div class="text-center py-8 text-slate-400">
                    <span class="material-symbols-outlined text-4xl">storefront</span>
                    <p class="mt-2">Tidak ada data supplier</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Catatan -->
    @if($stokMasuk->catatan)
    <div class="mt-8 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-h2 text-h2 text-slate-900">Catatan</h3>
        </div>
        <div class="p-6">
            <p class="text-slate-700">{{ $stokMasuk->catatan }}</p>
        </div>
    </div>
    @endif

    <!-- Tombol Aksi -->
    <div class="mt-8 flex gap-3">
        <a href="{{ route('stok-masuk.index') }}" class="flex-1 border border-slate-300 text-slate-700 py-3 rounded-xl text-center hover:bg-slate-50 transition">
            Kembali ke Daftar
        </a>
    </div>
</div>
@endsection