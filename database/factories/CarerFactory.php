<?php

namespace Database\Factories;

use App\Models\Carer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarerFactory extends Factory {
    protected $model = Carer::class;
    public function definition() {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'qualifications' => $this->faker->randomElement(['Registered Nurse', 'Healthcare Assistant', 'Care Worker'])
        ];
    }
} 