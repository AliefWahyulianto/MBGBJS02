@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6 fade-in-up">

    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Manajemen Supplier</h1>
            <p class="text-slate-500 text-sm">Kelola data pemasok bahan baku dapur</p>
        </div>
        <a href="{{ route('supplier.create') }}" 
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-semibold text-sm transition shadow-sm">
            <span class="material-symbols-outlined text-lg">add</span>
            Tambah Supplier
        </a>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-slate-500 text-[10px] font-semibold uppercase">Total Supplier</p>
            <p class="text-xl font-bold text-slate-800">{{ $totalSupplier }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-slate-500 text-[10px] font-semibold uppercase">Supplier Aktif</p>
            <p class="text-xl font-bold text-emerald-600">{{ $supplierAktif }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
            <p class="text-slate-500 text-[10px] font-semibold uppercase">Total Pembelian</p>
            <p class="text-xl font-bold text-blue-600">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-3">
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="flex-1 min-w-[160px]">
                <input type="text" name="search" placeholder="Cari supplier..." value="{{ request('search') }}"
                       class="w-full px-3 py-1.5 border border-slate-200 rounded-lg text-sm">
            </div>
            <select name="kategori" class="px-3 py-1.5 border border-slate-200 rounded-lg text-sm">
                <option value="semua">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
            <select name="status" class="px-3 py-1.5 border border-slate-200 rounded-lg text-sm">
                <option value="semua">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="px-4 py-1.5 bg-emerald-600 text-white rounded-lg text-sm font-semibold">Filter</button>
            @if(request('search') || request('kategori') || request('status'))
                <a href="{{ route('supplier.index') }}" class="text-red-500 text-sm">Reset</a>
            @endif
        </form>
    </div>

    <!-- Tabel Supplier -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase border-b border-slate-200">Kode</th>
                        <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase border-b border-slate-200">Nama Supplier</th>
                        <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase border-b border-slate-200">Kontak</th>
                        <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase border-b border-slate-200">Telepon</th>
                        <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase border-b border-slate-200">Kategori</th>
                        <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase text-center border-b border-slate-200">Total Pembelian</th>
                        <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase text-center border-b border-slate-200">Status</th>
                        <th class="px-2 py-2 text-[10px] font-semibold text-slate-500 uppercase text-center border-b border-slate-200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $index => $supplier)
                        <tr class="hover:bg-slate-50/80 transition-colors border-b border-slate-100">
                            <td class="px-2 py-2 text-xs font-mono text-slate-600 align-middle border-b border-slate-100">{{ $supplier->kode_supplier }}</td>
                            <td class="px-2 py-2 text-xs font-medium text-slate-800 align-middle border-b border-slate-100">{{ $supplier->nama }}</td>
                            <td class="px-2 py-2 text-xs text-slate-600 align-middle border-b border-slate-100 truncate max-w-[100px]" title="{{ $supplier->kontak ?: '-' }}">
                                {{ \Illuminate\Support\Str::limit($supplier->kontak ?: '-', 15) }}
                            </td>
                            <td class="px-2 py-2 text-xs text-slate-600 align-middle border-b border-slate-100">{{ $supplier->telepon ?: '-' }}</td>
                            <td class="px-2 py-2 align-middle border-b border-slate-100">
                                <span class="inline-flex px-1.5 py-0.5 bg-slate-100 text-slate-600 rounded-full text-[9px] font-medium">{{ $supplier->kategori ?: '-' }}</span>
                            </td>
                            <td class="px-2 py-2 text-center text-xs text-slate-600 align-middle border-b border-slate-100 whitespace-nowrap">
                                Rp {{ number_format($supplier->total_pembelian, 0, ',', '.') }}
                            </td>
                            <td class="px-2 py-2 text-center align-middle border-b border-slate-100">
                                <span class="inline-flex px-1.5 py-0.5 rounded-full text-[9px] font-semibold
                                    {{ $supplier->status == 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $supplier->status == 'aktif' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-2 py-2 text-center align-middle border-b border-slate-100">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('supplier.show', $supplier) }}" class="p-1 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded transition-colors" title="Detail">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                    </a>
                                    <a href="{{ route('supplier.edit', $supplier) }}" class="p-1 text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 rounded transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </a>
                                    <button onclick="confirmDelete({{ $supplier->id }}, '{{ $supplier->nama }}')" class="p-1 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                    <form id="delete-form-{{ $supplier->id }}" action="{{ route('supplier.destroy', $supplier) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-2 py-8 text-center text-slate-400 text-sm border-b border-slate-100">
                                <span class="material-symbols-outlined text-4xl mb-2">storefront</span>
                                <p>Belum ada data supplier</p>
                                <a href="{{ route('supplier.create') }}" class="text-emerald-600 hover:underline text-xs mt-1 inline-block">Tambah supplier pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-slate-100 bg-slate-50">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, nama) {
        if (confirm(`Apakah Anda yakin ingin menghapus supplier "${nama}"?`)) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    }
</script>

<style>
    .fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection