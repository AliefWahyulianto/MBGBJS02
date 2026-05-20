<?php
// app/Http/Controllers/BahanController.php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\BahanExport;
use App\Exports\LaporanPdf;
use Maatwebsite\Excel\Facades\Excel;

class BahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Bahan::query();

        // Search
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kategori', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('kategori') && $request->kategori !== 'semua') {
            $query->where('kategori', $request->kategori);
        }

        // Filter status
        if ($request->filled('status') && $request->status !== 'semua') {
            if ($request->status === 'menipis') {
                $query->whereColumn('stok', '<=', 'stok_minimal')->where('stok', '>', 0);
            } elseif ($request->status === 'habis') {
                $query->where('stok', 0);
            } elseif ($request->status === 'aman') {
                $query->whereColumn('stok', '>', 'stok_minimal');
            }
        }

        $bahans = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Statistik
        $totalBahan = Bahan::count();
        $stokMenipis = Bahan::whereColumn('stok', '<=', 'stok_minimal')->where('stok', '>', 0)->count();
        $stokHabis = Bahan::where('stok', 0)->count();
        
        // Daftar kategori unik untuk filter
        $kategoris = Bahan::select('kategori')->distinct()->pluck('kategori');

        return view('bahan.index', compact('bahans', 'totalBahan', 'stokMenipis', 'stokHabis', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bahan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150|unique:bahans,nama',
            'kategori' => 'required|string|max:50',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:20',
            'harga_beli' => 'required|numeric|min:0',
            'stok_minimal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('bahans', 'public');
            $validated['gambar'] = $path;
        }

        Bahan::create($validated);

        return redirect()->route('bahan.index')
            ->with('success', '✅ Bahan "' . $request->nama . '" berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bahan $bahan)
    {
        return view('bahan.show', compact('bahan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bahan $bahan)
    {
        return view('bahan.edit', compact('bahan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bahan $bahan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150|unique:bahans,nama,' . $bahan->id,
            'kategori' => 'required|string|max:50',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:20',
            'harga_beli' => 'required|numeric|min:0',
            'stok_minimal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($bahan->gambar) {
                Storage::disk('public')->delete($bahan->gambar);
            }
            $path = $request->file('gambar')->store('bahans', 'public');
            $validated['gambar'] = $path;
        }

        $bahan->update($validated);

        return redirect()->route('bahan.index')
            ->with('success', '✅ Bahan "' . $bahan->nama . '" berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bahan $bahan)
    {
        $nama = $bahan->nama;
        
        if ($bahan->gambar) {
            Storage::disk('public')->delete($bahan->gambar);
        }
        
        $bahan->delete();

        return redirect()->route('bahan.index')
            ->with('success', '✅ Bahan "' . $nama . '" berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(new BahanExport, 'laporan-bahan-' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf()
    {
        return LaporanPdf::bahan();
    }
}