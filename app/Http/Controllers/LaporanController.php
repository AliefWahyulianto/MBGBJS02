<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use App\Models\StokOpname;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\BahanExport;
use App\Exports\StokMasukExport;
use App\Exports\StokKeluarExport;
use App\Exports\KeuanganExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // ========== FILTER TANGGAL ==========
        $range = $request->range ?? 'bulan_ini';
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;
        
        if ($range == '7_hari') {
            $tanggalMulai = date('Y-m-d', strtotime('-7 days'));
            $tanggalSelesai = date('Y-m-d');
        } elseif ($range == '30_hari') {
            $tanggalMulai = date('Y-m-d', strtotime('-30 days'));
            $tanggalSelesai = date('Y-m-d');
        } elseif ($range == 'bulan_ini') {
            $tanggalMulai = date('Y-m-01');
            $tanggalSelesai = date('Y-m-d');
        } elseif ($range == 'custom' && $tanggalMulai && $tanggalSelesai) {
            // gunakan tanggal dari input
        } else {
            $tanggalMulai = date('Y-m-01');
            $tanggalSelesai = date('Y-m-d');
        }
        
        // ========== TOTAL PENGELUARAN BULAN INI ==========
        $totalPengeluaran = StokKeluar::whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalSelesai])
            ->join('bahans', 'stok_keluar.bahan_id', '=', 'bahans.id')
            ->sum(DB::raw('stok_keluar.jumlah * bahans.harga_beli'));
        
        $totalPengeluaranLalu = StokKeluar::whereBetween('tanggal_keluar', [date('Y-m-d', strtotime('-1 month', strtotime($tanggalMulai))), date('Y-m-d', strtotime('-1 month', strtotime($tanggalSelesai)))])
            ->join('bahans', 'stok_keluar.bahan_id', '=', 'bahans.id')
            ->sum(DB::raw('stok_keluar.jumlah * bahans.harga_beli'));
        
        $persenPerubahan = $totalPengeluaranLalu > 0 
            ? round(($totalPengeluaran - $totalPengeluaranLalu) / $totalPengeluaranLalu * 100, 1)
            : 0;
        
        // ========== PALING BANYAK DIGUNAKAN ==========
        $palingBanyakDigunakan = StokKeluar::select('bahan_id', DB::raw('SUM(jumlah) as total'))
            ->whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalSelesai])
            ->with('bahan')
            ->groupBy('bahan_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // ========== PERLU RE-STOCK ==========
        $perluRestock = Bahan::whereColumn('stok', '<=', 'stok_minimal')->where('stok', '>', 0)->count();
        $stokHabis = Bahan::where('stok', 0)->count();
        
        // ========== TREN PENGGUNAAN (Chart) ==========
        $trenData = [];
        for ($i = 1; $i <= 4; $i++) {
            $start = date('Y-m-d', strtotime("-$i week", strtotime($tanggalSelesai)));
            $end = date('Y-m-d', strtotime("-$i week +6 days", strtotime($tanggalSelesai)));
            
            $masuk = StokMasuk::whereBetween('tanggal_masuk', [$start, $end])->sum('jumlah');
            $keluar = StokKeluar::whereBetween('tanggal_keluar', [$start, $end])->sum('jumlah');
            
            $trenData[] = ['masuk' => $masuk, 'keluar' => $keluar];
        }
        $trenData = array_reverse($trenData);
        
        // ========== AKTIVITAS TERAKHIR ==========
        $aktivitasTerakhir = collect();
        
        // Stok masuk terbaru
        $stokMasukTerbaru = StokMasuk::with('bahan')->orderBy('tanggal_masuk', 'desc')->limit(3)->get();
        foreach ($stokMasukTerbaru as $item) {
            $aktivitasTerakhir->push([
                'type' => 'masuk',
                'title' => 'Stok Masuk: ' . ($item->bahan->nama ?? 'Bahan Dihapus'),
                'subtitle' => 'Jumlah: ' . number_format($item->jumlah, 2) . ' ' . ($item->bahan->satuan ?? ''),
                'time' => $item->tanggal_masuk->diffForHumans(),
                'icon' => 'add_circle',
                'icon_bg' => 'bg-emerald-50',
                'icon_color' => 'text-emerald-600'
            ]);
        }
        
        // Stok keluar terbaru
        $stokKeluarTerbaru = StokKeluar::with('bahan')->orderBy('tanggal_keluar', 'desc')->limit(3)->get();
        foreach ($stokKeluarTerbaru as $item) {
            $aktivitasTerakhir->push([
                'type' => 'keluar',
                'title' => 'Stok Keluar: ' . ($item->bahan->nama ?? 'Bahan Dihapus'),
                'subtitle' => 'Jumlah: ' . number_format($item->jumlah, 2) . ' ' . ($item->bahan->satuan ?? ''),
                'time' => $item->tanggal_keluar->diffForHumans(),
                'icon' => 'remove_circle',
                'icon_bg' => 'bg-orange-50',
                'icon_color' => 'text-orange-500'
            ]);
        }
        
        $aktivitasTerakhir = $aktivitasTerakhir->sortByDesc('time')->take(5);
        
        // ========== RINCIAN PENGGUNAAN BAHAN ==========
        $rincianBahan = Bahan::withSum(['stokKeluar as total_keluar' => function($q) use ($tanggalMulai, $tanggalSelesai) {
            $q->whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalSelesai]);
        }], 'jumlah')
        ->get()
        ->filter(function($item) {
            return $item->total_keluar > 0;
        })
        ->sortByDesc('total_keluar')
        ->take(10);
        
        // ========== KATEGORI UNTUK FILTER ==========
        $kategoris = Bahan::select('kategori')->distinct()->pluck('kategori');
        
        return view('laporan.index', compact(
            'totalPengeluaran',
            'persenPerubahan',
            'palingBanyakDigunakan',
            'perluRestock',
            'stokHabis',
            'trenData',
            'aktivitasTerakhir',
            'rincianBahan',
            'kategoris',
            'range',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }
    
    // Method untuk filter via AJAX
    public function filter(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;
        $kategori = $request->kategori;
        $status = $request->status;
        
        // Filter bahan berdasarkan kategori dan status
        $query = Bahan::query();
        
        if ($kategori && $kategori != 'semua') {
            $query->where('kategori', $kategori);
        }
        
        if ($status) {
            if ($status == 'aman') {
                $query->whereColumn('stok', '>', 'stok_minimal');
            } elseif ($status == 'menipis') {
                $query->whereColumn('stok', '<=', 'stok_minimal')->where('stok', '>', 0);
            } elseif ($status == 'habis') {
                $query->where('stok', 0);
            }
        }
        
        $bahans = $query->withSum(['stokKeluar as total_keluar' => function($q) use ($tanggalMulai, $tanggalSelesai) {
            $q->whereBetween('tanggal_keluar', [$tanggalMulai, $tanggalSelesai]);
        }], 'jumlah')->get();
        
        return response()->json($bahans);
    }

    public function laporanStok()
    {
        $bahans = Bahan::withSum('stokMasuk', 'jumlah')
            ->withSum('stokKeluar', 'jumlah')
            ->orderBy('nama')
            ->get();
        
        return view('laporan.stok', compact('bahans'));
    }

    public function exportExcel()
    {
        // Export gabungan atau sesuai kebutuhan
        return Excel::download(new BahanExport, 'laporan-lengkap-' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf()
    {
        return LaporanPdf::bahan();
    }
}