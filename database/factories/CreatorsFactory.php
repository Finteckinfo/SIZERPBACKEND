<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CreatorsFactory extends Factory
{

    public function definition(): array
    {
        return [
            'profile' => $this->faker->imageUrl(300, 300, 'people', true, 'Profile'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'creator_type' => $this->faker->randomElement(['Direct Pay', 'Non-Direct Pay']),
            'on_board_date' => $this->faker->date(),
            'off_board_date' => null,
            'google_sheet_name' => $this->faker->word(),
            'google_sheet_name_new_template' => $this->faker->word(),
            'account_group' => $this->faker->randomElement(['FreePages', 'Default']),
            'on_platform' => $this->faker->boolean(80),
            'on_platform_date' => now()->subDays(rand(1, 30)),
            'off_platform_date' => null,
            'is_active' => $this->faker->boolean(90),
            'archived' => $this->faker->boolean(10),
            'content_type' => $this->faker->randomElement(['Music', 'Art', 'Photography','Fitness']),
            'last_active' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
