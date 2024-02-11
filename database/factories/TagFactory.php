<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->randomElement(
            [
                'Tag 1',
                'Tag 2',
                'Tag 3',
                'Tag 4',
                'Tag 5',
                'Tag 6',
                'Sample', 'Another'
            ]);

        return [
            'title' => $title,
            'color' => $this->faker->hexColor,
            'language' => $this->faker->randomElement(['en', 'bn']),
            'created_by' => \App\Models\User::factory(),
        ];
    }
}