<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 50)->unique();
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->string('kategori', 50);
            $table->string('sumber_tujuan', 200)->nullable();
            $table->decimal('jumlah', 18, 0); // ← 18 digit, 0 desimal (bisa 600.000.000+)
            $table->text('keterangan')->nullable();
            $table->date('tanggal_transaksi');
            $table->string('bukti_gambar')->nullable();
            $table->string('status', 20)->default('verified');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};