<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_mengendap', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')->constrained('produksi')->onDelete('cascade');
            $table->foreignId('bahan_id')->constrained('bahans')->onDelete('cascade');
            $table->decimal('jumlah_kelebihan', 12, 2)->default(0); // kelebihan bahan
            $table->decimal('jumlah_kekurangan', 12, 2)->default(0); // kekurangan bahan
            $table->decimal('jumlah_terpakai', 12, 2)->default(0); // dari mengendap yang dipakai
            $table->string('satuan', 20);
            $table->enum('status', ['menunggu', 'terpakai', 'kadaluarsa'])->default('menunggu');
            $table->date('tanggal_mengendap');
            $table->date('tanggal_terpakai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_mengendap');
    }
};