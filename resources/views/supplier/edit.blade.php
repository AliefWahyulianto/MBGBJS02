@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('supplier.index') }}" class="p-2 hover:bg-slate-100 rounded-lg">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Supplier</h1>
            <p class="text-slate-500 text-sm">Ubah informasi supplier</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm p-6">
        <form action="{{ route('supplier.update', $supplier) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Supplier (Readonly) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Kode Supplier</label>
                    <input type="text" value="{{ $supplier->kode_supplier }}" readonly 
                           class="w-full border rounded-lg px-4 py-2 bg-slate-50 text-slate-600">
                </div>

                <!-- Nama Supplier -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Nama Supplier <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $supplier->nama) }}" required 
                           class="w-full border rounded-lg px-4 py-2">
                    @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kontak Person -->
                <div>
                    <label class="block text-sm font-semibold mb-1">Kontak Person</label>
                    <input type="text" name="kontak" value="{{ old('kontak', $supplier->kontak) }}" 
                           class="w-full border rounded-lg px-4 py-2">
                </div>

                <!-- Telepon -->
                <div>
                    <label class="block text-sm font-semibold mb-1">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $supplier->telepon) }}" 
                           class="w-full border rounded-lg px-4 py-2">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $supplier->email) }}" 
                           class="w-full border rounded-lg px-4 py-2">
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-semibold mb-1">Kategori</label>
                    <select name="kategori" class="w-full border rounded-lg px-4 py-2">
                        <option value="">Pilih Kategori</option>
                        <option value="Daging & Protein" {{ old('kategori', $supplier->kategori) == 'Daging & Protein' ? 'selected' : '' }}>🥩 Daging & Protein</option>
                        <option value="Sayuran" {{ old('kategori', $supplier->kategori) == 'Sayuran' ? 'selected' : '' }}>🥬 Sayuran</option>
                        <option value="Bumbu & Rempah" {{ old('kategori', $supplier->kategori) == 'Bumbu & Rempah' ? 'selected' : '' }}>🧄 Bumbu & Rempah</option>
                        <option value="Karbohidrat" {{ old('kategori', $supplier->kategori) == 'Karbohidrat' ? 'selected' : '' }}>🍚 Karbohidrat</option>
                        <option value="Dairy & Egg" {{ old('kategori', $supplier->kategori) == 'Dairy & Egg' ? 'selected' : '' }}>🥚 Dairy & Egg</option>
                        <option value="Minuman" {{ old('kategori', $supplier->kategori) == 'Minuman' ? 'selected' : '' }}>🥤 Minuman</option>
                        <option value="Lainnya" {{ old('kategori', $supplier->kategori) == 'Lainnya' ? 'selected' : '' }}>📦 Lainnya</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold mb-1">Status</label>
                    <select name="status" class="w-full border rounded-lg px-4 py-2">
                        <option value="aktif" {{ old('status', $supplier->status) == 'aktif' ? 'selected' : '' }}>✅ Aktif</option>
                        <option value="nonaktif" {{ old('status', $supplier->status) == 'nonaktif' ? 'selected' : '' }}>❌ Nonaktif</option>
                    </select>
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" class="w-full border rounded-lg px-4 py-2">{{ old('alamat', $supplier->alamat) }}</textarea>
                </div>

                <!-- Keterangan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="w-full border rounded-lg px-4 py-2">{{ old('keterangan', $supplier->keterangan) }}</textarea>
                </div>
            </div>

            <!-- Statistik (Readonly) -->
            <div class="mt-6 p-4 bg-slate-50 rounded-lg grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-xs text-slate-500">Rating</p>
                    <p class="font-semibold">{{ number_format($supplier->rating, 1) }} / 5</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Total Transaksi</p>
                    <p class="font-semibold">{{ number_format($supplier->total_transaksi) }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Total Pembelian</p>
                    <p class="font-semibold text-emerald-600">Rp {{ number_format($supplier->total_pembelian, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex gap-3 mt-8 pt-4 border-t">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg font-semibold">Update Supplier</button>
                <a href="{{ route('supplier.index') }}" class="flex-1 border text-center py-2 rounded-lg hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection