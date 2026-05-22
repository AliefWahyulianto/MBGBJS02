<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    
    protected $fillable = [
        'kode_supplier',
        'nama',
        'kontak',
        'telepon',
        'email',
        'alamat',
        'kategori',
        'rating',
        'total_transaksi',
        'total_pembelian',
        'terakhir_transaksi',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'total_pembelian' => 'decimal:2',
        'terakhir_transaksi' => 'date'
    ];

    // Auto generate kode supplier
    public static function generateKode()
    {
        $last = self::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->kode_supplier, -4)) + 1 : 1;
        return 'SUP-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // Scope filter
    public function scopeFilter($query, $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('nama', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('kode_supplier', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('kontak', 'like', '%' . $filters['search'] . '%');
        }
        if ($filters['kategori'] ?? false) {
            $query->where('kategori', $filters['kategori']);
        }
        if ($filters['status'] ?? false) {
            $query->where('status', $filters['status']);
        }
        return $query;
    }
}