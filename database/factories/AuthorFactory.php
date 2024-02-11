<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Author::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'bio' => $this->faker->paragraph,
            'color' => $this->faker->hexColor,
            'language' => $this->faker->randomElement(['en', 'bn']),
            'created_by' => \App\Models\User::factory(),
        ];
    }
}