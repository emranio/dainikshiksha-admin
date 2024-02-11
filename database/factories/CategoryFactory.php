<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->randomElement(
            [
                'Category 1', 
                'Category 2', 
                'Category 3', 
                'Category 4', 
                'Category 5', 
                'Foo', 'Bar'
            ]);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'color' => $this->faker->hexColor,
            'language' => $this->faker->randomElement(['en', 'bn']),
            'seo_title' => $title,
            'seo_description' => $this->faker->paragraph,
            'created_by' => \App\Models\User::factory(),
        ];
    }
}