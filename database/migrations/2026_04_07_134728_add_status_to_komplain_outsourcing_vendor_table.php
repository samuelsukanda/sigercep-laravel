<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('komplain_outsourcing_vendor', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'On Progress', 'Done'])->after('foto')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('komplain_outsourcing_vendor', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
