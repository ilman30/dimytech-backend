<?php

use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\PaymentMethod\PaymentMethodController;
use App\Http\Controllers\Product\ProductController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    Route::group(['prefix' => 'product'], function () {
        Route::get('read',  [ProductController::class, 'read']);
    });
    Route::group(['prefix' => 'payment-method'], function () {
        Route::get('read',  [PaymentMethodController::class, 'read']);
    });
    Route::group(['prefix' => 'order'], function () {
        Route::post('create',  [OrderController::class, 'create']);
    });
});
