<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pemindahan_aset', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('unit_asal');
            $table->string('unit_tujuan');
            $table->string('keperluan');
            $table->date('tanggal');
            $table->string('nama_barang');
            $table->string('foto_barang');
            $table->string('foto_barcode')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemindahan_aset');
    }
};
