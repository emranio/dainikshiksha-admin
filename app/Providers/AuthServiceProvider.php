<?php

namespace App\Providers;

use App\Models\Author;
use App\Models\Category;
use App\Models\Comment;
use App\Models\News;
use App\Models\Poll;
use App\Models\Settings;
use App\Models\Tag;
use App\Models\User;
use App\Policies\AuthorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\NewsPolicy;
use App\Policies\PollPolicy;
use App\Policies\SettingsPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
        Tag::class => TagPolicy::class,
        Comment::class => CommentPolicy::class,
        Settings::class => SettingsPolicy::class,
        Poll::class => PollPolicy::class,
        News::class => NewsPolicy::class,
        Author::class => AuthorPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
