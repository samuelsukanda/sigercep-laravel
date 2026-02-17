<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('ticket_approvals', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->restrictOnDelete()
                ->cascadeOnUpdate();


            $table->foreignId('admin_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->text('analysis');
            $table->text('action_plan');
            $table->datetime('estimated_completion');

            $table->enum('approval_status', [
                'Approved',
                'Rejected',
                'Need Clarification',
                'Pending'
            ])->default('Pending');;

            $table->text('approval_note')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('approved_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->unique('ticket_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_approvals');
    }
};
