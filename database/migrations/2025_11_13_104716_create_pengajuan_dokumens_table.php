<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pengajuan_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_dokumen');
            $table->string('permintaan_pengajuan');
            $table->string('kategori_pengajuan');
            $table->string('nomor_dokumen');
            $table->string('judul_dokumen');
            $table->string('nomor_revisi');
            $table->string('alasan_pengajuan');
            $table->string('bagian_yang_direvisi')->nullable();
            $table->string('sebelum_revisi')->nullable();
            $table->string('usulan_revisi')->nullable();
            $table->date('tanggal_pengajuan');
            $table->string('diajukan_oleh');
            $table->string('diperiksa_oleh');
            $table->string('disetujui_oleh');
            $table->string('file_pdf');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_dokumen');
    }
};
