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

// Route::get('/', function () {
//     return view('home.produtos.index');
// });	

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});

// Rotas não autenticadas dos produtos
Route::group(['namespace' => 'Home\Produtos', 'prefix' => 'produtos'], function () {
    Route::get('/', 'ProdutoController@index')->name('produtos');
});

// Rotas não autenticadas dos painéis
Route::group(['namespace' => 'Home\Produtos\Paineis\Enfermagem', 'prefix' => 'produtos/painel/enfermagem'], function () {
    Route::get('/checagem', 'PainelEnfChecagemController@index')->name('painel.enfermagem.checagem');
    Route::get('/checagem/{atendimentoId}/detalhes', 'PainelEnfChecagemController@details')->name('painel.enfermagem.checagem.detalhes');
});