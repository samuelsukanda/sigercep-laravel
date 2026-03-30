<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("
            ALTER TABLE tickets 
            MODIFY status ENUM('Open', 'In Progress', 'Done', 'Closed') 
            DEFAULT 'Open'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE tickets 
            MODIFY status ENUM('Open', 'In Progress', 'Closed') 
            DEFAULT 'Open'
        ");
    }
};