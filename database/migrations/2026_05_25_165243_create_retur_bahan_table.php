<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retur_bahan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_retur', 50)->unique();
            $table->foreignId('bahan_id')->constrained('bahans')->onDelete('cascade');
            $table->decimal('jumlah', 12, 2);
            $table->string('satuan', 20);
            $table->enum('jenis', ['rusak', 'kadaluarsa', 'tercecer', 'lainnya'])->default('rusak');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_retur');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retur_bahan');
    }
};