<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'AuthController@login');
    Route::get('/logout', 'AuthController@logout');
    Route::get('/unauthorized', 'AuthController@unauthorized')->name('login');
});

Route::post('user/', 'UserController@store');

Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
    Route::get('/', 'UserController@index');
    Route::get('/{id}', 'UserController@show');
    Route::put('/{id}', 'UserController@update');
    Route::put('/{id}/Notifications', 'UserController@updateNotifications');
    Route::delete('/{id}', 'UserController@delete');
    Route::get('/{id}/myposts', 'UserController@myPosts');

    Route::get('/auth/me', 'UserController@me');
});

Route::group(['prefix' => 'post', 'middleware' => 'auth:api'], function () {
    Route::get('/', 'PostController@index');
    Route::get('/{id}', 'PostController@show');
    Route::post('/', 'PostController@store');
    Route::put('/{id}', 'PostController@update');
    Route::delete('/{id}', 'PostController@delete');
    Route::post('/{id}/Comment', 'PostController@storeComment');
});

Route::group(['prefix' => 'comment', 'middleware' => 'auth:api'], function () {
    Route::get('/{id}', 'CommentController@show');
    Route::put('/{id}', 'CommentController@update');
    Route::delete('/{id}', 'CommentController@delete');
});

Route::group(['prefix' => 'notification', 'middleware' => 'auth:api'], function () {
    Route::get('/', 'NotificationController@index');
    Route::get('/{id}', 'NotificationController@show');
    Route::delete('/{id}', 'NotificationController@delete');
});
