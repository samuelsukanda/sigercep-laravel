<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('requester_jabatan')->unique();
            $table->string('approver_jabatan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_mappings');
    }
};