@extends('layouts.app')

@section('content')
<!-- MAIN CONTENT - Tambah Transaksi -->
<main class="space-y-6">
    <div class="max-w-2xl mx-auto">
        
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('keuangan.index') }}" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Tambah Transaksi</h1>
                <p class="text-slate-500 text-sm mt-1">Catat pemasukan atau pengeluaran dapur</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('keuangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-5">
                    <!-- Jenis Transaksi -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Jenis Transaksi</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-emerald-50 transition flex-1">
                                <input type="radio" name="jenis" value="masuk" required>
                                <span class="material-symbols-outlined text-emerald-600">arrow_downward</span>
                                <span>Pemasukan</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-red-50 transition flex-1">
                                <input type="radio" name="jenis" value="keluar" required>
                                <span class="material-symbols-outlined text-red-500">arrow_upward</span>
                                <span>Pengeluaran</span>
                            </label>
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                        <select name="kategori" required class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="">Pilih Kategori</option>
                            <option value="Pembelian Bahan">🛒 Pembelian Bahan</option>
                            <option value="Penjualan Menu">💰 Penjualan Menu</option>
                            <option value="Gaji Karyawan">👥 Gaji Karyawan</option>
                            <option value="Biaya Operasional">⚡ Biaya Operasional</option>
                            <option value="Biaya Transportasi">🚚 Biaya Transportasi</option>
                            <option value="Peralatan Dapur">🔧 Peralatan Dapur</option>
                            <option value="Lainnya">📦 Lainnya</option>
                        </select>
                    </div>

                    <!-- Sumber/Tujuan -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sumber / Tujuan</label>
                        <input type="text" name="sumber_tujuan" placeholder="Contoh: Supplier Makmur, Pelanggan A, dll"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <!-- Jumlah - TANPA batasan apapun -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah (Rp)</label>
                        <input type="text" 
                            name="jumlah" 
                            required
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="600000000"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <p class="text-xs text-slate-400 mt-1">Contoh: 600000000 untuk 600 juta</p>
                    </div>

                    <!-- Tanggal Transaksi -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Transaksi</label>
                        <input type="date" name="tanggal_transaksi" required value="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <!-- Bukti Gambar -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Bukti Transaksi (Struk/Faktur)</label>
                        <input type="file" name="bukti_gambar" accept="image/*"
                               class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500
                                      file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 
                                      file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700">
                        <p class="text-xs text-slate-400 mt-1">Upload bukti/foto transaksi (opsional)</p>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" 
                                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                  placeholder="Detail transaksi..."></textarea>
                    </div>
                </div>

                <div class="flex gap-3 mt-8 pt-4 border-t border-slate-100">
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-semibold transition">
                        Simpan Transaksi
                    </button>
                    <a href="{{ route('keuangan.index') }}" class="flex-1 border border-slate-300 text-slate-700 py-3 rounded-xl text-center hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection