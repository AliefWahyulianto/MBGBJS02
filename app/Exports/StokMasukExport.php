<?php

namespace App\Exports;

use App\Models\StokMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StokMasukExport implements FromCollection, WithHeadings, WithMapping
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
        $query = StokMasuk::with('bahan');
        
        if ($this->startDate) {
            $query->whereDate('tanggal_masuk', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('tanggal_masuk', '<=', $this->endDate);
        }
        
        return $query->orderBy('tanggal_masuk', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Bahan',
            'Jumlah',
            'Satuan',
            'Catatan',
            'Input By'
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $item->tanggal_masuk->format('d/m/Y'),
            $item->bahan->nama ?? '-',
            $item->jumlah,
            $item->bahan->satuan ?? '-',
            $item->catatan ?: '-',
            $item->created_at->format('d/m/Y H:i')
        ];
    }
}