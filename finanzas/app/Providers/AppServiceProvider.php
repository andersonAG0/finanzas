<?php

namespace App\Providers;

use App\Models\Entrie;
use App\Models\Expense;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Observers\EntrieObserver;
use App\Observers\ExpenseObserver;

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
    public function boot()
    {
        Route::middleware('api')
            ->prefix('api') // <- Prefijo /api
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        Entrie::observe(EntrieObserver::class);
        Expense::observe(ExpenseObserver::class);
    }
}
