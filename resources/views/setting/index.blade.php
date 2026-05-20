@extends('layouts.app')

@section('content')
@php
use App\Models\Setting;
@endphp
<div class="max-w-5xl mx-auto p-8 space-y-8">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengaturan Sistem</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola konfigurasi umum aplikasi Dapur MBG</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('setting.clear-cache') }}" 
               class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-semibold transition flex items-center gap-2"
               onclick="return confirm('Bersihkan cache sistem?')">
                <span class="material-symbols-outlined text-lg">cleaning_services</span>
                Bersihkan Cache
            </a>
            <a href="{{ route('setting.backup') }}" 
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">backup</span>
                Backup
            </a>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-500">info</span>
            {{ session('info') }}
        </div>
    @endif

    <!-- Form Pengaturan -->
    <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Informasi Aplikasi -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">info</span>
                    <h3 class="font-h2 text-h2 text-slate-900">Informasi Aplikasi</h3>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Aplikasi</label>
                    <input type="text" name="app_name" value="{{ Setting::get('app_name', 'KMS Admin') }}" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                    <p class="text-xs text-slate-400 mt-1">Nama yang tampil di sidebar dan title browser</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Logo Aplikasi</label>
                    @if(Setting::get('app_logo'))
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . Setting::get('app_logo')) }}" class="w-16 h-16 rounded-lg object-cover">
                        </div>
                    @endif
                    <input type="file" name="app_logo" accept="image/*" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700">
                </div>
            </div>
        </div>

        <!-- Informasi Dapur -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">restaurant</span>
                    <h3 class="font-h2 text-h2 text-slate-900">Informasi Dapur</h3>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Dapur</label>
                    <input type="text" name="dapur_name" value="{{ Setting::get('dapur_name', 'Dapur MBG Bojongsari 02') }}" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Telepon Dapur</label>
                    <input type="text" name="dapur_phone" value="{{ Setting::get('dapur_phone', '') }}" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email Dapur</label>
                    <input type="email" name="dapur_email" value="{{ Setting::get('dapur_email', '') }}" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Dapur</label>
                    <textarea name="dapur_address" rows="2" class="w-full px-4 py-2 border border-slate-300 rounded-lg">{{ Setting::get('dapur_address', '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Pengaturan Stok -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">inventory</span>
                    <h3 class="font-h2 text-h2 text-slate-900">Pengaturan Stok</h3>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Persentase Peringatan Stok Minimal</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="stok_minimal_percent" value="{{ Setting::get('stok_minimal_percent', 20) }}" 
                               class="w-24 px-4 py-2 border border-slate-300 rounded-lg">
                        <span class="text-slate-500">%</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Peringatan akan muncul jika stok ≤ persentase ini dari stok minimal</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Default Supplier Stok Masuk</label>
                    <input type="text" name="stok_masuk_default_supplier" value="{{ Setting::get('stok_masuk_default_supplier', '') }}" 
                           class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                </div>
            </div>
        </div>

        <!-- Pengaturan Produksi -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">factory</span>
                    <h3 class="font-h2 text-h2 text-slate-900">Pengaturan Produksi</h3>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Default Status Produksi</label>
                    <select name="produksi_default_status" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                        <option value="proses" {{ Setting::get('produksi_default_status') == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ Setting::get('produksi_default_status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Pengaturan Keuangan -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">payments</span>
                    <h3 class="font-h2 text-h2 text-slate-900">Pengaturan Keuangan</h3>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Default Kategori Pengeluaran</label>
                    <select name="keuangan_default_kategori" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                        <option value="Pembelian Bahan" {{ Setting::get('keuangan_default_kategori') == 'Pembelian Bahan' ? 'selected' : '' }}>Pembelian Bahan</option>
                        <option value="Gaji Karyawan" {{ Setting::get('keuangan_default_kategori') == 'Gaji Karyawan' ? 'selected' : '' }}>Gaji Karyawan</option>
                        <option value="Biaya Operasional" {{ Setting::get('keuangan_default_kategori') == 'Biaya Operasional' ? 'selected' : '' }}>Biaya Operasional</option>
                        <option value="Biaya Transportasi" {{ Setting::get('keuangan_default_kategori') == 'Biaya Transportasi' ? 'selected' : '' }}>Biaya Transportasi</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Pengaturan Laporan -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">assessment</span>
                    <h3 class="font-h2 text-h2 text-slate-900">Pengaturan Laporan</h3>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Default Periode Laporan</label>
                    <select name="laporan_default_periode" class="w-full px-4 py-2 border border-slate-300 rounded-lg">
                        <option value="bulan_ini" {{ Setting::get('laporan_default_periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="7_hari" {{ Setting::get('laporan_default_periode') == '7_hari' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30_hari" {{ Setting::get('laporan_default_periode') == '30_hari' ? 'selected' : '' }}>30 Hari Terakhir</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition flex items-center gap-2">
                <span class="material-symbols-outlined">save</span>
                Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection