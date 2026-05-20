<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('no_po', 100)->unique();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->date('tanggal_order');
            $table->date('tanggal_diperlukan')->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->string('status', 30)->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};