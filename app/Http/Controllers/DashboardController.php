<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use App\Models\Transaksi;
use App\Models\Menu;
use App\Models\Produksi;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;
use App\Exports\DashboardExport;
use Maatwebsite\Excel\Facades\Excel;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter tahun & bulan
        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');
        
        // ========== STATISTIK BAHAN ==========
        $totalBahan = Bahan::count();
        $stokMenipis = Bahan::whereColumn('stok', '<=', 'stok_minimal')->where('stok', '>', 0)->count();
        $stokHabis = Bahan::where('stok', 0)->count();
        
        // ========== PENGELUARAN BULAN INI ==========
        $pengeluaranBulanIni = StokKeluar::whereMonth('tanggal_keluar', $bulan)
            ->whereYear('tanggal_keluar', $tahun)
            ->join('bahans', 'stok_keluar.bahan_id', '=', 'bahans.id')
            ->sum(DB::raw('stok_keluar.jumlah * bahans.harga_beli'));
        
        $pengeluaranBulanLalu = StokKeluar::whereMonth('tanggal_keluar', $bulan - 1)
            ->whereYear('tanggal_keluar', $tahun)
            ->join('bahans', 'stok_keluar.bahan_id', '=', 'bahans.id')
            ->sum(DB::raw('stok_keluar.jumlah * bahans.harga_beli'));
        
        $persenPerubahan = $pengeluaranBulanLalu > 0 
            ? round(($pengeluaranBulanIni - $pengeluaranBulanLalu) / $pengeluaranBulanLalu * 100, 1)
            : 0;
        
        // ========== CHART PENGELUARAN PER BULAN ==========
        $monthlyExpenses = [];
        for ($i = 1; $i <= 12; $i++) {
            $expense = StokKeluar::whereMonth('tanggal_keluar', $i)
                ->whereYear('tanggal_keluar', $tahun)
                ->join('bahans', 'stok_keluar.bahan_id', '=', 'bahans.id')
                ->sum(DB::raw('stok_keluar.jumlah * bahans.harga_beli'));
            
            $monthlyExpenses[] = [
                'month' => $this->getMonthName($i),
                'amount' => $expense
            ];
        }
        
        // ========== CHART PRODUKSI PER BULAN ==========
        $monthlyProduction = [];
        for ($i = 1; $i <= 12; $i++) {
            $production = Produksi::whereMonth('tanggal_produksi', $i)
                ->whereYear('tanggal_produksi', $tahun)
                ->sum('jumlah_porsi');
            
            $monthlyProduction[] = [
                'month' => $this->getMonthName($i),
                'total' => $production
            ];
        }
        
        // ========== TOP 5 BAHAN TERPAKAI ==========
        $topBahan = StokKeluar::select('bahan_id', DB::raw('SUM(jumlah) as total'))
            ->whereYear('tanggal_keluar', $tahun)
            ->with('bahan')
            ->groupBy('bahan_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // ========== AKTIVITAS TERBARU ==========
        $aktivitasTerbaru = StokMasuk::with('bahan')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'masuk',
                    'title' => 'Stok Masuk: ' . ($item->bahan->nama ?? 'Bahan Dihapus'),
                    'jumlah' => $item->jumlah,
                    'satuan' => $item->bahan->satuan ?? '',
                    'time' => $item->created_at->diffForHumans(),
                    'icon' => 'add_circle',
                    'icon_bg' => 'bg-emerald-50',
                    'icon_color' => 'text-emerald-600'
                ];
            });
        
        $stokKeluarTerbaru = StokKeluar::with('bahan')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'keluar',
                    'title' => 'Stok Keluar: ' . ($item->bahan->nama ?? 'Bahan Dihapus'),
                    'jumlah' => $item->jumlah,
                    'satuan' => $item->bahan->satuan ?? '',
                    'time' => $item->created_at->diffForHumans(),
                    'icon' => 'remove_circle',
                    'icon_bg' => 'bg-orange-50',
                    'icon_color' => 'text-orange-500'
                ];
            });
        
        $aktivitasTerbaru = $aktivitasTerbaru->concat($stokKeluarTerbaru)->sortByDesc('time')->take(5);
        
        // ========== TAMBAHAN FITUR ==========
        
        // 1. Total Pemasukan vs Pengeluaran
        $totalPemasukan = Transaksi::where('jenis', 'masuk')
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('jumlah');
        $totalPengeluaran = Transaksi::where('jenis', 'keluar')
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;
        
        // 2. Stok Terendah (Top 5)
        $stokTerendah = Bahan::orderBy('stok', 'asc')->limit(5)->get();
        
        // 3. Menu Terlaris (Top 5)
        $menuTerlaris = Produksi::select('menu_id', DB::raw('SUM(jumlah_porsi) as total'))
            ->whereYear('tanggal_produksi', $tahun)
            ->with('menu')
            ->groupBy('menu_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // 4. Notifikasi Terbaru
        $notifikasiTerbaru = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // 5. Kategori Stok (Donut Chart)
        $kategoriStok = Bahan::select('kategori', DB::raw('SUM(stok) as total'))
            ->whereNotNull('kategori')
            ->groupBy('kategori')
            ->get();
        
        // ========== DATA UNTUK FILTER ==========
        $tahunList = range(date('Y') - 2, date('Y'));
        
        return view('dashboard.index', compact(
            'totalBahan',
            'stokMenipis',
            'stokHabis',
            'pengeluaranBulanIni',
            'persenPerubahan',
            'monthlyExpenses',
            'monthlyProduction',
            'topBahan',
            'aktivitasTerbaru',
            'tahun',
            'bulan',
            'tahunList',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'stokTerendah',
            'menuTerlaris',
            'notifikasiTerbaru',
            'kategoriStok'
        ));
    }
    
    private function getMonthName($month)
    {
        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        return $months[$month];
    }

    public function exportPdf(Request $request)
    {
        // Ambil data yang sama dengan dashboard
        $tahun = $request->tahun ?? date('Y');
        $bulan = $request->bulan ?? date('m');
        
        // Statistik bahan
        $totalBahan = Bahan::count();
        $stokMenipis = Bahan::whereColumn('stok', '<=', 'stok_minimal')->where('stok', '>', 0)->count();
        $stokHabis = Bahan::where('stok', 0)->count();
        
        // Keuangan
        $totalPemasukan = Transaksi::where('jenis', 'masuk')
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('jumlah');
        $totalPengeluaran = Transaksi::where('jenis', 'keluar')
            ->whereYear('tanggal_transaksi', $tahun)
            ->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;
        
        // Chart data
        $monthlyExpenses = [];
        for ($i = 1; $i <= 12; $i++) {
            $expense = StokKeluar::whereMonth('tanggal_keluar', $i)
                ->whereYear('tanggal_keluar', $tahun)
                ->join('bahans', 'stok_keluar.bahan_id', '=', 'bahans.id')
                ->sum(DB::raw('stok_keluar.jumlah * bahans.harga_beli'));
            
            $monthlyExpenses[] = [
                'month' => $this->getMonthName($i),
                'amount' => $expense
            ];
        }
        
        // Top 5 bahan terpakai
        $topBahan = StokKeluar::select('bahan_id', DB::raw('SUM(jumlah) as total'))
            ->whereYear('tanggal_keluar', $tahun)
            ->with('bahan')
            ->groupBy('bahan_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Data untuk filter
        $tahunList = range(date('Y') - 2, date('Y'));
        
        // Nama dapur dari setting
        $dapurName = Setting::get('dapur_name', 'Dapur MBG Bojongsari 02');
        $dapurAddress = Setting::get('dapur_address', 'Bojongsari 02');
        
        $data = compact(
            'totalBahan', 'stokMenipis', 'stokHabis',
            'totalPemasukan', 'totalPengeluaran', 'saldo',
            'monthlyExpenses', 'topBahan', 'tahun', 'bulan',
            'dapurName', 'dapurAddress'
        );
        
        $pdf = Pdf::loadView('exports.dashboard-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('laporan-dashboard-' . date('Y-m-d') . '.pdf');
    }
}