<?php

namespace App\Http\Controllers;

use App\Models\StokMengendap;
use Illuminate\Http\Request;

class StokMengendapController extends Controller
{
    public function index()
    {
        $stokMengendap = StokMengendap::with('bahan', 'produksi.menu')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Perbaiki: gunakan jumlah_kelebihan, bukan jumlah_diambil
        $totalMenunggu = StokMengendap::where('status', 'menunggu')->sum('jumlah_kelebihan');
        $totalTerpakai = StokMengendap::where('status', 'terpakai')->sum('jumlah_terpakai');
        
        return view('stok-mengendap.index', compact('stokMengendap', 'totalMenunggu', 'totalTerpakai'));
    }
    
    public function gunakan(Request $request, $id)
    {
        $stokMengendap = StokMengendap::findOrFail($id);
        
        $request->validate([
            'jumlah' => 'required|numeric|min:0.01|max:' . $stokMengendap->jumlah_kelebihan
        ]);
        
        $stokMengendap->jumlah_terpakai += $request->jumlah;
        $stokMengendap->jumlah_kelebihan -= $request->jumlah;
        
        if ($stokMengendap->jumlah_kelebihan <= 0) {
            $stokMengendap->status = 'terpakai';
            $stokMengendap->tanggal_terpakai = now();
        }
        
        $stokMengendap->save();
        
        return redirect()->route('stok-mengendap.index')
            ->with('success', 'Stok mengendap berhasil digunakan!');
    }
}