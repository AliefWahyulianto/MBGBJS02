@extends('layouts.app')

@section('content')
<main class="space-y-6">
    <div class="max-w-4xl mx-auto p-8">
        
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('menu.index') }}" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Tambah Menu Baru</h1>
                <p class="text-slate-500 text-sm mt-1">Isi form di bawah untuk menambahkan menu baru</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
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
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Menu -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Menu <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                        <select name="kategori" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="Makanan">🍚 Makanan</option>
                            <option value="Snack">🍪 Snack</option>
                            <option value="Minuman">🥤 Minuman</option>
                        </select>
                    </div>

                    <!-- Kelompok Gizi -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kelompok Gizi</label>
                        <select name="kelompok" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="">-- Pilih Kelompok --</option>
                            <option value="Karbohidrat">🍚 Karbohidrat</option>
                            <option value="Protein Hewani">🍗 Protein Hewani</option>
                            <option value="Protein Nabati">🥜 Protein Nabati</option>
                            <option value="Sayur">🥬 Sayur</option>
                            <option value="Buah">🍎 Buah</option>
                        </select>
                    </div>

                    <!-- Harga Jual -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Jual (Rp)</label>
                        <input type="number" name="harga_jual" value="{{ old('harga_jual', 0) }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <!-- Kalori -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kalori (per porsi)</label>
                        <input type="number" name="kalori" value="{{ old('kalori') }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Contoh: 500">
                    </div>

                    <!-- Gambar -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Menu</label>
                        <input type="file" name="gambar" accept="image/*"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500
                                      file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 
                                      file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700">
                        <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" 
                                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-4 border-t border-slate-100">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-semibold transition">
                        Simpan Menu
                    </button>
                    <a href="{{ route('menu.index') }}" class="flex-1 border border-slate-300 text-slate-700 py-3 rounded-xl text-center hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection