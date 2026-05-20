@extends('layouts.app')

@section('content')
<!-- MAIN CONTENT -->
<main class="space-y-6">
    <div class="max-w-3xl mx-auto">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('bahan.index') }}" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Tambah Bahan Baru</h1>
                <p class="text-slate-500 text-sm mt-1">Isi form di bawah untuk menambahkan bahan baku</p>
            </div>
        </div>

        <!-- Alert Error -->
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('bahan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Bahan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Bahan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                        <select name="kategori" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="Daging & Protein">Daging & Protein</option>
                            <option value="Sayuran">Sayuran</option>
                            <option value="Bumbu & Rempah">Bumbu & Rempah</option>
                            <option value="Karbohidrat">Karbohidrat</option>
                            <option value="Dairy & Egg">Dairy & Egg</option>
                            <option value="Bahan Pelengkap">Bahan Pelengkap</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <!-- Satuan -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Satuan</label>
                        <select name="satuan" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="kg">Kilogram (kg)</option>
                            <option value="gram">Gram (g)</option>
                            <option value="liter">Liter (L)</option>
                            <option value="butir">Butir</option>
                            <option value="pack">Pack</option>
                            <option value="pcs">Pcs</option>
                            <option value="ikat">Ikat</option>
                        </select>
                    </div>

                    <!-- Stok Awal -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Stok Awal</label>
                        <input type="number" name="stok" value="{{ old('stok', 0) }}" step="0.01"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <!-- Stok Minimal -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Stok Minimal (Peringatan)</label>
                        <input type="number" name="stok_minimal" value="{{ old('stok_minimal', 1) }}" step="0.01"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <p class="text-xs text-slate-400 mt-1">⚠️ Akan mendapat peringatan jika stok di bawah angka ini</p>
                    </div>

                    <!-- Harga Beli (PERBAIKAN - Hapus $bahan) -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Beli (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                            <input type="number" 
                                name="harga_beli" 
                                value="{{ old('harga_beli', 0) }}" 
                                step="1"
                                min="0"
                                class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="0">
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Per satuan (contoh: 2500)</p>
                        
                        <!-- Estimasi nilai stok -->
                        <div class="mt-2 text-sm" id="estimasi-harga">
                            Estimasi: <span class="font-semibold text-emerald-600">Rp 0</span>
                        </div>
                    </div>

                    <!-- Gambar -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Bahan</label>
                        <input type="file" name="gambar" accept="image/*"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        <p class="text-xs text-slate-400 mt-1">Format: JPG, JPEG, PNG (Max 2MB)</p>
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" 
                                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-8 pt-4 border-t border-slate-100">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-semibold transition">
                        Simpan Bahan
                    </button>
                    <a href="{{ route('bahan.index') }}" class="flex-1 border border-slate-300 text-slate-700 py-3 rounded-xl font-semibold text-center hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    // Update estimasi nilai stok saat harga atau stok berubah
    function updateEstimasi() {
        let harga = document.querySelector('input[name="harga_beli"]').value || 0;
        let stok = document.querySelector('input[name="stok"]').value || 0;
        let estimasi = harga * stok;
        let estimasiEl = document.getElementById('estimasi-harga');
        if (estimasiEl) {
            estimasiEl.innerHTML = `Estimasi: <span class="font-semibold text-emerald-600">Rp ${new Intl.NumberFormat('id-ID').format(estimasi)}</span>`;
        }
    }
    
    // Event listener untuk input harga dan stok
    document.addEventListener('DOMContentLoaded', function() {
        let hargaInput = document.querySelector('input[name="harga_beli"]');
        let stokInput = document.querySelector('input[name="stok"]');
        
        if (hargaInput) hargaInput.addEventListener('input', updateEstimasi);
        if (stokInput) stokInput.addEventListener('input', updateEstimasi);
        
        updateEstimasi(); // Panggil pertama kali
    });
</script>
@endsection