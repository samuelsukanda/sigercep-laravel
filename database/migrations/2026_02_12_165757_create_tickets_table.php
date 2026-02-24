<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ticket_number', 50)->unique();
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->string('unit_name');
            $table->enum('category', ['Hardware', 'Printer', 'Jaringan', 'Software', 'SIMRS']);
            $table->text('description');
            $table->enum('urgency', ['Low', 'Medium', 'High', 'Critical']);
            $table->string('attachment')->nullable();
            $table->enum('status', ['Open', 'In Progress', 'Closed'])->default('Open');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
