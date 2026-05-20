<?php

namespace App\Exports;

use App\Models\StokKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StokKeluarExport implements FromCollection, WithHeadings, WithMapping
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
        $query = StokKeluar::with('bahan');
        
        if ($this->startDate) {
            $query->whereDate('tanggal_keluar', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('tanggal_keluar', '<=', $this->endDate);
        }
        
        return $query->orderBy('tanggal_keluar', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Bahan',
            'Jumlah',
            'Satuan',
            'Keterangan'
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->tanggal_keluar->format('d/m/Y'),
            $item->bahan->nama ?? '-',
            $item->jumlah,
            $item->bahan->satuan ?? '-',
            $item->keterangan ?: '-'
        ];
    }
}