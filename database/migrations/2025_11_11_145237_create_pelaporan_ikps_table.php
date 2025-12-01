<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pelaporan_ikp', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_rm');
            $table->date('tanggal_lahir');
            $table->string('kelompok_umur');
            $table->string('jenis_kelamin');
            $table->string('penanggung_jawab');
            $table->date('tanggal_masuk_rs');
            $table->string('rincian_kejadian');
            $table->date('tanggal_kejadian');
            $table->time('waktu_kejadian');
            $table->string('insiden');
            $table->string('kronologis_kejadian');
            $table->string('jenis_kejadian');
            $table->string('orang_pelapor');
            $table->string('jenis_insiden');
            $table->string('insiden_pasien');
            $table->string('lokasi_insiden');
            $table->string('jenis_spesialisasi_pasien');
            $table->string('unit_terkait');
            $table->string('akibat_insiden');
            $table->string('tindakan_yang_dilakukan');
            $table->string('tindakan_dilakukan_oleh');
            $table->string('kejadian_serupa');
            $table->string('grading_risiko');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaporan_ikp');
    }
};
