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

/*Route::get('/', function () {
    return view('welcome');
});*/


Route::get('test', 'TestController@test');

Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

Route::get('signup', 'UsersController@create')->name('signup');

Route::resource('users', 'UsersController');

/*Route::get('users', 'UsersController@index')->name('users.index');
Route::get('users/{user}', 'UsersController@show')->name('users.show');
Route::get('users/create', 'UsersController@create')->name('users.create');
Route::post('users', 'UsersController@store')->name('users.store');
Route::get('users/{user}/edit', 'UsersController@edit')->name('edit');
//Route::patch('users/{user}', 'UsersController@update')->name('update');
//Route::delete('users/{user}', 'UsersController@destroy')->name('destroy');*/

Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');

Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');
