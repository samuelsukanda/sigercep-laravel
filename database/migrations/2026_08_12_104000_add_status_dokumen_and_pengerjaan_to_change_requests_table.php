<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->enum('status_dokumen', ['Terpenuhi', 'Dalam Proses', 'Tidak Ada'])->default('Dalam Proses')->after('file_path');
            $table->enum('status_pengerjaan', ['Open', 'In Progress', 'Pending', 'QC', 'Done', 'Closed'])->default('Open')->after('status_dokumen');
        });
    }

    public function down(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->dropColumn(['status_dokumen', 'status_pengerjaan']);
        });
    }
};
