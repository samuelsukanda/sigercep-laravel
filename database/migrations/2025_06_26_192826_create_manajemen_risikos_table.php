<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('manajemen_risiko', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('unit');
            $table->date('tanggal');
            $table->text('uraian');
            $table->string('dampak');
            $table->string('kemungkinan');
            $table->integer('nilai');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manajemen_risikos');
    }
};
