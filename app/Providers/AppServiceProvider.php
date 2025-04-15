<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Services\UserService;
use App\Base\BaseResponse;


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
