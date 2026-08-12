<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('change_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // relasi ke user pembuat
            $table->string('nama');
            $table->string('unit');
            $table->text('deskripsi');
            $table->string('file_pendukung')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['Pending', 'On Progress', 'Done', 'Cancelled'])->default('Pending');
            $table->string('no_tiket')->nullable();
            $table->string('pic_request')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('change_requests');
    }
};
