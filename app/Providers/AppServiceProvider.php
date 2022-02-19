<?php

namespace App\Providers;

use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\Implementations\UserRepository;
use App\Services\Contracts\UploadFileServiceContract;
use App\Services\Implementations\UploadFileService;
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
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(UploadFileServiceContract::class, UploadFileService::class);
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
