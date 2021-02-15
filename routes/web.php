<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;

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

Route::name('user.')->prefix('user')->group(function () {
    Route::get('index', [App\Http\Controllers\UserController::class, 'index'])->name('index');
    Auth::routes();
});

Route::name('admin.')->prefix('admin')->group(function () {
    Route::get('index', AdminController::class)->name('index');
});

Route::group([
    'as' => 'admin.',
    'prefix' => 'admin',
    'middleware' => ['auth', 'admin']
], function () {
    // main page of admin panel
    Route::get('index', IndexController::class)->name('index');
    // CRUD for category
    Route::resource('category', CategoryController::class);
    Route::resource('product', ProductController::class);
    Route::resource('order', OrderController::class, ['expect' => [
        'create', 'store', 'destroy'
    ]]);
});


Route::get('/', IndexController::class)->name('index');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/catalog', [App\Http\Controllers\CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/category/{slug}', [App\Http\Controllers\CatalogController::class, 'category'])->name('catalog.category');
Route::get('/catalog/product/{slug}', [App\Http\Controllers\CatalogController::class, 'product'])->name('catalog.product');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




Route::get('/basket/index', [App\Http\Controllers\BasketController::class, 'index'])->name('basket.index');
Route::get('/basket/checkout', [App\Http\Controllers\BasketController::class, 'checkout'])->name('basket.checkout');
Route::post('/basket/saveorder', [App\Http\Controllers\BasketController::class, 'saveorder'])->name('basket.saveorder');
Route::get('/basket/success', [App\Http\Controllers\BasketController::class, 'success'])->name('basket.success');
Route::post('/basket/add/{id}', [App\Http\Controllers\BasketController::class, 'add'])->where('id', '[0-9]+')->name('basket.add');
// Route::post('/basket/plus/{id}', [App\Http\Controllers\BasketController::class, 'plus'])->where('id', '[0-9]+')->name('basket.plus');
// Route::post('/basket/minus/{id}', [App\Http\Controllers\BasketController::class, 'minus'])->where('id', '[0-9]+')->name('basket.minus');
// Route::post('/basket/remove/{id}', [App\Http\Controllers\BasketController::class, 'remove'])->where('id', '[0-9]+')->name('basket.remove');
// Route::post('/basket/clear', [App\Http\Controllers\BasketController::class, 'clear'])->name('basket.clear');


