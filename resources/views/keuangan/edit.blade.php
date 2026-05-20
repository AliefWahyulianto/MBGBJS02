@extends('layouts.app')

@section('content')
<!-- MAIN CONTENT - Edit Transaksi Keuangan -->
<main class="ml-64 min-h-screen p-8">
    <div class="max-w-2xl mx-auto">
        
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('keuangan.laporan') }}" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Edit Transaksi</h1>
                <p class="text-slate-500 text-sm mt-1">Ubah data pemasukan atau pengeluaran</p>
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

        <!-- Form Edit -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('keuangan.update', $keuangan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-5">
                    
                    <!-- Kode Transaksi (Readonly) -->
                    <div class="bg-slate-50 p-3 rounded-lg">
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Kode Transaksi</label>
                        <p class="text-sm font-mono font-semibold text-slate-700">{{ $keuangan->kode_transaksi }}</p>
                    </div>

                    <!-- Jenis Transaksi -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Jenis Transaksi</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer flex-1 {{ $keuangan->jenis == 'masuk' ? 'bg-emerald-50 border-emerald-300' : 'border-slate-200' }}">
                                <input type="radio" name="jenis" value="masuk" {{ $keuangan->jenis == 'masuk' ? 'checked' : '' }} required>
                                <span class="material-symbols-outlined text-emerald-600">arrow_downward</span>
                                <span>Pemasukan</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer flex-1 {{ $keuangan->jenis == 'keluar' ? 'bg-red-50 border-red-300' : 'border-slate-200' }}">
                                <input type="radio" name="jenis" value="keluar" {{ $keuangan->jenis == 'keluar' ? 'checked' : '' }} required>
                                <span class="material-symbols-outlined text-red-500">arrow_upward</span>
                                <span>Pengeluaran</span>
                            </label>
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                        <select name="kategori" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="Pembelian Bahan" {{ $keuangan->kategori == 'Pembelian Bahan' ? 'selected' : '' }}>🛒 Pembelian Bahan</option>
                            <option value="Penjualan Menu" {{ $keuangan->kategori == 'Penjualan Menu' ? 'selected' : '' }}>💰 Penjualan Menu</option>
                            <option value="Gaji Karyawan" {{ $keuangan->kategori == 'Gaji Karyawan' ? 'selected' : '' }}>👥 Gaji Karyawan</option>
                            <option value="Biaya Operasional" {{ $keuangan->kategori == 'Biaya Operasional' ? 'selected' : '' }}>⚡ Biaya Operasional</option>
                            <option value="Biaya Transportasi" {{ $keuangan->kategori == 'Biaya Transportasi' ? 'selected' : '' }}>🚚 Biaya Transportasi</option>
                            <option value="Peralatan Dapur" {{ $keuangan->kategori == 'Peralatan Dapur' ? 'selected' : '' }}>🔧 Peralatan Dapur</option>
                            <option value="Lainnya" {{ $keuangan->kategori == 'Lainnya' ? 'selected' : '' }}>📦 Lainnya</option>
                        </select>
                    </div>

                    <!-- Sumber/Tujuan -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sumber / Tujuan</label>
                        <input type="text" name="sumber_tujuan" value="{{ old('sumber_tujuan', $keuangan->sumber_tujuan) }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Contoh: Supplier Makmur, Pelanggan A">
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah (Rp)</label>
                        <input type="text" 
                               name="jumlah" 
                               value="{{ old('jumlah', $keuangan->jumlah) }}"
                               required
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="600000000"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <p class="text-xs text-slate-400 mt-1">Contoh: 600000000 untuk 600 juta</p>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" required value="{{ old('tanggal_transaksi', $keuangan->tanggal_transaksi->format('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <!-- Bukti Gambar -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Bukti Transaksi</label>
                        @if($keuangan->bukti_gambar)
                            <div class="mb-3 flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                                <img src="{{ asset('storage/' . $keuangan->bukti_gambar) }}" class="w-16 h-16 rounded-lg object-cover">
                                <span class="text-xs text-slate-500">Gambar saat ini</span>
                            </div>
                        @endif
                        <input type="file" name="bukti_gambar" accept="image/*"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500
                                      file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 
                                      file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700
                                      hover:file:bg-emerald-100 transition">
                        <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" 
                                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                  placeholder="Detail transaksi...">{{ old('keterangan', $keuangan->keterangan) }}</textarea>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-8 pt-4 border-t border-slate-100">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-semibold transition">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('keuangan.laporan') }}" class="flex-1 border border-slate-300 text-slate-700 py-3 rounded-xl text-center hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection