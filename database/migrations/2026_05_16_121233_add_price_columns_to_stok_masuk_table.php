<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_masuk', function (Blueprint $table) {
            if (!Schema::hasColumn('stok_masuk', 'harga_satuan')) {
                $table->decimal('harga_satuan', 15, 2)->nullable()->after('jumlah');
            }
            if (!Schema::hasColumn('stok_masuk', 'total_harga')) {
                $table->decimal('total_harga', 15, 2)->nullable()->after('harga_satuan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stok_masuk', function (Blueprint $table) {
            $table->dropColumn(['harga_satuan', 'total_harga']);
        });
    }
};