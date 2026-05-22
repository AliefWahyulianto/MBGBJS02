<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    protected $table = 'stok_masuk';
    
    protected $fillable = [
        'bahan_id',
        'supplier_id',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'tanggal_masuk',
        'no_invoice',
        'catatan',
        'status'
    ];
    
    protected $casts = [
        'tanggal_masuk' => 'date',
        'jumlah' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2'
    ];

    // Relasi ke bahan
    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }

    // Relasi ke supplier (TAMBAHKAN INI)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}