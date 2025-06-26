<?php

namespace Database\Factories;

use App\Models\Shift;
use App\Models\Carer;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory {
    protected $model = Shift::class;
    public function definition() {
        $start = $this->faker->dateTimeBetween('+1 days', '+2 days');
        $end = (clone $start)->modify('+2 hours');
        return [
            'carer_id' => Carer::factory(),
            'client_id' => Client::factory(),
            'start_time' => $start->format('Y-m-d H:i:s'),
            'end_time' => $end->format('Y-m-d H:i:s'),
            'notes' => $this->faker->sentence
        ];
    }
} 