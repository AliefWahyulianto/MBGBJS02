<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\StokMasuk;
use App\Models\Transaksi;
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
        $stokMasuk = StokMasuk::with('bahan')->orderBy('tanggal_masuk', 'desc')->paginate(10);
        
        // Total stok masuk hari ini
        $totalStokMasukHariIni = StokMasuk::whereDate('tanggal_masuk', today())->sum('jumlah');
        $totalTransaksiHariIni = StokMasuk::whereDate('tanggal_masuk', today())->count();
        
        return view('stok-masuk.index', compact('bahans', 'stokMasuk', 'totalStokMasukHariIni', 'totalTransaksiHariIni'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // VALIDASI dengan harga_satuan
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah' => 'required|numeric|min:0.01',
            'harga_satuan' => 'required|numeric|min:0', // TAMBAHKAN
            'tanggal_masuk' => 'required|date',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $bahan = Bahan::find($request->bahan_id);
            $totalHarga = $request->jumlah * $request->harga_satuan;

            // 1. Simpan stok masuk (dengan harga)
            $stokMasuk = StokMasuk::create([
                'bahan_id' => $request->bahan_id,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $request->harga_satuan,
                'total_harga' => $totalHarga,
                'tanggal_masuk' => $request->tanggal_masuk,
                'catatan' => $request->catatan
            ]);

            // 2. Update stok bahan
            $bahan->stok += $request->jumlah;
            $bahan->save();

            // 3. OTOMATIS CATAT TRANSAKSI KEUANGAN
            Transaksi::create([
                'kode_transaksi' => Transaksi::generateKode(),
                'jenis' => 'keluar', // stok masuk = pengeluaran uang
                'kategori' => 'Pembelian Bahan',
                'sumber_tujuan' => $bahan->nama,
                'jumlah' => $totalHarga,
                'keterangan' => "Pembelian {$bahan->nama} {$request->jumlah} {$bahan->satuan} @ Rp " . number_format($request->harga_satuan, 0, ',', '.'),
                'tanggal_transaksi' => $request->tanggal_masuk,
                'status' => 'verified'
            ]);

            DB::commit();
            return redirect()->route('stok-masuk.index')->with('success', 'Stok masuk dan transaksi keuangan berhasil dicatat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Filter data untuk AJAX
     */
    public function filter(Request $request)
    {
        $query = StokMasuk::with('bahan');
        
        if ($request->start_date) {
            $query->whereDate('tanggal_masuk', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('tanggal_masuk', '<=', $request->end_date);
        }
        if ($request->bahan_id) {
            $query->where('bahan_id', $request->bahan_id);
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
}