@extends('layouts.app')

@php
use App\Models\Setting;
@endphp

@section('content')
<div class="max-w-6xl mx-auto p-8 space-y-8">

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengaturan Sistem</h1>
            <p class="text-slate-500 text-sm">Kelola konfigurasi aplikasi Dapur MBG</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('setting.clear-cache') }}" 
               class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-semibold"
               onclick="return confirm('Bersihkan cache?')">
                🧹 Bersihkan Cache
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 1. Profil Dapur -->
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="p-5 border-b bg-slate-50/50">
                <h3 class="font-semibold text-lg">🏢 Profil Dapur</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Dapur</label>
                    <input type="text" name="dapur_name" value="{{ Setting::get('dapur_name', 'Dapur MBG Bojongsari 02') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Telepon</label>
                    <input type="text" name="dapur_phone" value="{{ Setting::get('dapur_phone', '') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Alamat</label>
                    <textarea name="dapur_address" rows="2" class="w-full border rounded-lg px-4 py-2">{{ Setting::get('dapur_address', '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Logo Dapur</label>
                    @if(Setting::get('app_logo'))
                        <img src="{{ asset('storage/' . Setting::get('app_logo')) }}" class="w-20 h-20 rounded-lg mb-2">
                    @endif
                    <input type="file" name="app_logo" accept="image/*" class="w-full border rounded-lg px-4 py-2">
                </div>
            </div>
        </div>
    <br><br>
        <!-- 2. Preferensi Stok -->
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="p-5 border-b bg-slate-50/50">
                <h3 class="font-semibold text-lg">📦 Preferensi Stok</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Peringatan Stok Minimal (%)</label>
                    <input type="number" name="stok_minimal_percent" value="{{ Setting::get('stok_minimal_percent', 20) }}" class="w-full border rounded-lg px-4 py-2">
                    <p class="text-xs text-slate-400 mt-1">Peringatan muncul jika stok ≤ persentase ini</p>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Default Satuan</label>
                    <select name="default_satuan" class="w-full border rounded-lg px-4 py-2">
                        <option value="kg" {{ Setting::get('default_satuan') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="gram" {{ Setting::get('default_satuan') == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                        <option value="liter" {{ Setting::get('default_satuan') == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                        <option value="butir" {{ Setting::get('default_satuan') == 'butir' ? 'selected' : '' }}>Butir</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Auto Hapus Stok 0</label>
                    <select name="auto_delete_stok_0" class="w-full border rounded-lg px-4 py-2">
                        <option value="0" {{ Setting::get('auto_delete_stok_0') == '0' ? 'selected' : '' }}>❌ Tidak</option>
                        <option value="1" {{ Setting::get('auto_delete_stok_0') == '1' ? 'selected' : '' }}>✅ Ya</option>
                    </select>
                </div>
            </div>
        </div>
    <br><br>
        <!-- 3. Preferensi Keuangan -->
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="p-5 border-b bg-slate-50/50">
                <h3 class="font-semibold text-lg">💰 Preferensi Keuangan</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Mata Uang</label>
                    <select name="currency" class="w-full border rounded-lg px-4 py-2">
                        <option value="IDR" {{ Setting::get('currency') == 'IDR' ? 'selected' : '' }}>IDR (Rp)</option>
                        <option value="USD" {{ Setting::get('currency') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Format Angka</label>
                    <select name="number_format" class="w-full border rounded-lg px-4 py-2">
                        <option value="1.000.000" {{ Setting::get('number_format') == '1.000.000' ? 'selected' : '' }}>1.000.000</option>
                        <option value="1,000,000" {{ Setting::get('number_format') == '1,000,000' ? 'selected' : '' }}>1,000,000</option>
                    </select>
                </div>
            </div>
        </div>
    <br><br>
        <!-- 4. Preferensi Laporan -->
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="p-5 border-b bg-slate-50/50">
                <h3 class="font-semibold text-lg">📄 Preferensi Laporan</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Default Periode Laporan</label>
                    <select name="laporan_default_periode" class="w-full border rounded-lg px-4 py-2">
                        <option value="7_hari" {{ Setting::get('laporan_default_periode') == '7_hari' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30_hari" {{ Setting::get('laporan_default_periode') == '30_hari' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="bulan_ini" {{ Setting::get('laporan_default_periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Format Tanggal</label>
                    <select name="date_format" class="w-full border rounded-lg px-4 py-2">
                        <option value="DD/MM/YYYY" {{ Setting::get('date_format') == 'DD/MM/YYYY' ? 'selected' : '' }}>DD/MM/YYYY</option>
                        <option value="YYYY-MM-DD" {{ Setting::get('date_format') == 'YYYY-MM-DD' ? 'selected' : '' }}>YYYY-MM-DD</option>
                    </select>
                </div>
            </div>
        </div>
    <br><br>
        <!-- 5. Informasi Aplikasi -->
        <div class="bg-white rounded-xl border shadow-sm overflow-hidden">
            <div class="p-5 border-b bg-slate-50/50">
                <h3 class="font-semibold text-lg">⚙️ Informasi Aplikasi</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Aplikasi</label>
                    <input type="text" name="app_name" value="{{ Setting::get('app_name', 'KMS Admin') }}" class="w-full border rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Versi Aplikasi</label>
                    <input type="text" name="app_version" value="{{ Setting::get('app_version', '1.0.0') }}" class="w-full border rounded-lg px-4 py-2" readonly>
                </div>
            </div>
        </div>
    <br><br>
        <!-- Tombol Simpan -->
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold">
                💾 Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection