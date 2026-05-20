@extends('layouts.app')

@section('content')
<!-- MAIN CONTENT - Edit Bahan -->
<main class="space-y-6">
    <div class="max-w-3xl mx-auto">
        
        <!-- HEADER -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('bahan.index') }}" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit Bahan</h1>
                <p class="text-slate-500 text-sm mt-1">Ubah informasi bahan baku dapur</p>
            </div>
        </div>

        <!-- FORM EDIT -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <form action="{{ route('bahan.update', $bahan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Nama Bahan (Full Width) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                Nama Bahan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" value="{{ old('nama', $bahan->nama) }}" required
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            @error('nama') 
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                            <select name="kategori" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="Daging & Protein" {{ $bahan->kategori == 'Daging & Protein' ? 'selected' : '' }}>🍖 Daging & Protein</option>
                                <option value="Sayuran" {{ $bahan->kategori == 'Sayuran' ? 'selected' : '' }}>🥬 Sayuran</option>
                                <option value="Bumbu & Rempah" {{ $bahan->kategori == 'Bumbu & Rempah' ? 'selected' : '' }}>🧄 Bumbu & Rempah</option>
                                <option value="Karbohidrat" {{ $bahan->kategori == 'Karbohidrat' ? 'selected' : '' }}>🍚 Karbohidrat</option>
                                <option value="Dairy & Egg" {{ $bahan->kategori == 'Dairy & Egg' ? 'selected' : '' }}>🥚 Dairy & Egg</option>
                                <option value="Bahan Pelengkap" {{ $bahan->kategori == 'Bahan Pelengkap' ? 'selected' : '' }}>🫗 Bahan Pelengkap</option>
                                <option value="Lainnya" {{ $bahan->kategori == 'Lainnya' ? 'selected' : '' }}>📦 Lainnya</option>
                            </select>
                        </div>

                        <!-- Satuan -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Satuan</label>
                            <select name="satuan" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <option value="kg" {{ $bahan->satuan == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="gram" {{ $bahan->satuan == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                                <option value="liter" {{ $bahan->satuan == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                                <option value="butir" {{ $bahan->satuan == 'butir' ? 'selected' : '' }}>Butir</option>
                                <option value="pack" {{ $bahan->satuan == 'pack' ? 'selected' : '' }}>Pack</option>
                                <option value="pcs" {{ $bahan->satuan == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                <option value="ikat" {{ $bahan->satuan == 'ikat' ? 'selected' : '' }}>Ikat</option>
                                <option value="botol" {{ $bahan->satuan == 'botol' ? 'selected' : '' }}>Botol</option>
                            </select>
                        </div>

                        <!-- Stok Saat Ini -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Stok Saat Ini</label>
                            <div class="relative">
                                <input type="number" name="stok" value="{{ old('stok', $bahan->stok) }}" step="0.01"
                                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400">
                                    {{ $bahan->satuan }}
                                </span>
                            </div>
                        </div>

                        <!-- Stok Minimal -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Stok Minimal</label>
                            <div class="relative">
                                <input type="number" name="stok_minimal" value="{{ old('stok_minimal', $bahan->stok_minimal) }}" step="0.01"
                                       class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400">
                                    {{ $bahan->satuan }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">
                                ⚠️ Akan mendapat peringatan jika stok di bawah angka ini
                            </p>
                        </div>

                        <!-- Harga Beli - Perbaikan Total -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Beli (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">Rp</span>
                                <input type="number" 
                                    name="harga_beli" 
                                    value="{{ old('harga_beli', $bahan->harga_beli) }}" 
                                    step="1"
                                    min="0"
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    placeholder="0">
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Per {{ $bahan->satuan }} (contoh: 2500)</p>
                            
                            <!-- Tampilkan estimasi nilai stok secara real-time -->
                            <div class="mt-2 text-sm" id="estimasi-harga">
                                Estimasi: <span class="font-semibold text-emerald-600">Rp 0</span>
                            </div>
                        </div>

                        <!-- Tambahkan script untuk update estimasi real-time -->
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

                        <!-- Estimasi Nilai Stok (Readonly) -->
                        <div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
                            <label class="block text-xs font-semibold text-slate-500 mb-1">Estimasi Nilai Stok</label>
                            <p class="text-lg font-bold text-emerald-600">
                                Rp {{ number_format($bahan->stok * $bahan->harga_beli, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-slate-400">{{ number_format($bahan->stok, 2) }} {{ $bahan->satuan }} × Rp {{ number_format($bahan->harga_beli, 0, ',', '.') }}</p>
                        </div>

                        <!-- Gambar (Full Width) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Bahan</label>
                            
                            @if($bahan->gambar)
                                <div class="mb-4 flex items-center gap-4 p-3 bg-slate-50 rounded-lg border border-slate-200">
                                    <img src="{{ asset('storage/' . $bahan->gambar) }}" class="w-16 h-16 rounded-lg object-cover border border-slate-200">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-slate-700">Gambar saat ini</p>
                                        <p class="text-xs text-slate-400">Kosongkan jika tidak ingin mengubah gambar</p>
                                    </div>
                                </div>
                            @endif
                            
                            <input type="file" name="gambar" accept="image/*"
                                   class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500
                                          file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 
                                          file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 
                                          hover:file:bg-emerald-100 transition">
                            <p class="text-xs text-slate-400 mt-1">Format: JPG, JPEG, PNG (Max 2MB)</p>
                        </div>

                        <!-- Keterangan (Full Width) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" rows="3" 
                                      class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                      placeholder="Tambahkan catatan tentang bahan ini...">{{ old('keterangan', $bahan->keterangan) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3">
                    <button type="submit" 
                            class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">save</span>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('bahan.index') }}" 
                       class="flex-1 border border-slate-300 text-slate-700 py-3 rounded-xl font-semibold text-center hover:bg-slate-50 transition flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-lg">close</span>
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- INFO CARD (Optional) -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-blue-500 text-lg">info</span>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-800">Tips Mengisi Data</p>
                    <p class="text-xs text-blue-600 mt-1">
                        • Stok minimal berguna untuk peringatan otomatis saat stok menipis<br>
                        • Harga beli digunakan untuk menghitung nilai stok dan laporan keuangan<br>
                        • Gambar akan membantu identifikasi bahan secara visual
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection