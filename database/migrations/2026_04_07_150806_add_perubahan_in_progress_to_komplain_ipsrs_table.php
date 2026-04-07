<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('komplain_ipsrs', function (Blueprint $table) {
            $table->enum('status_temp', ['Pending', 'In Progress', 'Done'])->nullable()->after('status');
        });

        DB::table('komplain_ipsrs')->update([
            'status_temp' => DB::raw("CASE 
                WHEN status = 'On Progress' THEN 'In Progress' 
                WHEN status IS NULL OR status = '' THEN 'Pending'
                ELSE status 
            END")
        ]);

        Schema::table('komplain_ipsrs', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('komplain_ipsrs', function (Blueprint $table) {
            $table->renameColumn('status_temp', 'status');
        });

        DB::statement("ALTER TABLE komplain_ipsrs MODIFY COLUMN status ENUM('Pending', 'In Progress', 'Done') NOT NULL DEFAULT 'Pending'");
    }

    public function down(): void
    {
        Schema::table('komplain_ipsrs', function (Blueprint $table) {
            $table->enum('status_old', ['Pending', 'On Progress', 'Done'])->nullable()->after('status');
        });

        DB::table('komplain_ipsrs')->update([
            'status_old' => DB::raw("CASE 
                WHEN status = 'In Progress' THEN 'On Progress' 
                ELSE status 
            END")
        ]);

        Schema::table('komplain_ipsrs', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('komplain_ipsrs', function (Blueprint $table) {
            $table->renameColumn('status_old', 'status');
        });
    }
};
