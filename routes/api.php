<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GovernorateController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
Route::apiResource("/products", ProductController::class);
Route::get('products/category/{id}', [ProductController::class, 'productsByCategory']);
//location-models
Route::apiResource('category', CategoryController::class);
Route::apiResource('governorate', GovernorateController::class);
Route::apiResource('city', CityController::class);

// Shipping
//Route::get('shippingOrders/{id}',[ShippingCompanyController::class,'getOrders']);

// Profile
Route::get('myProfile', [UserController::class, 'getProfile'])
    ->middleware('auth:sanctum');


// edit Profile
Route::post('editprofile', [UserController::class, 'updateProfile'])
    ->middleware('auth:sanctum');

// order
Route::get('orderItems/{id}', [OrderController::class, 'getOrderDetails']);

//permissions
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::group(['middleware'=>'auth:sanctum'],function(){
    Route::get('logout/all', [AuthController::class, 'logoutAllDevices']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('role', [AuthController::class, 'role']);
});

// Cart,Checkout
Route::group(['prefix' => 'cart', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [CartController::class, 'index']);
    Route::put('/', [CartController::class, 'update']);
    Route::post('info', [CartController::class, 'setCartInfo']);
    Route::post('{product}', [CartController::class, 'addItem']);
    Route::delete('{product}', [CartController::class, 'removeItem']);
    Route::get('info', [CartController::class, 'getCartInfo']);
    Route::get('items', [CartController::class, 'getItemsNumber']);
});

Route::post('checkout', [CheckoutController::class, 'charge'])->middleware('auth:sanctum');

Route::get('test', function () {
    return \App\Models\User::find(17);
});

//Seller Routes
Route::group(['prefix' => 'seller', 'middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [SellerController::class, 'products']);
        Route::post('/', [SellerController::class, 'createProduct']);
        Route::delete('{product}', [SellerController::class, 'deleteProduct']);
        Route::put('{product}', [SellerController::class, 'updateProduct']);
        Route::patch('{product}', [SellerController::class, 'updateProduct']);
    });
    Route::group(['prefix' => 'orders'], function () {
        Route::get('pending', [SellerController::class, 'pendingOrders']);
        Route::get('picked', [SellerController::class, 'pickedOrders']);
        Route::get('/', [SellerController::class, 'allOrders']);
    });
    Route::group(['prefix' => 'payments'], function () {
        Route::get('fulfilled', [SellerController::class, 'fulfilled']);
        Route::get('unfulfilled', [SellerController::class, 'unfulfilled']);
    });
});


Route::get('customer/orders', [CustomerController::class, 'getOrders'])->middleware('auth:sanctum');
Route::get('customer/orders/{order}', [CustomerController::class, 'orderDetails'])->middleware('auth:sanctum');
