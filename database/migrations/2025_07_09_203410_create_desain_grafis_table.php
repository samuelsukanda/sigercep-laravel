<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('desain_grafis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('unit');
            $table->string('keperluan');
            $table->date('tanggal');
            $table->string('desain');
            $table->enum('status', ['Pending', 'On Progress', 'Done'])->nullable();
            $table->integer('panjang')->nullable();
            $table->integer('tinggi')->nullable();
            $table->string('satuan')->nullable();
            $table->integer('menit')->nullable();
            $table->integer('detik')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desain_grafis');
    }
};
