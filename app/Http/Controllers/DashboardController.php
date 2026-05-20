<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use App\Models\Transaksi;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ========== STATISTIK BAHAN ==========
        $totalBahan = Bahan::count();
        $stokMenipis = Bahan::whereColumn('stok', '<=', 'stok_minimal')->where('stok', '>', 0)->count();
        $stokHabis = Bahan::where('stok', 0)->count();
        
        // Hitung persen perubahan stok menipis (contoh: dibanding minggu lalu)
        $stokMenipisLalu = Bahan::whereColumn('stok', '<=', 'stok_minimal')->where('stok', '>', 0)
            ->where('updated_at', '<', now()->subDays(7))
            ->count();
        $persenStokMenipis = $stokMenipisLalu > 0 
            ? round(($stokMenipis - $stokMenipisLalu) / $stokMenipisLalu * 100, 1)
            : 0;
        
        // ========== PENGELUARAN HARI INI ==========
        $pengeluaranHariIni = StokKeluar::whereDate('tanggal_keluar', today())
            ->join('bahans', 'stok_keluar.bahan_id', '=', 'bahans.id')
            ->sum(DB::raw('stok_keluar.jumlah * bahans.harga_beli'));
        
        $pengeluaranKemarin = StokKeluar::whereDate('tanggal_keluar', today()->subDay())
            ->join('bahans', 'stok_keluar.bahan_id', '=', 'bahans.id')
            ->sum(DB::raw('stok_keluar.jumlah * bahans.harga_beli'));
        
        $persenPerubahanPengeluaran = $pengeluaranKemarin > 0 
            ? round(($pengeluaranHariIni - $pengeluaranKemarin) / $pengeluaranKemarin * 100, 1)
            : 0;
        
        // ========== CHART PENGELUARAN MINGGUAN ==========
        $weeklyExpenses = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $expense = StokKeluar::whereDate('tanggal_keluar', $date)
                ->join('bahans', 'stok_keluar.bahan_id', '=', 'bahans.id')
                ->sum(DB::raw('stok_keluar.jumlah * bahans.harga_beli'));
            
            $weeklyExpenses[] = [
                'day' => $date->locale('id')->shortDayName,
                'amount' => $expense,
                'height' => 0 // akan dihitung di view
            ];
        }
        
        // Cari max untuk height chart
        $maxExpense = max(array_column($weeklyExpenses, 'amount')) ?: 1;
        foreach ($weeklyExpenses as &$item) {
            $item['height'] = ($item['amount'] / $maxExpense) * 100;
        }
        
        // ========== KATEGORI TERPOPULER ==========
        $kategoriTerpopuler = Bahan::select('kategori', DB::raw('COUNT(*) as total'))
            ->groupBy('kategori')
            ->orderBy('total', 'desc')
            ->limit(4)
            ->get();
        
        $totalKategori = $kategoriTerpopuler->sum('total');
        foreach ($kategoriTerpopuler as $kat) {
            $kat->persen = $totalKategori > 0 ? round(($kat->total / $totalKategori) * 100, 1) : 0;
        }
        
        // ========== AKTIVITAS TERKINI ==========
        $aktivitasTerbaru = collect();
        
        // Stok Masuk terbaru
        $stokMasukTerbaru = StokMasuk::with('bahan')->orderBy('created_at', 'desc')->limit(5)->get();
        foreach ($stokMasukTerbaru as $item) {
            $aktivitasTerbaru->push([
                'nama' => $item->bahan->nama ?? 'Bahan Dihapus',
                'tipe' => 'masuk',
                'tipe_text' => 'Masuk',
                'tipe_color' => 'text-emerald-600',
                'tipe_bg' => 'bg-emerald-50',
                'icon' => 'call_received',
                'jumlah' => number_format($item->jumlah, 2) . ' ' . ($item->bahan->satuan ?? ''),
                'waktu' => $item->created_at->diffForHumans(),
                'status' => 'Verified',
                'status_color' => 'bg-emerald-100 text-emerald-700'
            ]);
        }
        
        // Stok Keluar terbaru
        $stokKeluarTerbaru = StokKeluar::with('bahan')->orderBy('created_at', 'desc')->limit(5)->get();
        foreach ($stokKeluarTerbaru as $item) {
            $aktivitasTerbaru->push([
                'nama' => $item->bahan->nama ?? 'Bahan Dihapus',
                'tipe' => 'keluar',
                'tipe_text' => 'Keluar',
                'tipe_color' => 'text-red-500',
                'tipe_bg' => 'bg-red-50',
                'icon' => 'call_made',
                'jumlah' => number_format($item->jumlah, 2) . ' ' . ($item->bahan->satuan ?? ''),
                'waktu' => $item->created_at->diffForHumans(),
                'status' => $item->keterangan ? 'Diproses' : 'Verified',
                'status_color' => $item->keterangan ? 'bg-orange-100 text-orange-700' : 'bg-emerald-100 text-emerald-700'
            ]);
        }
        
        $aktivitasTerbaru = $aktivitasTerbaru->sortByDesc('waktu')->take(5);
        
        // ========== STATISTIK TAMBAHAN ==========
        $totalMenu = Menu::count();
        $menuTersedia = Menu::where('status', 'tersedia')->count();
        $totalTransaksiBulanIni = Transaksi::whereMonth('tanggal_transaksi', now()->month)->count();
        
        return view('dashboard.index', compact(
            'totalBahan',
            'stokMenipis',
            'stokHabis',
            'persenStokMenipis',
            'pengeluaranHariIni',
            'persenPerubahanPengeluaran',
            'weeklyExpenses',
            'maxExpense',
            'kategoriTerpopuler',
            'aktivitasTerbaru',
            'totalMenu',
            'menuTersedia',
            'totalTransaksiBulanIni'
        ));
    }
    
    // Filter 7 hari terakhir via AJAX
    public function filter7Hari(Request $request)
    {
        $startDate = now()->subDays(7);
        $endDate = now();
        
        $totalPengeluaran = StokKeluar::whereBetween('tanggal_keluar', [$startDate, $endDate])
            ->join('bahans', 'stok_keluar.bahan_id', '=', 'bahans.id')
            ->sum(DB::raw('stok_keluar.jumlah * bahans.harga_beli'));
        
        $pengeluaranSebelumnya = StokKeluar::whereBetween('tanggal_keluar', [now()->subDays(14), now()->subDays(8)])
            ->join('bahans', 'stok_keluar.bahan_id', '=', 'bahans.id')
            ->sum(DB::raw('stok_keluar.jumlah * bahans.harga_beli'));
        
        $persenPerubahan = $pengeluaranSebelumnya > 0 
            ? round(($totalPengeluaran - $pengeluaranSebelumnya) / $pengeluaranSebelumnya * 100, 1)
            : 0;
        
        return response()->json([
            'total' => $totalPengeluaran,
            'persen' => $persenPerubahan
        ]);
    }
}