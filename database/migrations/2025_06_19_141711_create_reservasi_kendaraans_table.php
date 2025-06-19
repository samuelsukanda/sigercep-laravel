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
        Schema::create('reservasi_kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('unit');
            $table->string('tempat_tujuan');
            $table->string('keperluan');
            $table->time('jam_berangkat');
            $table->time('jam_pulang');
            $table->date('tanggal');
            $table->string('jenis_kendaraan');
            $table->string('jumlah_penumpang');
            $table->string('waktu_tempuh');
            $table->string('jarak_tempuh');
            $table->string('jenis_layanan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasi_kendaraans');
    }
};
