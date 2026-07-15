<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('daftar_risikos', 'mitigasi_p')) {
            Schema::table('daftar_risikos', function (Blueprint $table) {
                // Rename existing mitigasi columns to tw1
                $table->renameColumn('mitigasi_p', 'mitigasi_tw1_p');
                $table->renameColumn('mitigasi_d', 'mitigasi_tw1_d');
                $table->renameColumn('mitigasi_bobot', 'mitigasi_tw1_bobot');
                $table->renameColumn('mitigasi_nilai', 'mitigasi_tw1_nilai');
                $table->renameColumn('mitigasi_tingkat', 'mitigasi_tw1_tingkat');
            });
        }

        Schema::table('daftar_risikos', function (Blueprint $table) {
            // Add TW 2
            if (!Schema::hasColumn('daftar_risikos', 'mitigasi_tw2_p')) {
                $table->decimal('mitigasi_tw2_p', 8, 2)->nullable()->after('mitigasi_tw1_tingkat'); 
                $table->decimal('mitigasi_tw2_d', 8, 2)->nullable();
                $table->decimal('mitigasi_tw2_bobot', 8, 2)->nullable();
                $table->decimal('mitigasi_tw2_nilai', 8, 2)->nullable();
                $table->string('mitigasi_tw2_tingkat')->nullable();
                
                // Add TW 3
                $table->decimal('mitigasi_tw3_p', 8, 2)->nullable();
                $table->decimal('mitigasi_tw3_d', 8, 2)->nullable();
                $table->decimal('mitigasi_tw3_bobot', 8, 2)->nullable();
                $table->decimal('mitigasi_tw3_nilai', 8, 2)->nullable();
                $table->string('mitigasi_tw3_tingkat')->nullable();

                // Add TW 4
                $table->decimal('mitigasi_tw4_p', 8, 2)->nullable();
                $table->decimal('mitigasi_tw4_d', 8, 2)->nullable();
                $table->decimal('mitigasi_tw4_bobot', 8, 2)->nullable();
                $table->decimal('mitigasi_tw4_nilai', 8, 2)->nullable();
                $table->string('mitigasi_tw4_tingkat')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daftar_risikos', function (Blueprint $table) {
            $table->dropColumn([
                'mitigasi_tw2_p', 'mitigasi_tw2_d', 'mitigasi_tw2_bobot', 'mitigasi_tw2_nilai', 'mitigasi_tw2_tingkat',
                'mitigasi_tw3_p', 'mitigasi_tw3_d', 'mitigasi_tw3_bobot', 'mitigasi_tw3_nilai', 'mitigasi_tw3_tingkat',
                'mitigasi_tw4_p', 'mitigasi_tw4_d', 'mitigasi_tw4_bobot', 'mitigasi_tw4_nilai', 'mitigasi_tw4_tingkat',
            ]);

            $table->renameColumn('mitigasi_tw1_p', 'mitigasi_p');
            $table->renameColumn('mitigasi_tw1_d', 'mitigasi_d');
            $table->renameColumn('mitigasi_tw1_bobot', 'mitigasi_bobot');
            $table->renameColumn('mitigasi_tw1_nilai', 'mitigasi_nilai');
            $table->renameColumn('mitigasi_tw1_tingkat', 'mitigasi_tingkat');
        });
    }
};
