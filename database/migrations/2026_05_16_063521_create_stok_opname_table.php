<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_opname', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_id')->constrained('bahans')->onDelete('cascade');
            $table->decimal('stok_sistem', 12, 2); // stok di sistem sebelum opname
            $table->decimal('stok_fisik', 12, 2);  // stok hasil pengecekan fisik
            $table->decimal('selisih', 12, 2);     // selisih (fisik - sistem) bisa plus/minus
            $table->text('keterangan')->nullable(); // alasan selisih (susut, rusak, tercecer, dll)
            $table->date('tanggal_opname');
            $table->foreignId('opname_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_opname');
    }
};