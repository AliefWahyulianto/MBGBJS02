<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    protected $table = 'stok_masuk';
    
    protected $fillable = [
        'bahan_id', 
        'jumlah',
        'harga_satuan',      // TAMBAHKAN
        'total_harga',       // TAMBAHKAN
        'tanggal_masuk', 
        'catatan'
    ];
    
    protected $casts = [
        'tanggal_masuk' => 'date',
        'jumlah' => 'decimal:2',
        'harga_satuan' => 'decimal:2',  // TAMBAHKAN
        'total_harga' => 'decimal:2'    // TAMBAHKAN
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }
}