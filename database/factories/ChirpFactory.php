<?php

namespace Database\Factories;

use App\Models\Chirp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChirpFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chirp::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'message' => $this->faker->sentence(10),
            'user_id' => User::factory(),
            'created_at' => $this->faker->dateTimeBetween('-1 week'),
            'updated_at' => $this->faker->dateTimeBetween('-1 week'),
        ];
    }
}