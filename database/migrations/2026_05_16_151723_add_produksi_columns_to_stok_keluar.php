<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_keluar', function (Blueprint $table) {
            if (!Schema::hasColumn('stok_keluar', 'is_for_produksi')) {
                $table->boolean('is_for_produksi')->default(false)->after('keterangan');
            }
            if (!Schema::hasColumn('stok_keluar', 'produksi_id')) {
                $table->foreignId('produksi_id')->nullable()->constrained('produksi')->onDelete('set null')->after('is_for_produksi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stok_keluar', function (Blueprint $table) {
            $table->dropColumn(['is_for_produksi', 'produksi_id']);
        });
    }
};