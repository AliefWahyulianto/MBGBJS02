<?php
// app/Models/Transaksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    
    protected $fillable = [
        'kode_transaksi',
        'jenis',
        'kategori',
        'sumber_tujuan',
        'jumlah',
        'keterangan',
        'tanggal_transaksi',
        'bukti_gambar',
        'status'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'jumlah' => 'decimal:2'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'input_by');
    }

    // Scope filter
    public function scopeFilter($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('kode_transaksi', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('sumber_tujuan', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('keterangan', 'like', '%' . $filters['search'] . '%');
        }

        if ($filters['jenis'] ?? false) {
            $query->where('jenis', $filters['jenis']);
        }

        if ($filters['kategori'] ?? false) {
            $query->where('kategori', $filters['kategori']);
        }

        if ($filters['tanggal_mulai'] ?? false) {
            $query->whereDate('tanggal_transaksi', '>=', $filters['tanggal_mulai']);
        }

        if ($filters['tanggal_selesai'] ?? false) {
            $query->whereDate('tanggal_transaksi', '<=', $filters['tanggal_selesai']);
        }

        return $query;
    }

    // Helper: Generate kode transaksi otomatis
    public static function generateKode()
    {
        $today = date('Ymd');
        $last = self::whereDate('created_at', today())->count() + 1;
        return 'TRX-' . $today . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}