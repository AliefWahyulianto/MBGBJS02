<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\StokMasuk;
use App\Models\Transaksi;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\StokMasukExport;
use Maatwebsite\Excel\Facades\Excel;

class StokMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahans = Bahan::orderBy('nama')->get();
        $suppliers = Supplier::where('status', 'aktif')->orderBy('nama')->get();
        $stokMasuk = StokMasuk::with('bahan', 'supplier')->orderBy('tanggal_masuk', 'desc')->paginate(10);  // ← tambah 'supplier'
        
        // Total stok masuk hari ini
        $totalStokMasukHariIni = StokMasuk::whereDate('tanggal_masuk', today())->sum('jumlah');
        $totalTransaksiHariIni = StokMasuk::whereDate('tanggal_masuk', today())->count();
        
        return view('stok-masuk.index', compact('bahans', 'suppliers', 'stokMasuk', 'totalStokMasukHariIni', 'totalTransaksiHariIni'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'jumlah' => 'required|numeric|min:0.01',
            'harga_satuan' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'no_invoice' => 'nullable|string|max:100',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $bahan = Bahan::find($request->bahan_id);
            $totalHarga = $request->jumlah * $request->harga_satuan;

            // 1. Simpan stok masuk
            $stokMasuk = StokMasuk::create([
                'bahan_id' => $request->bahan_id,
                'supplier_id' => $request->supplier_id,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $request->harga_satuan,
                'total_harga' => $totalHarga,
                'tanggal_masuk' => $request->tanggal_masuk,
                'no_invoice' => $request->no_invoice,
                'catatan' => $request->catatan,
                'status' => 'verified'
            ]);

            // 2. Update stok bahan
            $bahan->stok += $request->jumlah;
            $bahan->save();

            // 3. Update statistik supplier
            if ($request->supplier_id) {
                $supplier = Supplier::find($request->supplier_id);
                if ($supplier) {
                    $supplier->total_transaksi += 1;
                    $supplier->total_pembelian += $totalHarga;
                    $supplier->terakhir_transaksi = now();
                    $supplier->save();
                }
            }

            // 4. Catat transaksi keuangan
            Transaksi::create([
                'kode_transaksi' => Transaksi::generateKode(),
                'jenis' => 'keluar',
                'kategori' => 'Pembelian Bahan',
                'sumber_tujuan' => $bahan->nama,
                'jumlah' => $totalHarga,
                'keterangan' => "Pembelian {$bahan->nama} {$request->jumlah} {$bahan->satuan} @ Rp " . number_format($request->harga_satuan, 0, ',', '.'),
                'tanggal_transaksi' => $request->tanggal_masuk,
                'status' => 'verified'
            ]);

            DB::commit();
            return redirect()->route('stok-masuk.index')
                ->with('success', 'Stok masuk, transaksi keuangan, dan statistik supplier berhasil dicatat!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StokMasuk $stokMasuk)
    {
        $stokMasuk->load('bahan', 'supplier');
        return view('stok-masuk.show', compact('stokMasuk'));
    }

    /**
     * Filter data untuk AJAX
     */
    public function filter(Request $request)
    {
        $query = StokMasuk::with('bahan', 'supplier');  // ← tambah 'supplier'
        
        if ($request->start_date) {
            $query->whereDate('tanggal_masuk', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('tanggal_masuk', '<=', $request->end_date);
        }
        if ($request->bahan_id) {
            $query->where('bahan_id', $request->bahan_id);
        }
        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        return response()->json($query->orderBy('tanggal_masuk', 'desc')->get());
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new StokMasukExport($request->start_date, $request->end_date), 'laporan-stok-masuk-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        return LaporanPdf::stokMasuk($request->start_date, $request->end_date);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StokMasuk $stokMasuk)
    {
        DB::beginTransaction();
        try {
            // Kembalikan stok bahan
            $bahan = Bahan::find($stokMasuk->bahan_id);
            if ($bahan) {
                $bahan->stok -= $stokMasuk->jumlah;
                $bahan->save();
            }
            
            // Update supplier stats (kurangi total pembelian)
            if ($stokMasuk->supplier_id) {
                $supplier = Supplier::find($stokMasuk->supplier_id);
                if ($supplier) {
                    $supplier->total_transaksi -= 1;
                    $supplier->total_pembelian -= $stokMasuk->total_harga;
                    $supplier->save();
                }
            }
            
            // Hapus transaksi keuangan terkait
            Transaksi::where('keterangan', 'like', '%' . ($stokMasuk->bahan->nama ?? ''))
                ->whereDate('tanggal_transaksi', $stokMasuk->tanggal_masuk)
                ->where('jumlah', $stokMasuk->total_harga)
                ->delete();
            
            // Hapus stok masuk
            $stokMasuk->delete();
            
            DB::commit();
            return redirect()->route('stok-masuk.index')
                ->with('success', 'Stok masuk berhasil dihapus dan stok dikembalikan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}