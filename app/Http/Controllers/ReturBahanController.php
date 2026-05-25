<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\ReturBahan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReturBahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $query = ReturBahan::with('bahan', 'user');
        
        if ($request->filled('search')) {
            $query->where('kode_retur', 'like', '%' . $request->search . '%')
                  ->orWhereHas('bahan', function($q) use ($request) {
                      $q->where('nama', 'like', '%' . $request->search . '%');
                  });
        }
        
        if ($request->filled('jenis') && $request->jenis != 'semua') {
            $query->where('jenis', $request->jenis);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_retur', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_retur', '<=', $request->end_date);
        }
        
        $returs = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        $statistik = [
            'total' => ReturBahan::count(),
            'total_rusak' => ReturBahan::where('jenis', 'rusak')->sum('jumlah'),
            'total_kadaluarsa' => ReturBahan::where('jenis', 'kadaluarsa')->sum('jumlah'),
            'total_tercecer' => ReturBahan::where('jenis', 'tercecer')->sum('jumlah'),
            'bulan_ini' => ReturBahan::whereMonth('tanggal_retur', now()->month)->sum('jumlah'),
        ];
        
        $bahans = Bahan::orderBy('nama')->get();
        
        return view('retur-bahan.index', compact('returs', 'statistik', 'bahans'));
    }
    
    public function create()
    {
        $bahans = Bahan::orderBy('nama')->get();
        return view('retur-bahan.create', compact('bahans'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah' => 'required|numeric|min:0.01',
            'jenis' => 'required|in:rusak,kadaluarsa,tercecer, lainnya',
            'tanggal_retur' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        try {
            $bahan = Bahan::find($request->bahan_id);
            
            if ($bahan->stok < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi! Stok tersedia: ' . $bahan->stok . ' ' . $bahan->satuan);
            }
            
            // Kurangi stok
            $bahan->stok -= $request->jumlah;
            $bahan->save();
            
            // Simpan retur
            $retur = ReturBahan::create([
                'kode_retur' => ReturBahan::generateKode(),
                'bahan_id' => $request->bahan_id,
                'jumlah' => $request->jumlah,
                'satuan' => $bahan->satuan,
                'jenis' => $request->jenis,
                'keterangan' => $request->keterangan,
                'tanggal_retur' => $request->tanggal_retur,
                'user_id' => Auth::id()
            ]);
            
            // Catat ke log aktivitas
            ActivityLog::log(
                Auth::id(),
                Auth::user()->name,
                Auth::user()->role,
                'CREATE',
                'Retur Bahan',
                "Mencatat retur bahan {$bahan->nama} sebanyak {$request->jumlah} {$bahan->satuan} ({$request->jenis})",
                $request
            );
            
            DB::commit();
            return redirect()->route('retur-bahan.index')
                ->with('success', 'Retur bahan berhasil dicatat! Stok berkurang ' . $request->jumlah . ' ' . $bahan->satuan);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function show(ReturBahan $returBahan)
    {
        $returBahan->load('bahan', 'user');
        return view('retur-bahan.show', compact('returBahan'));
    }
    
    public function destroy(ReturBahan $returBahan)
    {
        // Kembalikan stok jika retur dihapus
        $bahan = Bahan::find($returBahan->bahan_id);
        if ($bahan) {
            $bahan->stok += $returBahan->jumlah;
            $bahan->save();
        }
        
        $returBahan->delete();
        
        return redirect()->route('retur-bahan.index')
            ->with('success', 'Retur bahan berhasil dihapus! Stok dikembalikan.');
    }
}