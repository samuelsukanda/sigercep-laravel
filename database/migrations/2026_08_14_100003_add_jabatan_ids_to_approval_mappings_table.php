<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('approval_mappings', function (Blueprint $table) {
            $table->unsignedBigInteger('requester_jabatan_id')->nullable()->after('requester_jabatan');
            $table->unsignedBigInteger('approver_jabatan_id')->nullable()->after('approver_jabatan');
        });
    }

    public function down(): void
    {
        Schema::table('approval_mappings', function (Blueprint $table) {
            $table->dropColumn(['requester_jabatan_id', 'approver_jabatan_id']);
        });
    }
};