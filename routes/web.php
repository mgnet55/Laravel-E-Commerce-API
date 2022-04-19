<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
//Route::group(['middleware'=>'auth:sanctum'],function (){
//    Route::get('profiles/{file}', function($file) {
//        if (Storage::disk('profiles')->exists($file)) {return response()->file('profiles/'.$file);}
//        else return response(status:404);});
//});
//Route::get('profiles/{file}', function($file) {
//    if (Storage::disk('profiles')->exists($file)) {return response()->file('profiles/'.$file);}
//    else return response(status:404);
//})->middleware('auth:sanctum');

//Route::get('/', function () {
//    return view('welcome');
//});
