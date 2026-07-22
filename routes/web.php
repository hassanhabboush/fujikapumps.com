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
        Route::controller(SystemUserController::class)->group(function () {
            Route::get('system_user', 'index')->name('system_user');
            Route::get('readsystem_user', 'readall');
            Route::post('addsystem_user', 'insert');
            Route::post('editsystem_user', 'edit');
            Route::get('deletesystem_user', 'delete')->name('deletesystem_user');
            Route::get('activeuser/{id}', 'active_user');
            Route::get('disactiveuser/{id}', 'disactive_user');
            Route::get('getsystem_user/{id}', 'getsystem_user');
        });

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

        //product related routes
        Route::controller(ProductController::class)->group(function () {
            Route::get('product', 'index')->name('product');
            Route::get('readproduct', 'readall');
            Route::get('featuredproduct', 'indexfeature')->name('featuredproduct');
            Route::get('readfeaturedproduct', 'readallfeature');
            Route::get('addproduct', 'add_product')->name('addproduct');
            Route::post('addproduct', 'insert');
            Route::post('updateproduct', 'edit');
            Route::get('editproduct/{id}', 'edit_product');
            Route::get('deleteproduct', 'delete');
            Route::get('makefeature/{id}', 'feature');
            Route::get('removefeature/{id}', 'remove_feature');
            Route::get('getproduct1/{id}', 'getproduct');
            Route::get('categoryproduct/{sid}', 'categoryproduct');
            Route::get('readcategoryproduct/{sid}', 'readcategoryproduct')->name('readcategoryproduct');
            Route::get('subcategoryproduct/{sid}', 'subcategoryproduct')->name('subcategory_product');
            Route::get('readsubcategoryproduct/{sid}', 'readsubcategoryproduct');
            Route::get('check_validity/{card_number}/{store_id}', 'check_validity');
            Route::get('check_validity1/{card_number}/{store_id}/{card_number1}','check_validity1');
            Route::get('productdetails/{id}', 'productdetails');
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

        
        Route::controller(AboutController::class)->group(function () {
            //about related routes
            Route::get('aboutdetails', 'index')->name('aboutdetails');
            Route::get('getabout', 'getabout');
            Route::post('editabout', 'edit');
            
            //about gallery related routes
            Route::get('aboutgallery', 'gallery')->name('aboutgallery');
            Route::get('readgalleryabout', 'readallgallery');
            Route::post('addgalleryabout', 'insertgallery');
            Route::get('deletegalleryabout', 'deletegallery');
            
            //team related routes
            Route::get('team', 'team')->name('team');
            Route::get('readallteam', 'readallteam');
            Route::post('addteam', 'insertteam');
            Route::get('deleteteam', 'deleteteam');
        });

        //orders related routes
        Route::controller(OrderController::class)->group(function () {
            Route::get('order', 'index')->name('order');
            Route::get('readorder', 'readall');
            Route::get('userorder/{uid}', 'user_order');
            Route::get('orderitem/{oid}', 'order_details');
            Route::get('readuserorder/{uid}', 'readuserorder');
            Route::get('readorderitem/{oid}', 'readorderdetails');
            Route::post('changestatus', 'changestatus');
            Route::get('orderdetails/{id}', 'orderdetils');
            Route::get('userdetails/{id}', 'user_details');
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