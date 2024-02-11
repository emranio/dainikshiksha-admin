<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CommentFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {
        return [
            'news_id' => \App\Models\News::factory(),
            'comment_body' => $this->faker->paragraph,
            'approved' => $this->faker->boolean,
            'language' => $this->faker->randomElement(['en', 'bn']),
            'created_by' => \App\Models\User::factory(),
        ];
    }
}