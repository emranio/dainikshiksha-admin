<?php

namespace Database\Seeders;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $this->command->warn(PHP_EOL . 'Creating test users...');

        $names = ['Admin', 'Ad Manager', 'Editor', 'Reporter', 'Subscriber'];
        $roles = ['admin', 'admanager', 'editor', 'reporter', 'subscriber'];
        $emails = [
            'admin@gmail.com',
            'admanager@gmail.com',
            'editor@gmail.com',
            'reporter@gmail.com',
            'subscriber@gmail.com'
        ];

        for ($i = 0; $i < 5; $i++) {
            \App\Models\User::create([
                'name' => $names[$i],
                'role' => $roles[$i],
                'email' => $emails[$i],
                'password' => Hash::make('password'), // replace 'password' with the desired password
            ]);
        }

        $this->command->info('Test users are created.');

        // Creating test categories
        $this->command->warn(PHP_EOL . 'Creating test categories...');

        $titles = ['Category A', 'Category B'];
        $slugs = ['category-a', 'category-b'];
        $colors = ['#FF0000', '#00FF00'];
        $languages = ['en', 'en'];
        $seo_titles = ['Awesome Category A', 'Wow Category B'];
        $seo_descriptions = ['My awesome category', 'My wow category'];
        $created_by = [1, 1]; // Assuming the users with id 1 created these categories

        for ($i = 0; $i < 2; $i++) {
            \App\Models\Category::create([
                'title' => $titles[$i],
                'slug' => $slugs[$i],
                'color' => $colors[$i],
                'language' => $languages[$i],
                'seo_title' => $seo_titles[$i],
                'seo_description' => $seo_descriptions[$i],
                'created_by' => $created_by[$i],
            ]);
        }

        $this->command->info('Test categories created.');


        // Creating test tags
        $this->command->warn(PHP_EOL . 'Creating test tags...');

        $titles = ['Tag A', 'Tag B'];
        $colors = ['#FF0000', '#00FF00'];
        $languages = ['en', 'en'];
        $created_by = [1, 1]; // Assuming the users with id 1 created these tags

        for ($i = 0; $i < 2; $i++) {
            \App\Models\Tag::create([
                'title' => $titles[$i],
                'color' => $colors[$i],
                'language' => $languages[$i],
                'created_by' => $created_by[$i],
            ]);
        }

        $this->command->info('Test tags created.');


        // Creating test authors
        $this->command->warn(PHP_EOL . 'Creating test authors...');

        $titles = ['Dainikshiksha Desk', 'Rabindranath Tagore'];
        $bios = ['Details for Dainikshiksha Desk', 'Bio for Rabindranath Tagore'];
        $colors = ['#FF0000', '#00FF00'];
        $languages = ['en', 'en'];
        $created_by = [1, 1]; // Assuming the users with id 1 created these authors

        for ($i = 0; $i < 2; $i++) {
            \App\Models\Author::create([
                'title' => $titles[$i],
                'bio' => $bios[$i],
                'color' => $colors[$i],
                'thumbnail' => null,
                'language' => $languages[$i],
                'created_by' => $created_by[$i],
            ]);
        }

        $this->command->info('Test authors created.');
    }

    protected function withProgressBar(int $amount, Closure $createCollectionOfOne): Collection
    {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);

        $progressBar->start();

        $items = new Collection();

        foreach (range(1, $amount) as $i) {
            $items = $items->merge(
                $createCollectionOfOne()
            );
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->getOutput()->writeln('');

        return $items;
    }
}
