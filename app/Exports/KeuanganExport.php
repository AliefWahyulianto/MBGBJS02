<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KeuanganExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $query = Transaksi::query();
        
        if ($this->startDate) {
            $query->whereDate('tanggal_transaksi', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('tanggal_transaksi', '<=', $this->endDate);
        }
        
        return $query->orderBy('tanggal_transaksi', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Transaksi',
            'Tanggal',
            'Jenis',
            'Kategori',
            'Sumber/Tujuan',
            'Jumlah',
            'Keterangan',
            'Status'
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->kode_transaksi,
            $item->tanggal_transaksi->format('d/m/Y'),
            $item->jenis == 'masuk' ? 'Pemasukan' : 'Pengeluaran',
            $item->kategori,
            $item->sumber_tujuan ?: '-',
            number_format($item->jumlah, 0, ',', '.'),
            $item->keterangan ?: '-',
            $item->status
        ];
    }
}