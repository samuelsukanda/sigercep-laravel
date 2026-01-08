<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_dokumen', function (Blueprint $table) {
            $table->renameColumn('file_pdf', 'file_spo');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_dokumen', function (Blueprint $table) {
            $table->renameColumn('file_spo', 'file_pdf');
        });
    }
};
