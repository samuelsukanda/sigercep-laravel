<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('daftar_risikos', function (Blueprint $table) {
            $table->id();
            $table->string('unit');
            $table->text('risiko');
            $table->string('kode_risiko')->nullable();
            $table->text('sebab')->nullable();
            $table->string('sumber_risiko')->nullable();
            $table->string('c_uc')->nullable();
            $table->text('dampak')->nullable();
            $table->text('pengendalian')->nullable();
            $table->boolean('efektif')->default(false);
            $table->boolean('tidak_efektif')->default(false);
            
            // Analisis Risiko
            $table->decimal('analisis_p', 8, 2)->nullable();
            $table->decimal('analisis_d', 8, 2)->nullable();
            $table->decimal('analisis_bobot', 8, 2)->nullable();
            $table->decimal('analisis_nilai', 8, 2)->nullable();
            $table->string('analisis_tingkat')->nullable();
            
            // Target
            $table->string('target_waktu')->nullable();
            
            // Mitigasi
            $table->decimal('mitigasi_p', 8, 2)->nullable();
            $table->decimal('mitigasi_d', 8, 2)->nullable();
            $table->decimal('mitigasi_bobot', 8, 2)->nullable();
            $table->decimal('mitigasi_nilai', 8, 2)->nullable();
            $table->string('mitigasi_tingkat')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daftar_risikos');
    }
};
