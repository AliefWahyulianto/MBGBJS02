<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();
        
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_supplier', 'like', '%' . $request->search . '%')
                  ->orWhere('kontak', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('kategori') && $request->kategori != 'semua') {
            $query->where('kategori', $request->kategori);
        }
        
        if ($request->filled('status') && $request->status != 'semua') {
            $query->where('status', $request->status);
        }
        
        $suppliers = $query->orderBy('nama', 'asc')->paginate(15)->withQueryString();
        
        // Statistik
        $totalSupplier = Supplier::count();
        $supplierAktif = Supplier::where('status', 'aktif')->count();
        $totalPembelian = Supplier::sum('total_pembelian');
        
        // Daftar kategori unik
        $kategoris = Supplier::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori');
        
        return view('supplier.index', compact('suppliers', 'totalSupplier', 'supplierAktif', 'totalPembelian', 'kategoris'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'kontak' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'kategori' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string'
        ]);

        $validated['kode_supplier'] = Supplier::generateKode();
        $validated['rating'] = 0;
        $validated['total_transaksi'] = 0;
        $validated['total_pembelian'] = 0;

        Supplier::create($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function show(Supplier $supplier)
    {
        // Ambil histori pembelian dari stok masuk
        $historiPembelian = \App\Models\StokMasuk::where('supplier_id', $supplier->id)
            ->with('bahan')
            ->orderBy('tanggal_masuk', 'desc')
            ->paginate(10);
        
        return view('supplier.show', compact('supplier', 'historiPembelian'));
    }

    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'kontak' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'kategori' => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif',
            'keterangan' => 'nullable|string'
        ]);

        $supplier->update($validated);

        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil diupdate!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        
        return redirect()->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus!');
    }
}