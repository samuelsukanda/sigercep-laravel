<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hardware', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan');
        });
    }

    public function down(): void
    {
        Schema::table('hardware', function (Blueprint $table) {
            $table->string('tanda_tangan')->nullable();
        });
    }
};
