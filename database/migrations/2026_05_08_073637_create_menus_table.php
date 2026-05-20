<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('kategori', 50)->default('Makanan'); // Makanan, Snack, Minuman
            $table->string('kelompok', 50)->nullable(); // Karbohidrat, Protein Hewani, Protein Nabati, Sayur, Buah
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->integer('kalori')->nullable(); // Kalori per porsi
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('status', 20)->default('tersedia'); // tersedia, terbatas, habis
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};