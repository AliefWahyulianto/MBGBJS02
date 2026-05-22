@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-8 space-y-8">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Supplier</h1>
            <p class="text-slate-500 text-sm">Kelola data pemasok bahan baku dapur</p>
        </div>
        <a href="{{ route('supplier.create') }}" 
           class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-sm flex items-center gap-2">
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border p-6 shadow-sm">
            <p class="text-slate-500 text-xs font-semibold uppercase">Total Supplier</p>
            <p class="text-3xl font-bold text-slate-800">{{ $totalSupplier }}</p>
        </div>
        <div class="bg-white rounded-xl border p-6 shadow-sm">
            <p class="text-slate-500 text-xs font-semibold uppercase">Supplier Aktif</p>
            <p class="text-3xl font-bold text-emerald-600">{{ $supplierAktif }}</p>
        </div>
        <div class="bg-white rounded-xl border p-6 shadow-sm">
            <p class="text-slate-500 text-xs font-semibold uppercase">Total Pembelian</p>
            <p class="text-3xl font-bold text-blue-600">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl border shadow-sm p-4">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Cari supplier..." value="{{ request('search') }}"
                       class="w-full px-4 py-2 border rounded-lg text-sm">
            </div>
            <select name="kategori" class="px-4 py-2 border rounded-lg text-sm">
                <option value="semua">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2 border rounded-lg text-sm">
                <option value="semua">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold">Filter</button>
            @if(request('search') || request('kategori') || request('status'))
                <a href="{{ route('supplier.index') }}" class="text-red-500 text-sm">Reset</a>
            @endif
        </form>
    </div>

    <!-- Tabel Supplier -->
    <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Kode</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Nama Supplier</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Kontak</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Telepon</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase">Kategori</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase text-center">Total Pembelian</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-sm font-mono">{{ $supplier->kode_supplier }}</td>
                        <td class="px-6 py-4 font-medium">{{ $supplier->nama }}</td>
                        <td class="px-6 py-4">{{ $supplier->kontak ?: '-' }}</td>
                        <td class="px-6 py-4">{{ $supplier->telepon ?: '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-slate-100 text-xs rounded-full">{{ $supplier->kategori ?: '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">Rp {{ number_format($supplier->total_pembelian, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                {{ $supplier->status == 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ $supplier->status == 'aktif' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('supplier.show', $supplier) }}" class="text-blue-500 hover:text-blue-700" title="Detail">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                </a>
                                <a href="{{ route('supplier.edit', $supplier) }}" class="text-emerald-500 hover:text-emerald-700" title="Edit">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>
                                <button onclick="confirmDelete({{ $supplier->id }}, '{{ $supplier->nama }}')" class="text-red-500 hover:text-red-700" title="Hapus">
                                    <span class="material-symbols-outlined text-base">delete</span>
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
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                            Belum ada data supplier. Silakan tambah supplier baru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, nama) {
        if (confirm('Apakah Anda yakin ingin menghapus supplier "' + nama + '"?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection