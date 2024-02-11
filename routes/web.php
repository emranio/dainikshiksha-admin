<?php

use App\Http\Controllers\SocialiteController;
use App\Livewire\Form;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('form', Form::class);

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');


Route::get('/test', function () {
    // $comment = new \App\Models\Comment();
    // $comment->news_id = 1;
    // $comment->comment_body = 'This is a test comment';
    // // $comment->approved = 1;
    // $comment->created_by = auth()->id();
    // $comment->save();

    // $commentID = isset($comment) ? $comment->id : 5; 

    // $comment = \App\Models\Comment::where('id', $commentID)->with('news')->get()->toArray();

    // dd($comment);


    $faker = \Faker\Factory::create();
    $newsData = [
        'title' => $faker->sentence,
        'slug' => \Str::slug($faker->sentence),
        'social_title' => $faker->sentence,
        'sub_title' => $faker->sentence,
        'upper_title' => $faker->sentence,
        'title_color' => $faker->hexColor,
        'title_size' => $faker->randomElement([
            '1.24', '1.25', '1.28', '1.32', '1.36', '1.48', '1.52'
        ]),
        'sub_title_color' => $faker->hexColor,
        'upper_title_color' => $faker->hexColor,
        'news_body' => $faker->paragraph,
        'summary' => $faker->paragraph,
        'social_summary' => $faker->paragraph,
        // 'author_id' => \App\Models\Author::factory(),
        'updated_by' => 1,
        'position' => $faker->randomElement(\config('app.news_position')),
        'show_created_at' => $faker->boolean,
        'show_updated_at' => $faker->boolean,
        'show_thumbnail' => $faker->boolean,
        'language' => $faker->randomElement(['en', 'bn']),
        'status' => $faker->randomElement(['draft', 'reviewing', 'published']),
    ];

    // create new news
    $news = \App\Models\News::create($newsData);
    dd($news->toArray());

});