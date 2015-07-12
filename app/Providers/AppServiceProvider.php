<?php namespace LootTracker\Providers;

use Illuminate\Support\ServiceProvider;
use LootTracker\Repositories\Adventure\Admin\EloquentAdminAdventureRepository;
use LootTracker\Repositories\Adventure\EloquentAdventureRepository;
use LootTracker\Repositories\Blog\EloquentBlogCommentRepository;
use LootTracker\Repositories\Blog\EloquentBlogPostRepository;
use LootTracker\Repositories\Guild\EloquentGuildApplicationRepository;
use LootTracker\Repositories\Guild\EloquentGuildRepository;
use LootTracker\Repositories\Loot\EloquentLootRepository;
use LootTracker\Repositories\PriceList\Admin\EloquentAdminPriceListRepository;
use LootTracker\Repositories\PriceList\EloquentPriceListRepository;
use LootTracker\Repositories\Stats\EloquentGeneralStatsRepository;
use LootTracker\Repositories\Stats\EloquentGlobalStatsRepository;
use LootTracker\Repositories\Stats\EloquentGuildStatsRepository;
use LootTracker\Repositories\Stats\EloquentPersonalStatsRepository;
use LootTracker\Repositories\User\EloquentUserRepository;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'LootTracker\Services\Registrar'
		);


        //--------------- Blog ----------------

        $this->app->bind('LootTracker\Repositories\Blog\BlogPostInterface', function () {
            return new EloquentBlogPostRepository();
        });

        $this->app->bind('LootTracker\Repositories\Blog\BlogCommentInterface', function () {
            return new EloquentBlogCommentRepository();
        });


        //--------------- User ------------------
        $this->app->bind('LootTracker\Repositories\User\UserInterface', function () {
            return new EloquentUserRepository();
        });


        //--------------- Guild ----------------

        $this->app->bind('LootTracker\Repositories\Guild\GuildInterface', function () {
            return new EloquentGuildRepository(
                $this->app->make('LootTracker\Repositories\User\UserInterface')
            );
        });

        $this->app->bind('LootTracker\Repositories\Guild\GuildApplicationInterface', function () {
            return new EloquentGuildApplicationRepository(
                $this->app->make('LootTracker\Repositories\User\UserInterface')
            );
        });


        //--------------- Loot ----------------

        $this->app->bind('LootTracker\Repositories\Loot\LootInterface', function () {
            return new EloquentLootRepository();
        });


        //--------------- Admin Adventure ----------------

        $this->app->bind('LootTracker\Repositories\Adventure\Admin\AdminAdventureInterface', function () {
            return new EloquentAdminAdventureRepository();
        });


        //--------------- Adventure ----------------

        $this->app->bind('LootTracker\Repositories\Adventure\AdventureInterface', function () {
            return new EloquentAdventureRepository(
            );
        });


        //--------------- Prices ----------------

        $this->app->bind('LootTracker\Repositories\PriceList\PriceListInterface', function () {
            return new EloquentPriceListRepository();
        });


        //--------------- Admin Prices ----------------

        $this->app->bind('LootTracker\Repositories\PriceList\Admin\AdminPriceListInterface', function () {
            return new EloquentAdminPriceListRepository();
        });


        //--------------- Stats ----------------

        $this->app->bind('LootTracker\Repositories\Stats\GeneralStatsInterface', function () {
            return new EloquentGeneralStatsRepository();
        });

        $this->app->bind('LootTracker\Repositories\Stats\GlobalStatsInterface', function () {
            return new EloquentGlobalStatsRepository(
                $this->app->make('LootTracker\Repositories\Loot\LootInterface')
            );
        });

        $this->app->bind('LootTracker\Repositories\Stats\GuildStatsInterface', function () {
            return new EloquentGuildStatsRepository(
                $this->app->make('LootTracker\Repositories\Loot\LootInterface')
            );
        });

        $this->app->bind('LootTracker\Repositories\Stats\PersonalStatsInterface', function () {
            return new EloquentPersonalStatsRepository(
                $this->app->make('LootTracker\Repositories\Loot\LootInterface')
            );
        });
	}
}