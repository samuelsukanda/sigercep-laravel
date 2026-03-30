<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ticket_approvals', function (Blueprint $table) {
            $table->dateTime('estimated_completion')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('ticket_approvals', function (Blueprint $table) {
            $table->dateTime('estimated_completion')->nullable(false)->change();
        });
    }
};
