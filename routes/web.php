<?php

Route::get('/', 'MicropostsController@index');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['middleware' => ['auth']], function () {
   Route::resource('users', 'UsersController', ['only' => ['index', 'show']]);
        // Route::post('users', 'UserFollowController@store')->name('user.follow');
        // Route::delete('users', 'UserFollowController@destroy')->name('user.unfollow');
        // Route::get('users', 'UsersController@followings')->name('users.followings');
        // Route::put('users', 'UsersController@followers')->name('users.followers');

   Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);
    Route::group(['prefix' => 'users/{id}'], function () {
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings', 'UsersController@followings')->name('users.followings');
        Route::get('followers', 'UsersController@followers')->name('users.followers');
    });

    Route::group(['prefix' => 'users/{id}'], function () {
        Route::delete('unfavorite', 'FavoriteController@destroy')->name('user.unfavorite');
        Route::get('favoritings', 'FavoriteController@favoritings')->name('users.favoritings');
        // Route::get('favorites', 'FavoriteController@destroy')->name('user.favorites');
    });

    Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);
});