<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('kecelakaan_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('unit');
            $table->string('no_hp', 20);
            $table->string('jam');
            $table->date('tanggal');
            $table->string('jenis_kecelakaan');
            $table->string('lokasi_kecelakaan');
            $table->string('saksi')->nullable();
            $table->string('kegiatan');
            $table->string('riwayat');
            $table->string('penyebab');
            $table->string('bahan')->nullable();
            $table->string('cedera');
            $table->string('pengobatan');
            $table->string('pengobatan2');
            $table->string('pelaksana');
            $table->string('tanda_tangan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kecelakaan_kerja');
    }
};
