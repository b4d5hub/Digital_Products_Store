<?php

namespace App\Providers;

use App\Models\StoreSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Schema;



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
        $StoreSettings = StoreSetting::pluck('value', 'name')->toArray();
        View::share('StoreSettings', $StoreSettings);
    }
}
