<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;


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


Route::get('/stocks/create', [App\Http\Controllers\StockController::class, 'create'])->name('stocks.create');
Route::post('/stocks/store', [App\Http\Controllers\StockController::class, 'store'])->name('stocks.store');
Route::get('/products', [App\Http\Controllers\ProductController::class, 'getProducts'])->name('products.get');
Route::get('/stocks/get-stock', [App\Http\Controllers\StockController::class, 'getStock'])->name('stocks.getStock');
Route::get('/lang/{locale}', [App\Http\Controllers\LanguageController::class, 'switchLanguage'])->name('switch.language');


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
