<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hardware_health_checks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pc');
            $table->string('ip')->nullable();
            $table->string('unit')->nullable();
            $table->string('lantai')->nullable();
            $table->date('checked_at')->nullable();
            $table->json('items')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hardware_health_checks');
    }
};