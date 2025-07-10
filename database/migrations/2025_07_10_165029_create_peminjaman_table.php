<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('unit');
            $table->date('tanggal');
            $table->string('barang');
            $table->text('tanda_tangan');
            $table->enum('status', ['Sudah Di Kembalikan', 'Belum Di Kembalikan'])->default('Belum Di Kembalikan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
