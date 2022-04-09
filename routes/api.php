<?php


use App\Http\Controllers\CityController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Products resource
Route::apiResource("/products",ProductController::class);

//location-models
Route::apiResource('category',CategoryController::class);
Route::apiResource('governorate',GovernorateController::class);
Route::apiResource('city',CityController::class);

//permissions
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::get('test',fn()=>'done')->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);

Route::get('cart', [\App\Http\Controllers\CartController::class, 'index'])
    ->middleware('auth:sanctum');
Route::get('cart/{product}/add', [\App\Http\Controllers\CartController::class, 'addItem'])
      ->middleware('auth:sanctum');
Route::get('cart/{product}/remove', [\App\Http\Controllers\CartController::class, 'removeItem'])
      ->middleware('auth:sanctum');
Route::post('cart/info', [\App\Http\Controllers\CartController::class, 'info'])
    ->middleware('auth:sanctum');
Route::post('checkout', [\App\Http\Controllers\CheckoutController::class, 'charge'])
    ->middleware('auth:sanctum');

