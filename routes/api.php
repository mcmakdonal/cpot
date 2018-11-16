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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

///////// Category /////////////////////////////////
Route::get('/category', 'CategoryController@list');
Route::get('/main_category', 'CategoryController@main_list');
Route::get('/sub_category/{mcat_id}', 'CategoryController@sub_list');
///////// Category /////////////////////////////////

//////// Product ///////////////////////////////////
// Route::resource('product', 'ProductController');

Route::get('/product', 'ProductController@index');
Route::post('/product/create', 'ProductController@store');
Route::post('/product/{id}/update', 'ProductController@update');
Route::post('/product/{id}/delete', 'ProductController@destroy');
Route::get('/product/{id}/detail', 'ProductController@show');
Route::get('/product/category/{id}', 'ProductController@product_cat');

Route::post('/product/search', 'ProductController@search');

//////// Product ///////////////////////////////////

//////// Blog ///////////////////////////////////
// Route::resource('blog', 'BlogController');

Route::get('/blog', 'BlogController@index');
Route::post('/blog/create', 'BlogController@store');
Route::post('/blog/{id}/update', 'BlogController@update');
Route::post('/blog/{id}/delete', 'BlogController@destroy');
Route::get('/blog/{id}/detail', 'BlogController@show');

//////// Blog ///////////////////////////////////