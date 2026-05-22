@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('supplier.index') }}" class="p-2 hover:bg-slate-100 rounded-lg">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Supplier</h1>
            <p class="text-slate-500 text-sm">Isi form di bawah untuk menambahkan supplier baru</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border shadow-sm p-6">
        <form action="{{ route('supplier.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Nama Supplier <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full border rounded-lg px-4 py-2">
                    @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Kontak Person</label>
                    <input type="text" name="kontak" value="{{ old('kontak') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Kategori</label>
                    <select name="kategori" class="w-full border rounded-lg px-4 py-2">
                        <option value="">Pilih Kategori</option>
                        <option value="Daging & Protein">🥩 Daging & Protein</option>
                        <option value="Sayuran">🥬 Sayuran</option>
                        <option value="Bumbu & Rempah">🧄 Bumbu & Rempah</option>
                        <option value="Karbohidrat">🍚 Karbohidrat</option>
                        <option value="Dairy & Egg">🥚 Dairy & Egg</option>
                        <option value="Minuman">🥤 Minuman</option>
                        <option value="Lainnya">📦 Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Status</label>
                    <select name="status" class="w-full border rounded-lg px-4 py-2">
                        <option value="aktif">✅ Aktif</option>
                        <option value="nonaktif">❌ Nonaktif</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" class="w-full border rounded-lg px-4 py-2">{{ old('alamat') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="w-full border rounded-lg px-4 py-2">{{ old('keterangan') }}</textarea>
                </div>
            </div>
            <div class="flex gap-3 mt-8 pt-4 border-t">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg font-semibold">Simpan Supplier</button>
                <a href="{{ route('supplier.index') }}" class="flex-1 border text-center py-2 rounded-lg hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection