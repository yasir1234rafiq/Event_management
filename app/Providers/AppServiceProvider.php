<?php

namespace App\Providers;

use App\Repositories\eventRepositoryInterface;
use App\Repositories\eventRepository;
use App\Services\EventService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Paginator::useBootstrap();
$this->app->bind(eventRepositoryInterface::class,eventRepository::class);
        $this->app->bind(EventService::class, function($app) {
            return new EventService($app->make(EventRepositoryInterface::class));
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
