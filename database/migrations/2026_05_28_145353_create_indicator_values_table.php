<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('indicator_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id')->constrained('indicators')->onDelete('cascade');
            $table->year('tahun');
            $table->tinyInteger('bulan'); // 1-12
            $table->decimal('nilai', 8, 2)->nullable();
            $table->decimal('numerator', 12, 2)->nullable();
            $table->decimal('denominator', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicator_values');
    }
};
