<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('approval_mappings', function (Blueprint $table) {
            $table->unsignedBigInteger('requester_user_id')->nullable()->after('requester_jabatan_id');
            $table->unsignedBigInteger('approver_user_id')->nullable()->after('approver_jabatan_id');

            $table->foreign('requester_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approver_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('approval_mappings', function (Blueprint $table) {
            $table->dropForeign(['requester_user_id']);
            $table->dropForeign(['approver_user_id']);
            $table->dropColumn(['requester_user_id', 'approver_user_id']);
        });
    }
};