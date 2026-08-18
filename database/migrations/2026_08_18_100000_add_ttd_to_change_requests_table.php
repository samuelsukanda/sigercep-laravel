<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->longText('approval_1_ttd')->nullable()->after('approval_1_note');
            $table->longText('approval_2_ttd')->nullable()->after('approval_2_note');
        });
    }

    public function down(): void
    {
        Schema::table('change_requests', function (Blueprint $table) {
            $table->dropColumn(['approval_1_ttd', 'approval_2_ttd']);
        });
    }
};
