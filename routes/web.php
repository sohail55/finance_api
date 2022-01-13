<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/createList', [App\Http\Controllers\ListController::class, 'index'])->name('createList');
Route::get('/editList/{list_id}', [App\Http\Controllers\ListController::class, 'editList'])->name('editList');
Route::post('/saveWatchList', [App\Http\Controllers\ListController::class, 'create'])->name('saveWatchList');
Route::post('/updateWatchList/{id}', [App\Http\Controllers\ListController::class, 'update'])->name('updateWatchList');
Route::post('/search', [App\Http\Controllers\HomeController::class, 'SearchQuery'])->name('search');
Route::post('/saveResult', [App\Http\Controllers\HomeController::class, 'saveResult'])->name('saveResult');
