<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->string('no_urut', 10);
            $table->string('pj', 100);
            $table->text('nama_indikator');
            $table->string('jenis_indikator', 50); // INM, IMPRS, IMP-UNIT, IMP-VENDOR, IMP-KOMITE
            $table->string('unit_terkait', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('indicators');
    }
};
