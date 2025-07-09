<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('laporan_aset_rusak', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('unit');
            $table->string('nama_aset');
            $table->string('lokasi_aset');
            $table->string('kondisi_aset');
            $table->date('tanggal');
            $table->enum('status', ['Rusak Total', 'Bisa Diperbaiki']);
            $table->string('foto');
            $table->string('foto_barcode')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_aset_rusak');
    }
};
