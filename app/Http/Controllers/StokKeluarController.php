<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\StokKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\StokKeluarExport;
use Maatwebsite\Excel\Facades\Excel;


class StokKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahans = Bahan::orderBy('nama')->get();
        $stokKeluar = StokKeluar::with('bahan')->orderBy('tanggal_keluar', 'desc')->paginate(10);
        
        // Total stok keluar hari ini
        $totalStokKeluarHariIni = StokKeluar::whereDate('tanggal_keluar', today())->sum('jumlah');
        $totalTransaksiHariIni = StokKeluar::whereDate('tanggal_keluar', today())->count();
        
        // Total stok keluar semua
        $totalStokKeluarSemua = StokKeluar::sum('jumlah');
        
        // Stok menipis
        $stokMenipis = Bahan::whereColumn('stok', '<=', 'stok_minimal')->where('stok', '>', 0)->count();
        
        return view('stok-keluar.index', compact('bahans', 'stokKeluar', 'totalStokKeluarHariIni', 'totalTransaksiHariIni', 'totalStokKeluarSemua', 'stokMenipis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah' => 'required|numeric|min:0.01',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Cek apakah stok mencukupi
            $bahan = Bahan::find($request->bahan_id);
            if ($bahan->stok < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $bahan->stok . ' ' . $bahan->satuan)->withInput();
            }

            // Simpan stok keluar
            $stokKeluar = StokKeluar::create([
                'bahan_id' => $request->bahan_id,
                'jumlah' => $request->jumlah,
                'tanggal_keluar' => $request->tanggal_keluar,
                'keterangan' => $request->keterangan
            ]);

            // Update stok bahan (KURANGI)
            $bahan->stok -= $request->jumlah;
            $bahan->save();

            DB::commit();
            return redirect()->route('stok-keluar.index')->with('success', 'Stok keluar berhasil dicatat!');
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
        $query = StokKeluar::with('bahan');
        
        if ($request->start_date) {
            $query->whereDate('tanggal_keluar', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('tanggal_keluar', '<=', $request->end_date);
        }
        if ($request->bahan_id) {
            $query->where('bahan_id', $request->bahan_id);
        }
        
        return response()->json($query->orderBy('tanggal_keluar', 'desc')->get());
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new StokKeluarExport($request->start_date, $request->end_date), 'laporan-stok-keluar-' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        return LaporanPdf::stokKeluar($request->start_date, $request->end_date);
    }
}