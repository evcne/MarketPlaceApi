<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Services\UserService;
use App\Responses\BaseResponse;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(UserService::class, function ($app) {

            return new UserService(
                $app->make(UserRepository::class),
                $app->make('App\Helpers\Helper'),
                $app->make(BaseResponse::class),

            );
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        

    }
}
