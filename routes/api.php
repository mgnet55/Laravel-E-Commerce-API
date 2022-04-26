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

Route::get('customers', [UserController::class,'customers']);
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
Route::post('{role}/login', [AuthController::class, 'login']);

Route::post('{role}/register', [AuthController::class, 'register'])->where('role', 'seller|customer');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('logout/all', [AuthController::class, 'logoutAllDevices']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('role', [AuthController::class, 'roles']);
});


//Seller Routes
Route::group(['prefix' => 'seller', 'middleware' => ['auth:sanctum', 'role:seller']], function () {
    Route::group(['prefix' => 'products'], function () {
        Route::get('/available', [SellerController::class, 'availableProducts']);
        Route::get('/unavailable', [SellerController::class, 'unavailableProducts']);
        Route::get('/zero-stock', [SellerController::class, 'zeroStock']);
        Route::get('/', [SellerController::class, 'products']);
        Route::post('/', [SellerController::class, 'createProduct']);
        Route::delete('{product}', [SellerController::class, 'deleteProduct']);
        Route::put('{product}', [SellerController::class, 'updateProduct']);
        Route::patch('{product}', [SellerController::class, 'updateProduct']);
        Route::get('{product}', [SellerController::class, 'showProduct']);
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

Route::group(['prefix' => 'customer', 'middleware' => ['auth:sanctum', 'role:customer']], function () {
    //orders
    Route::group(['prefix' => 'orders'], function () {
        Route::get('{order}', [CustomerController::class, 'orderDetails'])->middleware('auth:sanctum');
        Route::get('/', [CustomerController::class, 'orders'])->middleware('auth:sanctum');

    });
    // Cart
    Route::group(['prefix' => 'cart'], function () {
        Route::get('/', [CartController::class, 'index']);
        Route::put('/', [CartController::class, 'update']);
        Route::post('info', [CartController::class, 'setCartInfo']);
        Route::post('{product}', [CartController::class, 'addItem']);
        Route::delete('{product}', [CartController::class, 'removeItem']);
        Route::get('info', [CartController::class, 'getCartInfo']);
        Route::get('items', [CartController::class, 'getItemsNumber']);
    });
});


Route::post('checkout', [CheckoutController::class, 'charge'])->middleware('auth:sanctum');
