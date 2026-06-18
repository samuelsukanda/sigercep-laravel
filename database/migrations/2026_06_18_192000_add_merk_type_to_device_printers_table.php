<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_printers', function (Blueprint $table) {
            $table->string('merk_type')->nullable()->after('jenis');
        });
    }

    public function down(): void
    {
        Schema::table('device_printers', function (Blueprint $table) {
            $table->dropColumn('merk_type');
        });
    }
};
