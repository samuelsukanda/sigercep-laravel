<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\TicketApproval;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketApprovalFactory extends Factory
{
    protected $model = TicketApproval::class;

    public function definition()
    {
        $admin = User::factory()->create(); // atau ganti dengan admin tetap
        
        return [
            'ticket_id' => Ticket::factory(),
            'admin_id' => $admin->id,
            'analysis' => $this->faker->paragraph(),
            'action_plan' => $this->faker->sentence(10),
            'estimated_completion' => $this->faker->dateTimeBetween('+1 day', '+7 days'),
            'approval_status' => $this->faker->randomElement(['Approved', 'Rejected', 'Need Clarification']),
            'approval_note' => $this->faker->optional()->sentence(),
            // approved_at & approved_by diisi otomatis oleh model
        ];
    }
}