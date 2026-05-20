@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('menu.index') }}" class="p-2 hover:bg-slate-100 rounded-lg">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Menu Baru</h1>
            <p class="text-slate-500 text-sm">Isi form di bawah untuk menambahkan menu baru</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Menu <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                        <option value="Makanan">Makanan</option>
                        <option value="Snack">Snack</option>
                        <option value="Minuman">Minuman</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kelompok Gizi</label>
                    <select name="kelompok" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                        <option value="">-- Pilih --</option>
                        <option value="Karbohidrat">Karbohidrat</option>
                        <option value="Protein Hewani">Protein Hewani</option>
                        <option value="Protein Nabati">Protein Nabati</option>
                        <option value="Sayur">Sayur</option>
                        <option value="Buah">Buah</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Harga Jual (Rp)</label>
                        <input type="number" name="harga_jual" value="{{ old('harga_jual', 0) }}"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kalori</label>
                        <input type="number" name="kalori" value="{{ old('kalori') }}"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg"
                               placeholder="Contoh: 500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Gambar</label>
                    <input type="file" name="gambar" accept="image/*"
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                    <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" 
                              class="w-full px-4 py-2 border border-slate-300 rounded-lg">{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-8 pt-4 border-t">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg font-semibold">
                    Simpan Menu
                </button>
                <a href="{{ route('menu.index') }}" class="flex-1 border border-slate-300 text-slate-700 py-2 rounded-lg text-center hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection