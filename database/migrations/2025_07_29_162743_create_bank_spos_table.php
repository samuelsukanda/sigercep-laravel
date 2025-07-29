<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('bank_spo', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('file_path');
            $table->string('unit');
            $table->enum('jenis_spo', ['SPO Utama', 'SPO Terkait']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_spo');
    }
};
