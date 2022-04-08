<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', [AuthController::class, 'login']);


Route::get('cart', [\App\Http\Controllers\CartController::class, 'index'])
    ->middleware('auth:sanctum');
Route::get('cart/{product}/add', [\App\Http\Controllers\CartController::class, 'addItem'])
      ->middleware('auth:sanctum');
Route::get('cart/{product}/remove', [\App\Http\Controllers\CartController::class, 'removeItem'])
      ->middleware('auth:sanctum');
Route::post('cart/charge', [\App\Http\Controllers\CartController::class, 'charge'])
    ->middleware('auth:sanctum');
