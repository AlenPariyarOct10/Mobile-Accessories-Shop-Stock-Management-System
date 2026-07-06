<?php

namespace App\Providers;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        View::composer('*', function ($view): void {
            static $companySetting = false;

            if ($companySetting === false) {
                try {
                    $companySetting = CompanySetting::query()->first();
                } catch (Throwable) {
                    $companySetting = null;
                }
            }

            $view->with('companySetting', $companySetting);
        });
    }
}
