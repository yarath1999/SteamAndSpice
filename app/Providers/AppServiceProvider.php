<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
        View::share('siteSettings', $this->resolveSiteSettings());
    }

    private function resolveSiteSettings(): object
    {
        $fallback = (object) [
            'phone' => '+44 20 1234 5678',
            'email' => 'hello@steamandspice.co.uk',
            'address' => '221B Baker Street, London, UK',
        ];

        if (!Schema::hasTable('site_settings')) {
            return $fallback;
        }

        return SiteSetting::query()->latest()->first() ?: SiteSetting::query()->create([
            'phone' => $fallback->phone,
            'email' => $fallback->email,
            'address' => $fallback->address,
        ]);
    }
}
