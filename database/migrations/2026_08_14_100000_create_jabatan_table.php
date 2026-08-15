<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jabatan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // id jabatan dari HRIS
            $table->string('nama');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedInteger('level_approve')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jabatan');
    }
};