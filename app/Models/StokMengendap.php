<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokMengendap extends Model
{
    protected $table = 'stok_mengendap';
    
    protected $fillable = [
        'produksi_id',
        'bahan_id',
        'jumlah_kelebihan',   // ← PASTIKAN NAMA INI
        'jumlah_kekurangan',   // ← PASTIKAN NAMA INI
        'jumlah_terpakai',
        'satuan',
        'status',
        'tanggal_mengendap',
        'tanggal_terpakai',
        'catatan'
    ];
    
    protected $casts = [
        'tanggal_mengendap' => 'date',
        'tanggal_terpakai' => 'date',
        'jumlah_kelebihan' => 'decimal:2',
        'jumlah_kekurangan' => 'decimal:2',
        'jumlah_terpakai' => 'decimal:2'
    ];

    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }
}