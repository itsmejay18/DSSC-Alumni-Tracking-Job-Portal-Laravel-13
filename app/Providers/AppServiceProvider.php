<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // SCALABLE: Preventing lazy loading helps catch N+1 issues before production rollout.
        Model::preventLazyLoading(! app()->isProduction());

        if (app()->isProduction()) {
            // SECURE: Production traffic is forced over HTTPS for safer authentication and file handling.
            URL::forceScheme('https');
        }

        Paginator::defaultView('vendor.pagination.portal');
    }
}
