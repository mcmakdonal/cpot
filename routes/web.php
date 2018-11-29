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

Route::get('/', function () {
    return view('login');
});

Route::get('/backend-login', function () {
    return view('login');
});
Route::post('/backend-login','AdministratorController@check_login');
Route::get('/backend-logout', function () {
    return redirect('/')->withCookie(Cookie::forget('ad_id'))
    ->withCookie(Cookie::forget('ad_firstname'));
});

Route::resource('administrator','AdministratorController');

Route::get('/product-match','ProductMapController@index');
Route::get('/product-match/{id}/matching','ProductMapController@matching');
Route::post('/product-match/{id}','ProductMapController@store');
