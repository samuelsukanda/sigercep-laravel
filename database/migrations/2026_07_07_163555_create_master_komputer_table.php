<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_komputer', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pc');
            $table->string('jenis_pc')->nullable();
            $table->string('unit')->nullable();
            $table->string('lantai')->nullable();
            $table->string('ip')->unique();
            $table->string('ram')->nullable();
            $table->string('cpu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_komputer');
    }
};
