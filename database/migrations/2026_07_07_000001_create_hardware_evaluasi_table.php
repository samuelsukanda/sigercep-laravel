<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hardware_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->string('bulan', 7); // Format: YYYY-MM
            $table->integer('nomor');
            $table->text('kendala')->nullable();
            $table->text('rtl')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hardware_evaluasi');
    }
};
