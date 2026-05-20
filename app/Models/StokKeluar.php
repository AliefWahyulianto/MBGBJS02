<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    protected $table = 'stok_keluar';
    
    protected $fillable = [
        'bahan_id', 
        'jumlah', 
        'tanggal_keluar', 
        'keterangan',
        'is_for_produksi',   // TAMBAHKAN
        'produksi_id'        // TAMBAHKAN
    ];
    
    protected $casts = [
        'tanggal_keluar' => 'date',
        'jumlah' => 'decimal:2'
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }
    
    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }
}