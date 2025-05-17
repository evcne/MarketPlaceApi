<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Services\UserService;
use App\Base\BaseResponse;
use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Services\AuthService;
use App\Modules\Auth\Repositories\AuthRepository;


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

        $this->app->bind(AuthService::class);
        $this->app->bind(AuthRepository::class);

        $this->app->bind(CompanyService::class);
        $this->app->bind(CompanyRepository::class);

        $this->app->bind(CategoryService::class);
        $this->app->bind(CategoryRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::routes(function () {
            return collect(Route::getRoutes())
                ->filter(function ($route) {
                    return str_starts_with($route->uri, 'api/');
                });
        });
    
    }
}
