<?php
// database/migrations/2024_01_01_000001_create_bahans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kategori')->default('Lainnya');
            $table->decimal('stok', 10, 2)->default(0);
            $table->string('satuan')->default('kg');
            $table->decimal('harga_beli', 12, 2)->default(0);
            $table->decimal('stok_minimal', 10, 2)->default(1);
            $table->text('keterangan')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahans');
    }
};