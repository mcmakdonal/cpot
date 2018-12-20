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
    } else {
        return redirect('/administrator');
    }
});

// login module //
Route::get('/backend-login', function () {
    if (!(\Cookie::get('ad_id') !== null)) {
        return view('login');
    } else {
        return redirect('/administrator');
    }
});
Route::post('/backend-login', 'AdministratorController@check_login');
Route::get('/backend-logout', function () {
    return redirect('/')->withCookie(Cookie::forget('ad_id'))
        ->withCookie(Cookie::forget('ad_firstname'));
});
// login module //

// admin module //
Route::resource('administrator', 'AdministratorController');

Route::resource('evaluation', 'EvaluationController');
Route::post('/evaluation/active', 'EvaluationController@active');
Route::post('/evaluation/unactive', 'EvaluationController@unactive');
// admin module //

// product match module //
Route::get('/product-match', 'ProductMapController@index');
Route::get('/product-match/{id}/matching', 'ProductMapController@matching');
Route::post('/product-match/{id}', 'ProductMapController@store');
// product match module //

// image mobile //
Route::get('/ads', 'ImagesController@ads');
Route::get('/background', 'ImagesController@background');
Route::post('/image-store', 'ImagesController@store');
Route::post('/image-active', 'ImagesController@active');
Route::post('/image-unactive', 'ImagesController@unactive');
Route::delete('/image-destroy/{id}', 'ImagesController@destroy');
// image mobile //

// report //
Route::get('/report', 'ReportController@evaluation');
Route::get('/report/evaluation', 'ReportController@evaluation');
Route::get('/report/user', 'ReportController@user');
Route::get('/report/tag', 'ReportController@tag');
Route::get('/report/share', 'ReportController@share');
// report //

// image mobile //
Route::get('/ads', 'ImagesController@ads');
Route::get('/background', 'ImagesController@background');
Route::post('/image-store', 'ImagesController@store');
Route::post('/image-active', 'ImagesController@active');
Route::post('/image-unactive', 'ImagesController@unactive');
Route::delete('/image-destroy/{id}', 'ImagesController@destroy');
// image mobile //

// test
Route::get('/ajax', function () {
    return view('ajax');
});
Route::get('/jwt', 'TestController@jwt');
Route::get('/jwtdecode', 'TestController@jwtdecode');

// Route::get('/product-excel', 'TestController@product');
// Route::get('/blog-excel', 'TestController@blog');
// Route::get('/insert-p-t-s','TestController@insert_p_t_s');
// Route::get('/update-s-t-p','TestController@update_s_t_p');
//
