<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('mutu', function (Blueprint $table) {
            $table->id();
            $table->string('indikator');
            $table->string('periode');
            $table->string('unit');
            $table->string('pj_data');
            $table->string('numerator');
            $table->string('penumerator');
            $table->string('capaian');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutu');
    }
};
