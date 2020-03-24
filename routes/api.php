<?php

use Illuminate\Http\Request;

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

Route::apiResources(['cart' => 'API\ProductInShoppingCartsController']);
Route::apiResources(['products' => 'API\ProductController']);
Route::post('cart/plus', 'API\ProductInShoppingCartsController@plus');
Route::post('cart/minus', 'API\ProductInShoppingCartsController@minus');
Route::post('checkout', 'API\OrdersController@store');

Route::group(['middleware' => 'auth:api'], function () {
	Route::apiResources(['users' => 'API\UserController']);
	Route::get('orders', 'API\OrdersController@index');
	Route::put('upload/file/{id}', 'UserController@uploadFile');
});

Route::group(['prefix' => 'auth'], function () {
	Route::post('login', 'Auth\LoginController@login');

	Route::group(['middleware' => 'auth:api'], function () {
		Route::get('logout', 'Auth\LoginController@logout');
	});
});