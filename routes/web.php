<?php

use App\Http\Controllers\MovieController;
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

Route::get('/', [MovieController::class, 'welcome']);
Route::get('/danh-sach/{type_slug}', [MovieController::class, 'moviesByType'])->where('type_slug', '.*');;
Route::get('/the-loai/{category_slug}', [MovieController::class, 'moviesByCategory'])->where('category_slug', '.*');
Route::get('/quoc-gia/{country_slug}', [MovieController::class, 'moviesByCountry'])->where('country_slug', '.*');
Route::get('/tim-kiem', [MovieController::class, 'search']);
Route::get('/phim/{slug}', [MovieController::class, 'movieDetail']);
Route::get('/xem-phim/{slug}/{episode_slug}', [MovieController::class, 'watchMovie']);
