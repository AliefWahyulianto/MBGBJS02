<?php

namespace App\Exports;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Bahan;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use App\Models\Transaksi;

class LaporanPdf
{
    public static function bahan()
    {
        $bahans = Bahan::all();
        $pdf = Pdf::loadView('exports.bahan-pdf', compact('bahans'));
        return $pdf->download('laporan-bahan-' . date('Y-m-d') . '.pdf');
    }

    public static function stokMasuk($startDate = null, $endDate = null)
    {
        $query = StokMasuk::with('bahan');
        if ($startDate) $query->whereDate('tanggal_masuk', '>=', $startDate);
        if ($endDate) $query->whereDate('tanggal_masuk', '<=', $endDate);
        $stokMasuk = $query->orderBy('tanggal_masuk', 'desc')->get();
        
        $pdf = Pdf::loadView('exports.stok-masuk-pdf', compact('stokMasuk', 'startDate', 'endDate'));
        return $pdf->download('laporan-stok-masuk-' . date('Y-m-d') . '.pdf');
    }

    public static function stokKeluar($startDate = null, $endDate = null)
    {
        $query = StokKeluar::with('bahan');
        if ($startDate) $query->whereDate('tanggal_keluar', '>=', $startDate);
        if ($endDate) $query->whereDate('tanggal_keluar', '<=', $endDate);
        $stokKeluar = $query->orderBy('tanggal_keluar', 'desc')->get();
        
        $pdf = Pdf::loadView('exports.stok-keluar-pdf', compact('stokKeluar', 'startDate', 'endDate'));
        return $pdf->download('laporan-stok-keluar-' . date('Y-m-d') . '.pdf');
    }

    public static function keuangan($startDate = null, $endDate = null)
    {
        $query = Transaksi::query();
        if ($startDate) $query->whereDate('tanggal_transaksi', '>=', $startDate);
        if ($endDate) $query->whereDate('tanggal_transaksi', '<=', $endDate);
        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')->get();
        
        $totalMasuk = $transaksis->where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = $transaksis->where('jenis', 'keluar')->sum('jumlah');
        $saldo = $totalMasuk - $totalKeluar;
        
        $pdf = Pdf::loadView('exports.keuangan-pdf', compact('transaksis', 'totalMasuk', 'totalKeluar', 'saldo', 'startDate', 'endDate'));
        return $pdf->download('laporan-keuangan-' . date('Y-m-d') . '.pdf');
    }
}