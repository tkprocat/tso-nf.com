<?php namespace LootTracker\Repo;

use Illuminate\Support\ServiceProvider;
use LootTracker\Blog\BlogPost;
use LootTracker\Blog\BlogComment;
use LootTracker\Blog\DbBlogPostRepository;
use LootTracker\Blog\DbBlogCommentRepository;
use LootTracker\Guild\Guild;
use LootTracker\Guild\DbGuildRepository;
use LootTracker\Loot\UserAdventure;
use LootTracker\Loot\DbLootRepository;
use LootTracker\Adventure\Adventure;
use LootTracker\Adventure\DbAdventureRepository;
use LootTracker\Adventure\Admin\DbAdminAdventureRepository;
use LootTracker\Stats\DbStatsRepository;

class RepoServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //--------------- Blog ----------------

        $this->app->bind('LootTracker\Blog\BlogPostInterface', function () {
            return new DbBlogPostRepository(
                new BlogPost,
                $this->app->make('LootTracker\Blog\BlogCommentInterface'),
                $this->app->make('LootTracker\Blog\BlogPostFormValidator')
            );
        });

        $this->app->bind('LootTracker\Blog\BlogCommentInterface', function () {
            return new DbBlogCommentRepository(
                new BlogComment,
                $this->app->make('LootTracker\Blog\BlogCommentFormValidator')
            );
        });

        //--------------- Guild ----------------

        $this->app->bind('LootTracker\Guild\GuildInterface', function () {
            return new DbGuildRepository(
                new Guild,
                $this->app->make('LootTracker\Guild\GuildFormValidator'),
                $this->app->make('Authority\Repo\User\UserInterface'),
                $this->app->make('Cartalyst\Sentry\Sentry')
            );
        });


        //--------------- Loot ----------------

        $this->app->bind('LootTracker\Loot\LootInterface', function () {
            return new DbLootRepository(
                new UserAdventure,
                $this->app->make('Authority\Repo\User\UserInterface'),
                $this->app->make('LootTracker\Loot\LootFormValidator')
            );
        });


        //--------------- Admin Adventure ----------------

        $this->app->bind('LootTracker\Adventure\Admin\AdminAdventureInterface', function () {
            return new DbAdminAdventureRepository(
                new Adventure,
                $this->app->make('LootTracker\Adventure\Admin\AdminAdventureFormValidator')
            );
        });

        //--------------- Adventure ----------------

        $this->app->bind('LootTracker\Adventure\AdventureInterface', function () {
            return new DbAdventureRepository(
                new Adventure
            );
        });

        //--------------- Stats ----------------

        $this->app->bind('LootTracker\Stats\StatsInterface', function () {
            return new DbStatsRepository(
                $this->app->make('LootTracker\Loot\LootInterface')
            );
        });
    }
}