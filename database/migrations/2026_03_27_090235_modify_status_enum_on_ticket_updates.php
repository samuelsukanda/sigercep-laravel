<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("
            ALTER TABLE ticket_updates 
            MODIFY status ENUM('In Progress', 'Done', 'Closed') 
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE ticket_updates 
            MODIFY status ENUM('In Progress', 'Closed') 
        ");
    }
};