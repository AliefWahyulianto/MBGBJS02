<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_mengendap', function (Blueprint $table) {
            // Hapus kolom lama jika ada
            if (Schema::hasColumn('stok_mengendap', 'jumlah_kekurangan')) {
                $table->dropColumn('jumlah_kekurangan');
            }
            if (!Schema::hasColumn('stok_mengendap', 'jumlah_diambil')) {
                $table->decimal('jumlah_diambil', 12, 2)->default(0)->after('bahan_id');
            }
            if (!Schema::hasColumn('stok_mengendap', 'jumlah_sisa')) {
                $table->decimal('jumlah_sisa', 12, 2)->default(0)->after('jumlah_terpakai');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stok_mengendap', function (Blueprint $table) {
            $table->decimal('jumlah_kekurangan', 12, 2)->default(0);
            $table->dropColumn(['jumlah_diambil', 'jumlah_sisa']);
        });
    }
};