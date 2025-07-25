<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('kesehatan_lingkungan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('unit');
            $table->date('tanggal');
            $table->string('lokasi_masalah');
            $table->string('jenis_hama');
            $table->text('dokumentasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kesehatan_lingkungan');
    }
};
