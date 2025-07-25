<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('toner', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('unit');
            $table->string('toner');
            $table->string('jumlah');
            $table->date('tanggal');
            $table->text('tanda_tangan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('toner');
    }
};
