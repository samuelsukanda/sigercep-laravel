<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kesehatan_lingkungan', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'In Progress', 'Done'])->after('dokumentasi')->nullable();
            $table->string('keterangan')->after('status')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('kesehatan_lingkungan', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
