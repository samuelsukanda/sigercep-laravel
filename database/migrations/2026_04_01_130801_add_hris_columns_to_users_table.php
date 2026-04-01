<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik')->nullable()->after('email');
            $table->string('jabatan')->nullable()->after('unit');
            $table->string('status_karyawan')->nullable()->after('jabatan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'jabatan',
                'status_karyawan',
            ]);
        });
    }
};