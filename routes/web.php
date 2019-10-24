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
    return view('welcome');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});

Route::group(['namespace' => 'Painel', 'prefix' => 'painel'], function () {
    Route::get('/checagem', 'Painel\ChecagemController@index')->name('painel.checagem');
});

<<<<<<< HEAD
Route::get('/teste');
=======
Route::get('/marlus', 'Painel\ChecagemController@index')->name('painel.checagem');



>>>>>>> ec062feb17d516b77defa5d46c94af54513a3279
