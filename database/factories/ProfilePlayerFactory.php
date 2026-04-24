<?php

namespace Database\Factories;

use App\Models\ProfilePlayer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfilePlayerFactory extends Factory
{
    protected $model = ProfilePlayer::class;

    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'bio'            => $this->faker->optional()->sentence(12),
            'total_matches'  => $this->faker->numberBetween(0, 200),
            'win_rate'       => $this->faker->randomFloat(2, 0, 100),
            'total_trophies' => $this->faker->numberBetween(0, 50),
            'status'         => $this->faker->randomElement(['FREE', 'IN_TEAM']),
        ];
    }
}
