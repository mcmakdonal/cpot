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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

///////// Product Category /////////////////////////////////
Route::get('/category', 'CategoryController@list');
Route::get('/main_category', 'CategoryController@main_list');
Route::get('/sub_category/{mcat_id}', 'CategoryController@sub_list');
///////// Product Category /////////////////////////////////

//////// Product ///////////////////////////////////

Route::post('/product', 'ProductController@index');
Route::post('/product/create', 'ProductController@store');
Route::post('/product/{id}/update', 'ProductController@update');
Route::post('/product/{id}/delete', 'ProductController@destroy');
Route::get('/product/{id}/detail', 'ProductController@show');
// Route::get('/product/category/{id}', 'ProductController@product_cat');

// Route::post('/product/search', 'ProductController@search');

//////// Product ///////////////////////////////////

//////// Blog ///////////////////////////////////

Route::post('/blog', 'BlogController@index');
Route::post('/blog/create', 'BlogController@store');
Route::post('/blog/{id}/update', 'BlogController@update');
Route::post('/blog/{id}/delete', 'BlogController@destroy');
Route::get('/blog/{id}/detail', 'BlogController@show');

//////// Blog ///////////////////////////////////

//////// Blog Category ///////////////////////////////////

Route::get('/blog_main_category', 'BlogCategoryController@blog_main_list');
Route::get('/blog_sub_category/{bmc_id}', 'BlogCategoryController@blog_sub_list');

//////// Blog ///////////////////////////////////

//////// User /////////////////////////////////

Route::post('/user/check_email', 'UserController@check_email');
Route::post('/user/create', 'UserController@store');
Route::post('/user/check_login', 'UserController@check_login');
Route::post('/user/update', 'UserController@update');
Route::post('/user/delete', 'UserController@destroy');
Route::get('/user/detail', 'UserController@show');
Route::post('/user/forget-password', 'UserController@forget_password');

Route::post('/user/register_facebook', 'UserController@register_facebook');
//////// User /////////////////////////////////

/// Search Common ///
Route::post('/search_title_all', function (Request $request) {
    return [
        'status' => false,
        'message' => "Please use endpoint /search-all",
    ];
});
Route::post('/search_tag_all', function (Request $request) {
    return [
        'status' => false,
        'message' => "Please use endpoint /search-all",
    ];
});

Route::post('/search-all', 'IndexController@search');
/// Search Common ///

// Favorite //
Route::get('/favorite', 'FavoriteController@favorite');
Route::post('/favorite-all', 'FavoriteController@favorite_all');
Route::get('/favorite-product', 'FavoriteController@favorite_product');
Route::get('/favorite-blog', 'FavoriteController@favorite_blog');

Route::post('/favorite/like', 'FavoriteController@favorite_like');
Route::post('/favorite/unlike', 'FavoriteController@favorite_unlike');
// Favorite //

// Eva //
Route::get('/question', 'IndexController@question');
Route::post('/answer', 'IndexController@answer');
Route::get('/user-evaluation', 'IndexController@total_user_evaluation');
// Eva //

// activities //
Route::post('/activities', 'IndexController@activities');
// activities //

// Youtube Detail //
Route::get('/youtube/{id}/detail', 'IndexController@youtube');
// Youtube Detail //

// Ads & Wallpaer //
Route::get('/ads-image', 'ImagesController@ads_image');
Route::get('/background-image', 'ImagesController@background_image');
// Ads & Wallpaer //

// Province //
Route::get('/get-province', 'IndexController@province');
Route::get('/get-district/{id}', 'IndexController@distrcit');
Route::get('/get-sub-district/{id}', 'IndexController@sub_district');
// Province //

// New release //
Route::post('/new-release', 'IndexController@new_release');
// New release //

// Nof //
Route::post('/nof-token', 'IndexController@recive_token');
// Nof //


// Access Denind //
Route::get('/jwt', 'IndexController@jwt');
Route::get('/jwtdecode', 'IndexController@jwtdecode');
// Access Denind //
