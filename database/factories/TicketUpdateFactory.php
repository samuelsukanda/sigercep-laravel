<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketUpdate;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketUpdateFactory extends Factory
{
    protected $model = TicketUpdate::class;

    public function definition()
    {
        return [
            'ticket_id' => Ticket::factory(),
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['In Progress', 'Closed']),
            'note' => $this->faker->optional(0.8)->sentence(), // 80% ada catatan
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}