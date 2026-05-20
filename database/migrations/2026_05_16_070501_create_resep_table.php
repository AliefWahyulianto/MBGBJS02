<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resep', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->foreignId('bahan_id')->constrained('bahans')->onDelete('cascade');
            $table->decimal('jumlah', 12, 2); // jumlah bahan per porsi
            $table->string('satuan', 20);
            $table->timestamps();
            
            $table->unique(['menu_id', 'bahan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep');
    }
};