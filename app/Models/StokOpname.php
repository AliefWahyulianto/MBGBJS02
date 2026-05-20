<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    protected $table = 'stok_opname';
    
    protected $fillable = [
        'bahan_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'keterangan',
        'tanggal_opname',
        'opname_by'
    ];
    
    protected $casts = [
        'tanggal_opname' => 'date',
        'stok_sistem' => 'decimal:2',
        'stok_fisik' => 'decimal:2',
        'selisih' => 'decimal:2'
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'opname_by');
    }
}