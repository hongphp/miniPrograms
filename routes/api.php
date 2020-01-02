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
Route::group(['prefix' => 'admin/v1/'],function(){
    Route::any('login', 'AdminController@login');
    Route::group(['middleware' =>['AdminToken']],function(){
        Route::any('banner', 'BannerController@index');
        Route::any('logout', 'AdminController@logout');
    });

});
Route::group(['prefix' => 'api/v1/'],function(){
    Route::any('login', 'UserController@login');
    Route::any('user_password', 'UserController@update');
    Route::get('news_list', 'NewsController@newsList');
    Route::get('hot_list', 'NewsController@hotList');
    Route::get('get_comment', 'NewsController@getComment');
    Route::post('post_comment', 'NewsController@postComment');
});


