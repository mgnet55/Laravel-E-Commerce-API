<?php


use Illuminate\Http\Request;
use App\Models\ShippingCompany;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\ShippingcompanyController;

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
Route::get('products/category/{id}',[ProductController::class,'productsByCategory']);
//location-models
Route::apiResource('category',CategoryController::class);
Route::apiResource('governorate',GovernorateController::class);
Route::apiResource('city',CityController::class);

// Shipping
Route::get('shippingOrders/{id}',[ShippingcompanyController::class,'getOrders']);

// Profile
Route::get('myProfile',[UserController::class,'getProfile'])
    ->middleware('auth:sanctum');

// order
Route::get('orderItems/{id}',[OrderController::class,'getOrderDetails']);

//permissions
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::get('test',fn()=>'done')->middleware('auth:sanctum');

// Cart,Checkout
Route::get('cart', [\App\Http\Controllers\CartController::class, 'index'])
    ->middleware('auth:sanctum');
Route::put('cart', [\App\Http\Controllers\CartController::class, 'update'])
    ->middleware('auth:sanctum');
Route::post('cart/info', [\App\Http\Controllers\CartController::class, 'setCartInfo'])
    ->middleware('auth:sanctum');
Route::post('cart/{product}', [\App\Http\Controllers\CartController::class, 'addItem'])
    ->middleware('auth:sanctum');
Route::delete('cart/{product}', [\App\Http\Controllers\CartController::class, 'removeItem'])
    ->middleware('auth:sanctum');
Route::get('cart/info', [\App\Http\Controllers\CartController::class, 'getCartInfo'])
    ->middleware('auth:sanctum');
Route::post('checkout', [\App\Http\Controllers\CheckoutController::class, 'charge'])
    ->middleware('auth:sanctum');
Route::get('cart/items', [\App\Http\Controllers\CartController::class, 'getItemsNumber'])
    ->middleware('auth:sanctum');


