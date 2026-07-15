<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use App\Models\Contact;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        @ini_set('memory_limit', '512M');
        @ini_set('max_upload_size', '256M');


        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        // Cache invalidation is now handled per-model via the
        // App\Traits\InvalidatesCache trait, which forgets only the
        // cache keys each model is responsible for on save/delete.

        try {
            view()->share('contact', Cache::remember('contact', now()->addHours(1), fn () => Contact::first()));

            view()->share('headerCategories', Cache::remember('headerCategories', now()->addHours(1), fn () => Category::with('subCategories.subCategory1s.families')->get()));
        } catch (\Exception $e) {
            // DB not available (e.g. during migrations or artisan commands)
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}