<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->enum('approval_1_status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu')->after('pic_request');
            $table->string('approval_1_by')->nullable();
            $table->timestamp('approval_1_at')->nullable();
            $table->text('approval_1_note')->nullable();
            $table->enum('approval_2_status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->string('approval_2_by')->nullable();
            $table->timestamp('approval_2_at')->nullable();
            $table->text('approval_2_note')->nullable();
        });

        // Data lama masih dummy, dihapus sesuai permintaan
        \DB::table('change_requests')->delete();
    }

    public function down(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->dropColumn([
                'approval_1_status', 'approval_1_by', 'approval_1_at', 'approval_1_note',
                'approval_2_status', 'approval_2_by', 'approval_2_at', 'approval_2_note',
            ]);
        });
    }
};