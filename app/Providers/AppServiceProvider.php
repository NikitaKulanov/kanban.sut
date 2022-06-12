<?php

namespace App\Providers;

use App\Http\Controllers\BindingController;
use App\Services\BindingService;
use Illuminate\Http\Request;
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
        $this->app->bind(BindingController::class, function () {
            return new BindingController(
                config('sut.rights_for_binding'),
                new BindingService(),
                $this->app->make(Request::class)
            );
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
