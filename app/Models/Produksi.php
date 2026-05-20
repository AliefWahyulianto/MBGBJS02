<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'produksi';
    
    protected $fillable = [
        'menu_id',
        'jumlah_porsi',
        'tanggal_produksi',
        'jam_mulai',
        'jam_selesai',
        'status',
        'catatan',
        'produksi_by'
    ];
    
    protected $casts = [
        'tanggal_produksi' => 'date',
        'jam_mulai' => 'datetime',
        'jam_selesai' => 'datetime'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function detail()
    {
        return $this->hasMany(ProduksiDetail::class, 'produksi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'produksi_by');
    }
}