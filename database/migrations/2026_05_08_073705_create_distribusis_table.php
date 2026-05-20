<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribusis', function (Blueprint $table) {
            $table->id();
            $table->string('tujuan', 200);
            $table->string('alamat', 500)->nullable();
            $table->string('kontak_penerima', 100)->nullable();
            $table->integer('jumlah_porsi')->default(0);
            $table->date('tanggal_kirim');
            $table->time('jam_berangkat')->nullable();
            $table->time('jam_sampai')->nullable();
            $table->string('status', 30)->default('scheduled');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribusis');
    }
};