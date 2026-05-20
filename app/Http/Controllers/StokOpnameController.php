<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\StokOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokOpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahans = Bahan::orderBy('nama')->get();
        $stokOpname = StokOpname::with('bahan', 'user')
            ->orderBy('tanggal_opname', 'desc')
            ->paginate(15);
        
        // Statistik opname
        $totalSelisih = StokOpname::sum('selisih');
        $totalOpnameHariIni = StokOpname::whereDate('tanggal_opname', today())->count();
        $bahanPernahOpname = StokOpname::distinct('bahan_id')->count('bahan_id');
        
        return view('stok-opname.index', compact('bahans', 'stokOpname', 'totalSelisih', 'totalOpnameHariIni', 'bahanPernahOpname'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bahans = Bahan::orderBy('nama')->get();
        return view('stok-opname.create', compact('bahans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'stok_fisik' => 'required|numeric|min:0',
            'tanggal_opname' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $bahan = Bahan::find($request->bahan_id);
            $stokSistem = $bahan->stok;
            $stokFisik = $request->stok_fisik;
            $selisih = $stokFisik - $stokSistem;

            // Simpan record opname
            $opname = StokOpname::create([
                'bahan_id' => $request->bahan_id,
                'stok_sistem' => $stokSistem,
                'stok_fisik' => $stokFisik,
                'selisih' => $selisih,
                'keterangan' => $request->keterangan,
                'tanggal_opname' => $request->tanggal_opname,
                'opname_by' => auth()->id() ?? 1 // sementara pakai user id 1
            ]);

            // Update stok bahan sesuai stok fisik
            $bahan->stok = $stokFisik;
            $bahan->save();

            DB::commit();

            $message = $selisih >= 0 
                ? "✅ Opname selesai! Selisih +" . number_format($selisih, 2) . " " . $bahan->satuan . " (stok bertambah)"
                : "✅ Opname selesai! Selisih " . number_format($selisih, 2) . " " . $bahan->satuan . " (stok berkurang)";

            return redirect()->route('stok-opname.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StokOpname $stokOpname)
    {
        return view('stok-opname.show', compact('stokOpname'));
    }

    /**
     * Get stok sistem untuk AJAX
     */
    public function getStokSistem(Request $request)
    {
        $bahan = Bahan::find($request->bahan_id);
        if ($bahan) {
            return response()->json([
                'success' => true,
                'stok_sistem' => $bahan->stok,
                'satuan' => $bahan->satuan,
                'nama' => $bahan->nama
            ]);
        }
        return response()->json(['success' => false, 'message' => 'Bahan tidak ditemukan']);
    }

    /**
     * Filter data untuk AJAX
     */
    public function filter(Request $request)
    {
        $query = StokOpname::with('bahan', 'user');
        
        if ($request->start_date) {
            $query->whereDate('tanggal_opname', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('tanggal_opname', '<=', $request->end_date);
        }
        if ($request->bahan_id) {
            $query->where('bahan_id', $request->bahan_id);
        }
        
        return response()->json($query->orderBy('tanggal_opname', 'desc')->get());
    }
}