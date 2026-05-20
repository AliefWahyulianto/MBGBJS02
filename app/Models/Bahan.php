<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasFactory;

    protected $table = 'bahans';
    
    protected $fillable = [
        'nama',
        'kategori',
        'stok',
        'satuan',
        'harga_beli',
        'stok_minimal',
        'keterangan',
        'gambar'
    ];

    // Accessor untuk status stok
    public function getStatusAttribute()
    {
        if ($this->stok <= 0) {
            return ['text' => 'Habis', 'color' => 'red', 'badge' => 'bg-red-100 text-red-700'];
        } elseif ($this->stok <= $this->stok_minimal) {
            return ['text' => 'Menipis', 'color' => 'orange', 'badge' => 'bg-orange-100 text-orange-700'];
        }
        return ['text' => 'Aman', 'color' => 'green', 'badge' => 'bg-emerald-100 text-emerald-700'];
    }

    // ========== RELASI (TAMBAHKAN INI) ==========
    
    // Relasi ke stok masuk
    public function stokMasuk()
    {
        return $this->hasMany(StokMasuk::class, 'bahan_id');
    }

    // Relasi ke stok keluar
    public function stokKeluar()
    {
        return $this->hasMany(StokKeluar::class, 'bahan_id');
    }
    
    // Relasi ke stok opname
    public function stokOpname()
    {
        return $this->hasMany(StokOpname::class, 'bahan_id');
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $term)
    {
        if ($term) {
            $query->where('nama', 'LIKE', "%{$term}%")
                  ->orWhere('kategori', 'LIKE', "%{$term}%");
        }
        return $query;
    }
}