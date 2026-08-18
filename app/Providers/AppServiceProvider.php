<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Contact;
use App\Models\Category;
use App\Support\AdminSidebarCounts;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        @ini_set('memory_limit', '512M');

        if (! $this->app->environment('local')) {
            URL::forceScheme('https');
            if ($root = config('app.url')) {
                URL::forceRootUrl($root);
            }
        }

        Schema::defaultStringLength(191);

        View::composer('Layout.sidebar', function ($view) {
            try {
                $view->with('sidebarCounts', AdminSidebarCounts::all());
            } catch (\Exception $e) {
                $view->with('sidebarCounts', [
                    'categories' => 0, 'sub_category' => 0, 'sub_category_1' => 0,
                    'family' => 0, 'series' => 0, 'accessories' => 0,
                    'products' => 0, 'featured' => 0, 'slider' => 0,
                ]);
            }
        });

        View::composer(['web.Layout.header', 'web.Layout.footer', 'web.Layout.head', 'web.Layout.menu-bar', 'web.contact', 'web.home'], function ($view) {
            try {
                $view->with('contact', Cache::remember('contact', now()->addHours(1), fn () => Contact::first()));
                $view->with(
                    'headerCategories',
                    Cache::remember(
                        'headerCategories',
                        now()->addHours(1),
                        fn () => Category::with('subCategories.subCategory1s.families')->get()
                    )
                );
            } catch (\Exception $e) {
                $view->with('contact', null);
                $view->with('headerCategories', collect());
            }
        });
    }

    public function register()
    {
        //
    }
}
