<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Bahan;
use App\Models\Produksi;
use App\Models\ProduksiDetail;
use App\Models\StokKeluar;
use App\Models\StokMengendap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    public function index()
    {
        $produksi = Produksi::with('menu', 'user')
            ->orderBy('tanggal_produksi', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $statistik = [
            'hari_ini' => Produksi::whereDate('tanggal_produksi', today())->sum('jumlah_porsi'),
            'minggu_ini' => Produksi::whereBetween('tanggal_produksi', [now()->startOfWeek(), now()->endOfWeek()])->sum('jumlah_porsi'),
            'bulan_ini' => Produksi::whereMonth('tanggal_produksi', now()->month)->sum('jumlah_porsi'),
            'total_produksi' => Produksi::sum('jumlah_porsi')
        ];
        
        return view('produksi.index', compact('produksi', 'statistik'));
    }
    
    public function create()
    {
        $menus = Menu::where('status', 'tersedia')->orderBy('nama')->get();
        return view('produksi.create', compact('menus'));
    }
    
    public function cekKebutuhan(Request $request)
    {
        $menuId = $request->menu_id;
        $jumlahPorsi = $request->jumlah_porsi;
        
        $menu = Menu::with('resep.bahan')->find($menuId);
        
        if (!$menu) {
            return response()->json(['error' => 'Menu tidak ditemukan'], 404);
        }
        
        $kebutuhan = [];
        $cukup = true;
        
        foreach ($menu->resep as $item) {
            $jumlahDibutuhkan = $item->jumlah * $jumlahPorsi;
            $stokTersedia = $item->bahan->stok;
            $status = $stokTersedia >= $jumlahDibutuhkan ? 'cukup' : 'kurang';
            
            if ($status == 'kurang') {
                $cukup = false;
            }
            
            $kebutuhan[] = [
                'bahan_id' => $item->bahan->id,
                'bahan_nama' => $item->bahan->nama,
                'satuan' => $item->satuan,
                'per_porsi' => $item->jumlah,
                'dibutuhkan' => $jumlahDibutuhkan,
                'stok_tersedia' => $stokTersedia,
                'status' => $status
            ];
        }
        
        return response()->json([
            'menu' => $menu->nama,
            'jumlah_porsi' => $jumlahPorsi,
            'kebutuhan' => $kebutuhan,
            'cukup' => $cukup
        ]);
    }
    
    public function store(Request $request)
    {
        \Log::info('=== PRODUKSI STORE ===');
        \Log::info($request->all());
        
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'jumlah_porsi' => 'required|integer|min:1',
            'tanggal_produksi' => 'required|date',
            'catatan' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        try {
            $menu = Menu::with('resep.bahan')->find($request->menu_id);
            
            if (!$menu) {
                throw new \Exception('Menu tidak ditemukan');
            }
            
            // 🔍 DEBUG: Cek jumlah resep
            \Log::info('Menu ID: ' . $menu->id);
            \Log::info('Menu Nama: ' . $menu->nama);
            \Log::info('Jumlah resep: ' . $menu->resep->count());
            
            if ($menu->resep->count() == 0) {
                throw new \Exception('Menu tidak memiliki komposisi bahan. Silakan tambahkan komposisi bahan terlebih dahulu.');
            }
            
            // Hitung kebutuhan bahan
            $kebutuhan = [];
            foreach ($menu->resep as $item) {
                $jumlahDibutuhkan = $item->jumlah * $request->jumlah_porsi;
                $kebutuhan[] = [
                    'bahan' => $item->bahan,
                    'jumlah' => $jumlahDibutuhkan,
                    'satuan' => $item->satuan
                ];
                
                // 🔍 DEBUG: Log setiap bahan
                \Log::info('Bahan: ' . $item->bahan->nama . ', Diambil: ' . $jumlahDibutuhkan . ' ' . $item->satuan);
            }
            
            // Cek stok
            foreach ($kebutuhan as $item) {
                if ($item['bahan']->stok < $item['jumlah']) {
                    throw new \Exception("Stok {$item['bahan']->nama} tidak mencukupi. Tersedia: {$item['bahan']->stok} {$item['satuan']}, Dibutuhkan: {$item['jumlah']} {$item['satuan']}");
                }
            }
            
            // Simpan produksi
            $produksi = Produksi::create([
                'menu_id' => $request->menu_id,
                'jumlah_porsi' => $request->jumlah_porsi,
                'tanggal_produksi' => $request->tanggal_produksi,
                'jam_mulai' => now(),
                'status' => 'proses',
                'catatan' => $request->catatan,
                'produksi_by' => Auth::id() ?? 1
            ]);
            
            \Log::info('Produksi sukses disimpan, ID: ' . $produksi->id);
            
            // Simpan detail dan kurangi stok
            foreach ($kebutuhan as $item) {
                $bahan = $item['bahan'];
                $stokSebelum = $bahan->stok;
                $stokSesudah = $stokSebelum - $item['jumlah'];
                
                ProduksiDetail::create([
                    'produksi_id' => $produksi->id,
                    'bahan_id' => $bahan->id,
                    'jumlah' => $item['jumlah'],
                    'satuan' => $item['satuan'],
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSesudah
                ]);
                
                $bahan->stok = $stokSesudah;
                $bahan->save();
                
                StokKeluar::create([
                    'bahan_id' => $bahan->id,
                    'jumlah' => $item['jumlah'],
                    'tanggal_keluar' => $request->tanggal_produksi,
                    'keterangan' => "Produksi menu: {$menu->nama} ({$request->jumlah_porsi} porsi)",
                    'is_for_produksi' => true,
                    'produksi_id' => $produksi->id
                ]);
                
                StokMengendap::create([
                    'produksi_id' => $produksi->id,
                    'bahan_id' => $bahan->id,
                    'jumlah_diambil' => $item['jumlah'],
                    'jumlah_kelebihan' => 0,
                    'jumlah_kekurangan' => 0,
                    'jumlah_terpakai' => 0,
                    'jumlah_sisa' => $item['jumlah'],
                    'satuan' => $item['satuan'],
                    'status' => 'proses',
                    'tanggal_mengendap' => $request->tanggal_produksi
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('produksi.show', $produksi)
                ->with('success', "Produksi {$menu->nama} ({$request->jumlah_porsi} porsi) berhasil! Silakan update sisa bahan.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error store: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
    
    public function show(Produksi $produksi)
    {
        $produksi->load('menu', 'detail.bahan', 'user');
        $stokMengendap = StokMengendap::where('produksi_id', $produksi->id)
            ->with('bahan')
            ->get()
            ->keyBy('bahan_id');
        
        return view('produksi.show', compact('produksi', 'stokMengendap'));
    }
    
    public function updateSisa(Request $request, Produksi $produksi)
{
    $request->validate([
        'items' => 'required|array',
        'items.*.bahan_id' => 'required|exists:bahans,id',
        'items.*.terpakai' => 'required|numeric|min:0',
        'catatan' => 'nullable|string'
    ]);

    DB::beginTransaction();
    try {
        foreach ($request->items as $item) {
            $stokMengendap = StokMengendap::where('produksi_id', $produksi->id)
                ->where('bahan_id', $item['bahan_id'])
                ->first();
            
            if (!$stokMengendap) {
                \Log::warning('StokMengendap tidak ditemukan untuk produksi_id: ' . $produksi->id . ', bahan_id: ' . $item['bahan_id']);
                continue;
            }
            
            $terpakai = floatval($item['terpakai']);
            $diambil = $stokMengendap->jumlah_diambil;
            $sisa = max(0, $diambil - $terpakai);
            
            $stokMengendap->update([
                'jumlah_terpakai' => $terpakai,
                'jumlah_kelebihan' => $sisa,
                'jumlah_sisa' => $sisa,
                'status' => $sisa > 0 ? 'menunggu' : 'habis',
                'catatan' => $request->catatan
            ]);
        }
        
        $produksi->jam_selesai = now();
        $produksi->status = 'selesai';
        $produksi->save();
        
        DB::commit();
        return redirect()->route('produksi.show', $produksi)
            ->with('success', 'Sisa bahan berhasil dicatat!');
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error updateSisa: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}
    
    public function catatKelebihan(Request $request, Produksi $produksi)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.bahan_id' => 'required|exists:bahans,id',
            'items.*.jumlah_kelebihan' => 'nullable|numeric|min:0',
            'items.*.jumlah_kekurangan' => 'nullable|numeric|min:0',
            'items.*.satuan' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            StokMengendap::where('produksi_id', $produksi->id)->delete();

            foreach ($request->items as $item) {
                $kelebihan = floatval($item['jumlah_kelebihan'] ?? 0);
                $kekurangan = floatval($item['jumlah_kekurangan'] ?? 0);
                
                if ($kelebihan > 0 || $kekurangan > 0) {
                    StokMengendap::create([
                        'produksi_id' => $produksi->id,
                        'bahan_id' => $item['bahan_id'],
                        'jumlah_diambil' => 0,
                        'jumlah_kelebihan' => $kelebihan,
                        'jumlah_kekurangan' => $kekurangan,
                        'jumlah_terpakai' => 0,
                        'jumlah_sisa' => $kelebihan,
                        'satuan' => $item['satuan'],
                        'status' => $kelebihan > 0 ? 'menunggu' : 'habis',
                        'tanggal_mengendap' => $produksi->tanggal_produksi,
                        'catatan' => $request->catatan
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('produksi.show', $produksi)
                ->with('success', 'Kelebihan/kekurangan bahan berhasil dicatat!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error catatKelebihan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function destroy(Produksi $produksi)
    {
        if ($produksi->status == 'selesai') {
            return redirect()->back()->with('error', 'Produksi yang sudah selesai tidak dapat dihapus!');
        }
        
        DB::beginTransaction();
        try {
            foreach ($produksi->detail as $detail) {
                $bahan = Bahan::find($detail->bahan_id);
                if ($bahan) {
                    $bahan->stok += $detail->jumlah;
                    $bahan->save();
                }
            }
            
            StokMengendap::where('produksi_id', $produksi->id)->delete();
            StokKeluar::where('produksi_id', $produksi->id)->delete();
            $produksi->delete();
            
            DB::commit();
            return redirect()->route('produksi.index')
                ->with('success', 'Produksi berhasil dihapus dan stok dikembalikan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}