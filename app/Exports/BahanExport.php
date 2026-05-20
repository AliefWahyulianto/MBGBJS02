<?php

namespace App\Exports;

use App\Models\Bahan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BahanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $bahans;

    public function __construct($bahans = null)
    {
        $this->bahans = $bahans ?: Bahan::all();
    }

    public function collection()
    {
        return $this->bahans;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Bahan',
            'Kategori',
            'Stok',
            'Satuan',
            'Harga Beli',
            'Stok Minimal',
            'Status',
            'Terakhir Update'
        ];
    }

    public function map($bahan): array
    {
        static $no = 0;
        $no++;

        $status = $bahan->status['text'];

        return [
            $no,
            $bahan->nama,
            $bahan->kategori,
            $bahan->stok,
            $bahan->satuan,
            $bahan->harga_beli,
            $bahan->stok_minimal,
            $status,
            $bahan->updated_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}