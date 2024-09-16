<?php

namespace App\Providers;

use App\Services\FeedItemService;
use App\Services\MessageService;
use App\Services\RecommendationService;
use App\Services\SubscriptionService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService();
        });
        $this->app->singleton(MessageService::class, function ($app) {
            return new MessageService();
        });
        $feedItemService = new FeedItemService();
        $this->app->singleton(FeedItemService::class, function ($app) {
            return new FeedItemService();
        });
        $this->app->singleton(SubscriptionService::class, function ($app) {
            return new SubscriptionService($app->make(FeedItemService::class));
        });
        $this->app->singleton(RecommendationService::class, function ($app) {
            return new RecommendationService($app->make(FeedItemService::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
