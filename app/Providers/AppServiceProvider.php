<?php

namespace App\Providers;

use App\JsonApi\JsonApiBuilder;
use CloudCreativity\LaravelJsonApi\LaravelJsonApi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
     LaravelJsonApi::defaultApi('v1');
    }
}
