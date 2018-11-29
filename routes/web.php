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
    if (!(\Cookie::get('ad_id') !== null)) {
        return view('login');
    } else{
        return redirect('/administrator');
    }
});

// login module //
Route::get('/backend-login', function () {
    if (!(\Cookie::get('ad_id') !== null)) {
        return view('login');
    } else{
        return redirect('/administrator');
    }
});
Route::post('/backend-login','AdministratorController@check_login');
Route::get('/backend-logout', function () {
    return redirect('/')->withCookie(Cookie::forget('ad_id'))
    ->withCookie(Cookie::forget('ad_firstname'));
});
// login module //

// admin module //
Route::resource('administrator','AdministratorController');
// admin module //

// product match module //
Route::get('/product-match','ProductMapController@index');
Route::get('/product-match/{id}/matching','ProductMapController@matching');
Route::post('/product-match/{id}','ProductMapController@store');
// product match module //

// test
Route::get('/ajax', function () {
    return view('ajax');
});
//