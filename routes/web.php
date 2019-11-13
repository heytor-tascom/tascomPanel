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

Route::get('/', 'Home\Produtos\ProdutoController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
	// Route::get('/', 'Home\Produtos\ProdutoController@index')->name('produtos');
});

// Rotas não autenticadas dos produtos
Route::group(['namespace' => 'Home\Produtos', 'prefix' => 'produtos'], function () {
	Route::post('/editarProdutos', 'ProdutoController@update')->name('editar_produtos');
	Route::get('/', 'ProdutoController@index')->name('produtos');
});

// Rotas não autenticadas dos painéis da enfermagem
Route::group(['namespace' => 'Home\Produtos\Paineis\Enfermagem', 'prefix' => 'produtos/painel/enfermagem'], function () {
    Route::get('/checagem', 'PainelEnfChecagemController@index')->name('painel.enfermagem.checagem');
    Route::get('/checagem/{atendimentoId}/detalhes', 'PainelEnfChecagemController@details')->name('painel.enfermagem.checagem.detalhes');
});

// Rotas não autenticadas dos painéis da farmacia 
Route::group(['namespace' => 'Home\Produtos\Paineis\Farmacia', 'prefix' => 'produtos/painel/farmacia'], function () {
	Route::get('/central', 'central\PainelFarmCentralController@index')->name('painel.farmacia.central');
	Route::get('/uti', 'uti\PainelFarmUtiController@index')->name('painel.farmacia.uti');
	Route::get('/oncologia', 'oncologia\PainelFarmOncoController@index')->name('painel.farmacia.oncologia');
});

/************************************************************
* 				Rotas do painel de administração			*
************************************************************/

//Clientes
Route::group(['middleware' => 'auth', 'namespace' => 'Painel', 'prefix' => 'painel/config/cliente'], function (){
	Route::get('/', 'ClienteController@index')->name('painel.config.cliente');
	Route::get('/create', 'ClienteController@create')->name('painel.config.cliente.create');
	Route::post('/store', 'ClienteController@store')->name('painel.config.cliente.store');
	Route::get('/{id}', ['uses' => 'ClienteController@view'])->name('painel.config.cliente.view');
	Route::post('/{id}/update', ['uses' => 'ClienteController@update'])->name('painel.config.cliente.update');
});

//Produto
Route::group(['middleware' => 'auth', 'namespace' => 'Painel', 'prefix' => 'painel/config/produto'], function (){
	Route::get('/', 'ProdutoController@index')->name('painel.config.produto');
	Route::get('/create', 'ProdutoController@create')->name('painel.config.produto.create');
	Route::post('/store', 'ProdutoController@store')->name('painel.config.produto.store');
	Route::get('/{id}', ['uses' => 'ProdutoController@view'])->name('painel.config.produto.view');
	Route::post('/{id}/update', ['uses' => 'ProdutoController@update'])->name('painel.config.produto.update');
});

//Licença
Route::group(['middleware' => 'auth', 'namespace' => 'Painel', 'prefix' => 'painel/config/licenca'], function (){
	Route::get('/', 'LicencaController@index')->name('painel.config.licenca');
	Route::get('/create', 'LicencaController@create')->name('painel.config.licenca.create');
	Route::post('/store', 'LicencaController@store')->name('painel.config.licenca.store');
	Route::get('/{id}', ['uses' => 'LicencaController@view'])->name('painel.config.licenca.view');
	Route::post('/{id}/update', ['uses' => 'LicencaController@update'])->name('painel.config.licenca.update');
});

//Plano
Route::group(['middleware' => 'auth', 'namespace' => 'Painel', 'prefix' => 'painel/config/plano'], function (){
	Route::get('/', 'PlanoController@index')->name('painel.config.plano');
	Route::get('/create', 'PlanoController@create')->name('painel.config.plano.create');
	Route::post('/store', 'PlanoController@store')->name('painel.config.plano.store');
	Route::get('/{id}', ['uses' => 'PlanoController@view'])->name('painel.config.plano.view');
	Route::post('/{id}/update', ['uses' => 'PlanoController@update'])->name('painel.config.plano.update');
});

