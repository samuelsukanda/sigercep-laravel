<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('surat_keputusan', function (Blueprint $table) {
            $table->id();
            $table->string('file_pdf');
            $table->string('file_path');
            $table->string('unit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keputusan');
    }
};
