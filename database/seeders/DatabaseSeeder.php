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

        // // Creating test comments from factory
        $this->command->warn(PHP_EOL . 'Creating test comments...');
        \App\Models\Comment::factory(5)->create();
        $this->command->info('Test comments are created.');

        // Creating test news from factory
        $this->command->warn(PHP_EOL . 'Creating test news...');
        \App\Models\News::factory(10)->create();
        $this->command->info('Test news are created.');
        
    }

}
