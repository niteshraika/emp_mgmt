<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'department_id' => Department::inRandomOrder()->first()->id ?? Department::factory(),
            'salary' => $this->faker->numberBetween(30000, 150000),
            'joining_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'address' => $this->faker->address(),
        ];
    }
}
