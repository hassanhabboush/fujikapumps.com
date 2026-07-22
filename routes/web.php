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
        Route::controller(SliderController::class)->group(function () {
            Route::get('slider', 'index')->name('slider');
            Route::get('readslider', 'readall');
            Route::post('addslider', 'insert');
            Route::post('editslider', 'edit');
            Route::get('deleteslider', 'delete');
            Route::get('getslider/{id}', 'getslider');
        });

        //contact related routes
        Route::controller(ContactController::class)->group(function () {
            Route::get('contact', 'index')->name('contact');
            Route::get('getcontact/{id}', 'getcontact');
            Route::post('editcontact', 'edit');
        });

        //subcategory related routes
        Route::controller(SubCategoryController::class)->group(function () {
            Route::get('sub_category', 'index')->name('sub_category');
            Route::get('readsub_category', 'readall');
            Route::post('addsub_category', 'insert');
            Route::post('editsub_category', 'edit');
            Route::get('deletesub_category', 'delete');
            Route::get('getsub_category/{id}', 'getsub_category');
            Route::get('categorysub_category/{cid}', 'categorysub_category');
        });

        //subcategory1 related routes
        Route::controller(SubCategory1Controller::class)->group(function () {
            Route::get('sub_category1', 'index')->name('sub_category1');
            Route::get('readsub_category1', 'readall');
            Route::post('addsub_category1', 'insert');
            Route::post('editsub_category1', 'edit');
            Route::get('deletesub_category1', 'delete');
            Route::get('getsub_category1/{id}', 'getsub_category');
            Route::get('sub_subcategory/{cid}', 'categorysub_category');
            Route::get('readsubcetegory_category1/{cid}', 'readall_category');
            Route::get('readsubcetegory_category11/{name}', 'readall_category');
        });

        //family related routes
        Route::controller(FamilyController::class)->group(function () {
            Route::get('family', 'index')->name('family');
            Route::get('readfamily', 'list')->name('family.list');
            Route::post('family', 'store')->name('family.store');
            Route::get('family/{family}', 'show')->name('family.show');
            Route::put('family/{family}', 'update')->name('family.update');
            Route::delete('family/{family}', 'destroy')->name('family.destroy');
            Route::get('subcategoryfamily/{cid}', 'categoryfamily')->name('family.categoryfamily');
            Route::get('readsubfamily/{cid}', 'listByCategory')->name('family.listByCategory');
        });

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

        //gallery related routes
        Route::controller(GalleryController::class)->group(function () {
            Route::get('gallery/{id}', 'index');
            Route::get('readgallery/{id}', 'readall');
            Route::post('addgallery', 'insert');
            Route::get('deletegallery', 'delete');
        });

        //parameter related routes
        Route::controller(ParameterController::class)->group(function () {
            Route::get('parameter/{id}', 'index');
            Route::get('readparameter/{id}', 'readall');
            Route::get('addparameter', 'insert');
            Route::get('updateparameter', 'update');
            Route::get('deleteparameter', 'delete');
        });

        //accessories related routes
        Route::controller(AccessoriesController::class)->group(function () {
                Route::get('accessories', 'index')->name('accessories');
                Route::get('readaccessories', 'readall');
                Route::post('addaccessories', 'insert');
                Route::post('editaccessories', 'edit');
                Route::get('deleteaccessories', 'delete');
                Route::get('getaccessories/{id}', 'getacc');
        });

        //series related routes
        Route::controller(SeriesController::class)->group(function () {
            Route::get('series', 'index')->name('series');
            Route::get('readseries', 'readall');
            Route::post('addseries', 'insert');
            Route::post('editseries', 'edit');
            Route::get('deleteseries', 'delete');
            Route::get('getseries/{id}', 'getacc');
        });

        
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
        //misc admin routes
        Route::get('getcat/{id}', [SubCategory1Controller::class, 'getscat']);

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