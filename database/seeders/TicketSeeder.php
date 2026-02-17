<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;

class TicketSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada minimal 1 user
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        Ticket::factory()->count(20)->create([
            'user_id' => $user->id,
        ]);
    }
}