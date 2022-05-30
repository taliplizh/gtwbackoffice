<?php

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

Route::prefix('supply')->group(function() {
    Route::get('/', 'SupplyController@index');
    Route::get('create', 'SupplyController@create');
    Route::get('order', 'SupplyController@order');
    Route::get('demo', 'SupplyController@demo');

});
