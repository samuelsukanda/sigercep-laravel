<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        // ticket_number akan diisi otomatis oleh model (booted)
        return [
            'user_id' => User::factory(),
            'unit' => $this->faker->randomElement(['IT Support', 'Rawat Inap', 'Radiologi', 'Laboratorium']),
            'category' => $this->faker->randomElement(['Hardware', 'Printer', 'Jaringan', 'Software', 'SIMRS']),
            'description' => $this->faker->sentence(10),
            'urgency' => $this->faker->randomElement(['Low', 'Medium', 'High', 'Critical']),
            'attachment' => $this->faker->optional()->filePath(),
            'status' => $this->faker->randomElement(['Open', 'In Progress', 'Closed']),
        ];
    }
}