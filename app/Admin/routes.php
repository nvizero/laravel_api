<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UsersController::class);
    $router->resource('products', ProductsController::class);
    $router->resource('categories', CategoryController::class);
    $router->resource('category_styles', CategoryStyleController::class);

    $router->resource('orders', OrderController::class);
    $router->resource('order_details', OrderDetailController::class);
});
