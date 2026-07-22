<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\SystemUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SubCategory1Controller;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\AccessoriesController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\WebsiteController;

Route::group(['as' => 'admin.'], function () {
    // Route::get('/', function () {
    //     return view('login');
    // })->middleware('guest');

    Route::get('access', function () {
        return view('denide');
    });

    Route::middleware('auth')->group(function () {

        //system_user related routes
        Route::get('system_users/data', [SystemUserController::class, 'data'])
            ->name('system_users.data');
        Route::patch('system_users/{system_user}/activate', [SystemUserController::class, 'activate'])
            ->name('system_users.activate');
        Route::patch('system_users/{system_user}/deactivate', [SystemUserController::class, 'deactivate'])
            ->name('system_users.deactivate');
        Route::resource('system_users', SystemUserController::class)->except(['create', 'edit']);

        //category related routes
        // Declared before the resource so "data" is not matched as categories/{category}.
        Route::get('categories/data', [CategoryController::class, 'data'])->name('categories.data');
        Route::resource('categories', CategoryController::class)->except(['create', 'edit']);

        //slider related routes
        Route::get('sliders/data', [SliderController::class, 'data'])->name('sliders.data');
        Route::resource('sliders', SliderController::class)->except(['create', 'edit']);

        //contact related routes — a single row, so no store/destroy
        Route::resource('contacts', ContactController::class)->only(['index', 'show', 'update']);

        //subcategory related routes — extras first so they beat {sub_category}
        Route::get('sub_categories/data', [SubCategoryController::class, 'data'])
            ->name('sub_categories.data');
        Route::get('sub_categories/by-category/{id}', [SubCategoryController::class, 'byCategory'])
            ->name('sub_categories.byCategory');
        Route::get('sub_categories/category/{id}', [SubCategoryController::class, 'categoryScreen'])
            ->name('sub_categories.categoryScreen');
        Route::resource('sub_categories', SubCategoryController::class)->except(['create', 'edit']);

        //subcategory1 related routes
        Route::get('sub_categories1/data', [SubCategory1Controller::class, 'data'])
            ->name('sub_categories1.data');
        Route::get('sub_categories1/by-parent/{id}', [SubCategory1Controller::class, 'byParent'])
            ->name('sub_categories1.byParent');
        Route::get('sub_categories1/parent/{id}', [SubCategory1Controller::class, 'parentScreen'])
            ->name('sub_categories1.parentScreen');
        Route::get('sub_categories1/count-by-name/{name}', [SubCategory1Controller::class, 'countByName'])
            ->name('sub_categories1.countByName');
        Route::resource('sub_categories1', SubCategory1Controller::class)
            ->parameters(['sub_categories1' => 'subCategory1'])
            ->except(['create', 'edit']);

        //family related routes
        // Both declared before the resource so they are not read as families/{family}.
        Route::get('families/data', [FamilyController::class, 'data'])->name('families.data');
        Route::get('families/by-subcategory/{cid}', [FamilyController::class, 'listByCategory'])
            ->name('families.listByCategory');
        Route::get('families/subcategory/{cid}', [FamilyController::class, 'categoryfamily'])
            ->name('families.categoryfamily');
        Route::resource('families', FamilyController::class)->except(['create', 'edit']);

        //product related routes — literal segments before products/{product}
        Route::controller(ProductController::class)->group(function () {
            Route::get('products', 'index')->name('products.index');
            Route::get('products/data', 'data')->name('products.data');
            Route::get('products/create', 'create')->name('products.create');
            Route::get('products/featured', 'featuredScreen')->name('products.featured');
            Route::get('products/featured/data', 'featuredData')->name('products.featured.data');

            Route::get('products/by-category/{id}', 'categoryScreen')->name('products.byCategory');
            Route::get('products/by-category/{id}/data', 'byCategory')->name('products.byCategory.data');
            Route::get('products/by-subcategory/{id}', 'subCategoryScreen')
                ->name('products.bySubCategory');
            Route::get('products/by-subcategory/{id}/data', 'bySubCategory')
                ->name('products.bySubCategory.data');

            Route::get('products/check-validity/{card_number}/{store_id}', 'checkValidity')
                ->name('products.checkValidity');
            Route::get('products/check-validity/{card_number}/{store_id}/{card_number1}', 'checkValidityPair')
                ->name('products.checkValidityPair');

            Route::post('products', 'store')->name('products.store');
            Route::get('products/{product}', 'show')->name('products.show');
            Route::get('products/{product}/edit', 'editForm')->name('products.edit');
            Route::get('products/{product}/details', 'details')->name('products.details');
            Route::put('products/{product}', 'update')->name('products.update');
            Route::delete('products/{product}', 'destroy')->name('products.destroy');
            Route::patch('products/{product}/feature', 'feature')->name('products.feature');
            Route::patch('products/{product}/unfeature', 'unfeature')->name('products.unfeature');
        });

        //product gallery — nested so the product comes from the URL, not the session
        Route::get('products/{product}/gallery', [GalleryController::class, 'index'])
            ->name('gallery.index');
        Route::get('products/{product}/gallery/data', [GalleryController::class, 'data'])
            ->name('gallery.data');
        Route::post('products/{product}/gallery', [GalleryController::class, 'store'])
            ->name('gallery.store');
        Route::delete('gallery/{gallery}', [GalleryController::class, 'destroy'])
            ->name('gallery.destroy');

        //product parameters — likewise nested
        Route::get('products/{product}/parameters', [ParameterController::class, 'index'])
            ->name('parameters.index');
        Route::get('products/{product}/parameters/data', [ParameterController::class, 'data'])
            ->name('parameters.data');
        Route::post('products/{product}/parameters', [ParameterController::class, 'store'])
            ->name('parameters.store');
        Route::put('parameters/{parameter}', [ParameterController::class, 'update'])
            ->name('parameters.update');
        Route::delete('parameters/{parameter}', [ParameterController::class, 'destroy'])
            ->name('parameters.destroy');

        //accessories related routes
        Route::get('accessories/data', [AccessoriesController::class, 'data'])->name('accessories.data');
        Route::resource('accessories', AccessoriesController::class)
            ->parameters(['accessories' => 'accessory'])
            ->except(['create', 'edit']);

        //series related routes
        Route::get('series/data', [SeriesController::class, 'data'])->name('series.data');
        Route::resource('series', SeriesController::class)
            ->parameters(['series' => 'series'])
            ->except(['create', 'edit']);

        
        //about page — a single row plus two flat image collections
        Route::controller(AboutController::class)->group(function () {
            Route::get('about_page', 'index')->name('about.index');
            Route::get('about_page/data', 'show')->name('about.show');
            Route::put('about_page', 'update')->name('about.update');

            Route::get('about_page/gallery', 'gallery')->name('about.gallery.index');
            Route::get('about_page/gallery/data', 'galleryData')->name('about.gallery.data');
            Route::post('about_page/gallery', 'storeGalleryImage')->name('about.gallery.store');
            Route::delete('about_page/gallery/{gallery}', 'destroyGalleryImage')
                ->name('about.gallery.destroy');

            Route::get('about_page/team', 'team')->name('about.team.index');
            Route::get('about_page/team/data', 'teamData')->name('about.team.data');
            Route::post('about_page/team', 'storeTeamImage')->name('about.team.store');
            Route::delete('about_page/team/{team}', 'destroyTeamImage')->name('about.team.destroy');
        });

        //orders related routes — JSON only; there is no order screen (see below)
        Route::controller(OrderController::class)->group(function () {
            Route::get('orders/data', 'data')->name('orders.data');
            Route::get('orders/{order}', 'show')->name('orders.show');
            Route::get('orders/{order}/items', 'items')->name('orders.items');
            Route::patch('orders/{order}/status', 'updateStatus')->name('orders.updateStatus');
            Route::get('users/{user}/orders', 'byUser')->name('orders.byUser');
            Route::get('users/{user}/details', 'userDetails')->name('orders.userDetails');
        });

    }); // end auth middleware group

});

//login/auth related routes
Route::get('login', [MainController::class, 'index'])
->middleware('guest')
->name('login');
Route::post('checklogin', [MainController::class, 'checklogin'])->middleware('throttle:5,1');
Route::get('successlogin', [MainController::class, 'successlogin']);
Route::get('logout', [MainController::class, 'logout']);
Route::get('noaccess', function () {
    return view('noaccess');
});

//public website routes
Route::controller(WebsiteController::class)->middleware('cache.html')->group(function () {
    Route::get('sendemail', 'send_email');
    Route::get('filter', 'filter');
    Route::get('filterpop', 'filterpop');
    Route::get('/', 'index')->name('web');
    Route::get('/about', 'about')->name('/about');
    Route::get('/contactus', 'contact')->name('/contactus');
    Route::get('/{id}/{type}/{name2}', 'categories')->name('/{id}/{type}/{name2}');
    Route::get('web/getcategory/', 'get_category');
    Route::get('web/getvolt/', 'get_volt');
    Route::get('web/gethertz/', 'get_hertz');
    Route::get('web/getdm/', 'get_dm');
    Route::get('web/getrpm/', 'get_rpm');
    Route::get('web/getmaterial/', 'get_material');
});