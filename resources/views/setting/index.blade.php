@extends('layouts.app')

@php
use App\Models\Setting;
@endphp

@section('content')
<div class="max-w-6xl mx-auto space-y-8 p-8 fade-in-up">

    <!-- HEADER -->
    <div class="flex flex-wrap justify-between items-center gap-4 card-stagger">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Pengaturan Sistem</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Kelola konfigurasi aplikasi Dapur MBG</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('setting.clear-cache') }}" 
               class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-semibold transition shadow-sm hover:shadow-md"
               onclick="return confirm('Bersihkan cache?')">
                <span class="material-symbols-outlined text-base align-middle mr-1">cleaning_services</span>
                Bersihkan Cache
            </a>
        </div>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl flex items-center gap-2 card-stagger">
            <span class="material-symbols-outlined text-emerald-500">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('setting.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Profil Dapur -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden card-stagger" style="animation-delay: 0.05s">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-700/30">
                <h3 class="font-semibold text-slate-800 dark:text-white text-lg">🏢 Profil Dapur</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Dapur</label>
                    <input type="text" name="dapur_name" value="{{ Setting::get('dapur_name', 'Dapur MBG Bojongsari 02') }}" 
                           class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Telepon</label>
                    <input type="text" name="dapur_phone" value="{{ Setting::get('dapur_phone', '') }}" 
                           class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Alamat</label>
                    <textarea name="dapur_address" rows="2" 
                              class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">{{ Setting::get('dapur_address', '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Logo Dapur</label>
                    @if(Setting::get('app_logo'))
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . Setting::get('app_logo')) }}" class="w-16 h-16 rounded-lg object-cover border border-slate-200 dark:border-slate-600">
                        </div>
                    @endif
                    <input type="file" name="app_logo" accept="image/*" 
                           class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 dark:file:bg-emerald-900/30 dark:file:text-emerald-400">
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Format: JPG, PNG (Max 2MB)</p>
                </div>
            </div>
        </div>

        <!-- 2. Preferensi Stok -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden card-stagger" style="animation-delay: 0.1s">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-700/30">
                <h3 class="font-semibold text-slate-800 dark:text-white text-lg">📦 Preferensi Stok</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Peringatan Stok Minimal (%)</label>
                    <input type="number" name="stok_minimal_percent" value="{{ Setting::get('stok_minimal_percent', 20) }}" 
                           class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Peringatan muncul jika stok ≤ persentase ini dari stok minimal</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Default Satuan</label>
                    <select name="default_satuan" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                        <option value="kg" {{ Setting::get('default_satuan') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="gram" {{ Setting::get('default_satuan') == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                        <option value="liter" {{ Setting::get('default_satuan') == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                        <option value="butir" {{ Setting::get('default_satuan') == 'butir' ? 'selected' : '' }}>Butir</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Auto Hapus Stok 0</label>
                    <select name="auto_delete_stok_0" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                        <option value="0" {{ Setting::get('auto_delete_stok_0') == '0' ? 'selected' : '' }}>❌ Tidak</option>
                        <option value="1" {{ Setting::get('auto_delete_stok_0') == '1' ? 'selected' : '' }}>✅ Ya</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 3. Preferensi Keuangan -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden card-stagger" style="animation-delay: 0.15s">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-700/30">
                <h3 class="font-semibold text-slate-800 dark:text-white text-lg">💰 Preferensi Keuangan</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Mata Uang</label>
                    <select name="currency" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                        <option value="IDR" {{ Setting::get('currency') == 'IDR' ? 'selected' : '' }}>IDR (Rp)</option>
                        <option value="USD" {{ Setting::get('currency') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Format Angka</label>
                    <select name="number_format" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                        <option value="1.000.000" {{ Setting::get('number_format') == '1.000.000' ? 'selected' : '' }}>1.000.000</option>
                        <option value="1,000,000" {{ Setting::get('number_format') == '1,000,000' ? 'selected' : '' }}>1,000,000</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 4. Preferensi Laporan -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden card-stagger" style="animation-delay: 0.2s">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-700/30">
                <h3 class="font-semibold text-slate-800 dark:text-white text-lg">📄 Preferensi Laporan</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Default Periode Laporan</label>
                    <select name="laporan_default_periode" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                        <option value="7_hari" {{ Setting::get('laporan_default_periode') == '7_hari' ? 'selected' : '' }}>7 Hari Terakhir</option>
                        <option value="30_hari" {{ Setting::get('laporan_default_periode') == '30_hari' ? 'selected' : '' }}>30 Hari Terakhir</option>
                        <option value="bulan_ini" {{ Setting::get('laporan_default_periode') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Format Tanggal</label>
                    <select name="date_format" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                        <option value="DD/MM/YYYY" {{ Setting::get('date_format') == 'DD/MM/YYYY' ? 'selected' : '' }}>DD/MM/YYYY</option>
                        <option value="YYYY-MM-DD" {{ Setting::get('date_format') == 'YYYY-MM-DD' ? 'selected' : '' }}>YYYY-MM-DD</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 5. Informasi Aplikasi -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden card-stagger" style="animation-delay: 0.25s">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-700/30">
                <h3 class="font-semibold text-slate-800 dark:text-white text-lg">⚙️ Informasi Aplikasi</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Aplikasi</label>
                    <input type="text" name="app_name" value="{{ Setting::get('app_name', 'KMS Admin') }}" 
                           class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Versi Aplikasi</label>
                    <input type="text" name="app_version" value="{{ Setting::get('app_version', '1.0.0') }}" 
                           class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-sm bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-400 cursor-not-allowed" readonly>
                </div>
            </div>
        </div>

        <!-- TOMBOL SIMPAN -->
        <div class="flex justify-end card-stagger" style="animation-delay: 0.3s">
            <button type="submit" class="px-6 py-3 bg-gradient-primary text-white rounded-xl font-semibold transition shadow-md hover:shadow-lg flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">save</span>
                Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection