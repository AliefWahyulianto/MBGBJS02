<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'group')) {
                $table->string('group')->default('general')->after('key');
            }
            if (!Schema::hasColumn('settings', 'type')) {
                $table->string('type')->default('text')->after('group');
            }
            if (!Schema::hasColumn('settings', 'description')) {
                $table->text('description')->nullable()->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['group', 'type', 'description']);
        });
    }
};