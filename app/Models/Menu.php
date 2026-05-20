<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    
    protected $fillable = [
        'nama',
        'kategori',
        'kelompok',
        'harga_jual',
        'kalori',
        'deskripsi',
        'gambar',
        'is_active',
        'status'
    ];

    // Relasi ke resep
    public function resep()
    {
        return $this->hasMany(Resep::class, 'menu_id');
    }

    // Relasi ke bahan melalui resep
    public function bahan()
    {
        return $this->belongsToMany(Bahan::class, 'resep', 'menu_id', 'bahan_id')
                    ->withPivot('jumlah', 'satuan')
                    ->withTimestamps();
    }

    // Hitung HPP (Harga Pokok Produksi)
    public function getHppAttribute()
    {
        $total = 0;
        foreach ($this->resep as $item) {
            $total += $item->bahan->harga_beli * $item->jumlah;
        }
        return $total;
    }

    // Hitung margin keuntungan
    public function getMarginAttribute()
    {
        if ($this->hpp == 0) return 0;
        return round((($this->harga_jual - $this->hpp) / $this->hpp) * 100, 1);
    }

    // Cek ketersediaan berdasarkan stok bahan
    public function getStatusKetersediaanAttribute()
    {
        foreach ($this->resep as $item) {
            if ($item->bahan->stok < $item->jumlah) {
                return 'habis';
            }
            if ($item->bahan->stok <= $item->bahan->stok_minimal) {
                return 'terbatas';
            }
        }
        return 'tersedia';
    }

    // Scope untuk filter
    public function scopeFilter($query, $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('nama', 'like', '%' . $filters['search'] . '%');
        }
        if ($filters['kategori'] ?? false) {
            $query->where('kategori', $filters['kategori']);
        }
        if ($filters['kelompok'] ?? false) {
            $query->where('kelompok', $filters['kelompok']);
        }
        if ($filters['status'] ?? false) {
            $query->where('status', $filters['status']);
        }
        return $query;
    }
}