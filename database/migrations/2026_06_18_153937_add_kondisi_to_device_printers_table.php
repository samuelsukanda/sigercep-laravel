<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('device_printers', function (Blueprint $table) {
            $table->string('kondisi')->default('Baik')->after('jenis');
        });
    }

    public function down(): void
    {
        Schema::table('device_printers', function (Blueprint $table) {
            $table->dropColumn('kondisi');
        });
    }
};
