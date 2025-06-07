<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('komplain_ipsrs', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('unit');
        $table->string('tujuan_unit');
        $table->date('tanggal');
        $table->text('kendala');
        $table->text('foto');
        $table->enum('status', ['Pending', 'On Progress', 'Done'])->nullable();
        $table->string('keterangan')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komplain_ipsrs');
    }
};
