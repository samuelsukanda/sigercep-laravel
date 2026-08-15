<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('jabatan');
    }

    public function down(): void
    {
        Schema::create('jabatan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('nama')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->integer('level_approve')->nullable();
            $table->timestamps();
        });
    }
};