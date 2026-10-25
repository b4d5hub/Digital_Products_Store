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
        $StoreSettings = Schema::hasTable('store_settings')
            ? StoreSetting::pluck('value', 'name')->toArray()
            : [];

        if (Schema::hasTable('store_settings')) {
            $StoreSettings['terms_of_use'] = StoreSetting::query()
                ->where('name', 'like', 'terms_of_use_%')
                ->orderBy('name')
                ->pluck('value')
                ->map(fn (string $value) => base64_decode($value, true) ?: '')
                ->implode('');
        }

        View::share('StoreSettings', $StoreSettings);
    }
}
