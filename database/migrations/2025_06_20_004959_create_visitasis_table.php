<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('visitasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tim');
            $table->date('tanggal');
            $table->text('kendala');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitasi');
    }
};
