<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\News::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'slug' => Str::slug($this->faker->sentence),
            'social_title' => $this->faker->sentence,
            'sub_title' => $this->faker->sentence,
            'upper_title' => $this->faker->sentence,
            'title_color' => $this->faker->hexColor,
            'title_size' => $this->faker->randomElement([
                '1.24', '1.25', '1.28', '1.32', '1.36', '1.48', '1.52'
            ]),
            'sub_title_color' => $this->faker->hexColor,
            'upper_title_color' => $this->faker->hexColor,
            'news_body' => $this->faker->paragraph,
            'summary' => $this->faker->paragraph,
            'social_summary' => $this->faker->paragraph,
            'author_id' => \App\Models\Author::factory(),
            'updated_by' => \App\Models\User::factory(),
            'created_by' => \App\Models\User::factory(),
            'position' => $this->faker->randomElement(\config('app.news_position')),
            'show_created_at' => $this->faker->boolean,
            'show_updated_at' => $this->faker->boolean,
            'show_thumbnail' => $this->faker->boolean,
            'language' => $this->faker->randomElement(['en', 'bn']),
            'status' => $this->faker->randomElement(['draft', 'reviewing', 'published']),
        ];
    }
    // add relation data Category and Tag
    public function configure()
    {
        return $this->afterCreating(function (\App\Models\News $news) {
            $news->categories()->attach(\App\Models\Category::factory()->create());
            $news->tags()->attach(\App\Models\Tag::factory()->create());
        });
    }
}
