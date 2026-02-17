<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ticket_updates', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->enum('status', ['In Progress', 'Closed']);

            $table->text('note')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_updates');
    }
};
