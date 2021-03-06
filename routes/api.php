<?php

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
// api/v1/images
Route::post('/v1/images', "TincyController@updateImage");
Route::get('/v1/productList', "ProductController@productList");
Route::get('/v1/allProducts', "ProductController@allProducts");
Route::get('/v1/product/{id}', "ProductController@detail");
Route::get('/v1/context/{slogan}', "Api\ContextController@detail");
Route::get('/v1/menus', "Api\ContextController@menus");

Route::group([
                'middleware' => 'api',
                'prefix' => 'auth'
            ], 
            function () {
    Route::get('/', 'AuthController@me');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('register', 'AuthController@register');

    Route::post('orders', 'OrderController@orderProduct');
});

Route::group([
                'middleware' => 'api',
                'prefix' => 'order'
            ], 
            function () {    
    Route::post('product', 'Api\OrderController@orderProduct');
    Route::post('addToCart', 'Api\OrderController@addToCart');
    Route::post('getCart', 'Api\OrderController@getCart');
    Route::post('delCartProd', 'Api\OrderController@delCartProduct');     
     
});