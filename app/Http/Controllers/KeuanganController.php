<?php
// app/Http/Controllers/KeuanganController.php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exports\KeuanganExport;
use Maatwebsite\Excel\Facades\Excel;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai ?? date('Y-m-01');
        $tanggalSelesai = $request->tanggal_selesai ?? date('Y-m-d');

        $totalMasuk = Transaksi::where('jenis', 'masuk')
            ->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalSelesai])
            ->sum('jumlah');

        $totalKeluar = Transaksi::where('jenis', 'keluar')
            ->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalSelesai])
            ->sum('jumlah');

        $saldo = $totalMasuk - $totalKeluar;

        $chartData = Transaksi::select(
                DB::raw('DATE(tanggal_transaksi) as tanggal'),
                DB::raw('SUM(CASE WHEN jenis = "masuk" THEN jumlah ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN jenis = "keluar" THEN jumlah ELSE 0 END) as keluar')
            )
            ->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalSelesai])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $kategoriKeluar = Transaksi::where('jenis', 'keluar')
            ->whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalSelesai])
            ->select('kategori', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kategori')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $transaksiTerbaru = Transaksi::orderBy('tanggal_transaksi', 'desc')->limit(10)->get();

        $statistik = [
            'total_transaksi' => Transaksi::whereBetween('tanggal_transaksi', [$tanggalMulai, $tanggalSelesai])->count(),
        ];

        return view('keuangan.index', compact(
            'totalMasuk', 'totalKeluar', 'saldo', 
            'chartData', 'kategoriKeluar', 'transaksiTerbaru', 
            'statistik', 'tanggalMulai', 'tanggalSelesai'
        ));
    }

    public function laporan(Request $request)
    {
        $query = Transaksi::orderBy('tanggal_transaksi', 'desc');

        if ($request->filled('search')) {
            $query->where('kode_transaksi', 'like', '%' . $request->search . '%')
                  ->orWhere('sumber_tujuan', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }

        $transaksis = $query->paginate(20)->withQueryString();

        $ringkasan = [
            'total_masuk' => Transaksi::where('jenis', 'masuk')->sum('jumlah'),
            'total_keluar' => Transaksi::where('jenis', 'keluar')->sum('jumlah'),
            'saldo' => Transaksi::where('jenis', 'masuk')->sum('jumlah') - Transaksi::where('jenis', 'keluar')->sum('jumlah'),
        ];

        $kategoris = Transaksi::select('kategori')->distinct()->pluck('kategori');

        return view('keuangan.laporan', compact('transaksis', 'ringkasan', 'kategoris'));
    }

    public function create()
    {
        return view('keuangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:masuk,keluar',
            'kategori' => 'required|string',
            'sumber_tujuan' => 'nullable|string|max:200',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'tanggal_transaksi' => 'required|date',
            'bukti_gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $validated['kode_transaksi'] = Transaksi::generateKode();
        $validated['status'] = 'verified';

        if ($request->hasFile('bukti_gambar')) {
            $path = $request->file('bukti_gambar')->store('bukti_transaksi', 'public');
            $validated['bukti_gambar'] = $path;
        }

        Transaksi::create($validated);

        return redirect()->route('keuangan.index')
            ->with('success', '✅ Transaksi berhasil ditambahkan!');
    }

    public function edit(Transaksi $keuangan)
    {
        return view('keuangan.edit', compact('keuangan'));
    }

    public function update(Request $request, Transaksi $keuangan)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:masuk,keluar',
            'kategori' => 'required|string',
            'sumber_tujuan' => 'nullable|string|max:200',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'tanggal_transaksi' => 'required|date',
            'bukti_gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('bukti_gambar')) {
            if ($keuangan->bukti_gambar) {
                Storage::disk('public')->delete($keuangan->bukti_gambar);
            }
            $path = $request->file('bukti_gambar')->store('bukti_transaksi', 'public');
            $validated['bukti_gambar'] = $path;
        }

        $keuangan->update($validated);

        return redirect()->route('keuangan.laporan')
            ->with('success', '✅ Transaksi berhasil diupdate!');
    }

    public function destroy(Transaksi $keuangan)
    {
        if ($keuangan->bukti_gambar) {
            Storage::disk('public')->delete($keuangan->bukti_gambar);
        }
        $keuangan->delete();

        return redirect()->route('keuangan.laporan')
            ->with('success', '✅ Transaksi berhasil dihapus!');
    }

    public function export()
    {
        return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new KeuanganExport($request->start_date, $request->end_date), 'laporan-keuangan-' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        return LaporanPdf::keuangan($request->start_date, $request->end_date);
    }
}