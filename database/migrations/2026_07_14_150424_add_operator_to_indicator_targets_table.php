<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('indicator_targets', function (Blueprint $table) {
            $table->string('operator', 5)->nullable()->after('target_value');
        });
    }

    public function down(): void
    {
        Schema::table('indicator_targets', function (Blueprint $table) {
            $table->dropColumn('operator');
        });
    }
};
