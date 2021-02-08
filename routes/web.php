<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\IndexController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', IndexController::class)->name('index');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/catalog', [App\Http\Controllers\CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/category/{slug}', [App\Http\Controllers\CatalogController::class, 'category'])->name('catalog.category');
Route::get('/catalog/product/{slug}', [App\Http\Controllers\CatalogController::class, 'product'])->name('catalog.product');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




Route::get('/basket/index', [App\Http\Controllers\BasketController::class, 'index'])->name('basket.index');
Route::get('/basket/checkout', [App\Http\Controllers\BasketController::class, 'checkout'])->name('basket.checkout');
Route::post('/basket/add/{id}', [App\Http\Controllers\BasketController::class, 'add'])->where('id', '[0-9]+')->name('basket.add');
Route::post('/basket/plus/{id}', [App\Http\Controllers\BasketController::class, 'plus'])->where('id', '[0-9]+')->name('basket.plus');
Route::post('/basket/minus/{id}', [App\Http\Controllers\BasketController::class, 'minus'])->where('id', '[0-9]+')->name('basket.minus');
// Route::post('/basket/remove/{id}', [App\Http\Controllers\BasketController::class, 'remove'])->where('id', '[0-9]+')->name('basket.remove');
// Route::post('/basket/clear', [App\Http\Controllers\BasketController::class, 'clear'])->name('basket.clear');