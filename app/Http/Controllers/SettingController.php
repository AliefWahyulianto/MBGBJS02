<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('setting.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'nullable|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'dapur_name' => 'nullable|string|max:255',
            'dapur_address' => 'nullable|string',
            'dapur_phone' => 'nullable|string|max:20',
            'dapur_email' => 'nullable|email|max:100',
            'stok_minimal_percent' => 'nullable|numeric|min:0|max:100',
            'stok_masuk_default_supplier' => 'nullable|string|max:100',
            'produksi_default_status' => 'nullable|string',
            'keuangan_default_kategori' => 'nullable|string',
            'laporan_default_periode' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        // Handle logo upload
        if ($request->hasFile('app_logo')) {
            $path = $request->file('app_logo')->store('settings', 'public');
            Setting::set('app_logo', $path);
        }

        return redirect()->route('setting.index')
            ->with('success', 'Pengaturan berhasil disimpan!');
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        
        return redirect()->back()->with('success', 'Cache berhasil dibersihkan!');
    }

    public function backup()
    {
        // Simulasi backup (nanti bisa ditambah fitur export database)
        return redirect()->back()->with('info', 'Fitur backup akan segera hadir');
    }
}