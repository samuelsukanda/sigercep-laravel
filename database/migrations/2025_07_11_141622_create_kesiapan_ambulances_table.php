<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('kesiapan_ambulance', function (Blueprint $table) {
            $table->id();
            $table->string('mobil_ambulance');
            $table->date('tanggal');
            $table->string('perawat');
            $table->string('kondisi_mobil');
            $table->string('kondisi_driver');
            $table->string('oksigen');
            $table->string('regulator_oksigen');
            $table->string('kebersihan');
            $table->string('monitor_pasien');
            $table->string('aed');
            $table->string('suction');
            $table->string('ventilator');
            $table->string('bed_pasien');
            $table->string('linen');
            $table->string('obat');
            $table->string('inverter');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kesiapan_ambulance');
    }
};
