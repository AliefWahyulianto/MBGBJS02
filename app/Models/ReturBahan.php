<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturBahan extends Model
{
    protected $table = 'retur_bahan';
    
    protected $fillable = [
        'kode_retur',
        'bahan_id',
        'jumlah',
        'satuan',
        'jenis',
        'keterangan',
        'tanggal_retur',
        'user_id'
    ];
    
    protected $casts = [
        'tanggal_retur' => 'date',
        'jumlah' => 'decimal:2'
    ];
    
    public static function generateKode()
    {
        $today = date('Ymd');
        $last = self::whereDate('created_at', today())->count() + 1;
        return 'RET-' . $today . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
    
    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function getJenisLabelAttribute()
    {
        return match($this->jenis) {
            'rusak' => '<span class="badge-danger">❌ Rusak</span>',
            'kadaluarsa' => '<span class="badge-warning">⚠️ Kadaluarsa</span>',
            'tercecer' => '<span class="badge-info">📦 Tercecer</span>',
            default => '<span class="badge-secondary">📝 Lainnya</span>',
        };
    }
}