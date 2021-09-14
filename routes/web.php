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
	Route::get('/devChecagem', 'dev\ChecagemController@index')->name('painel.enfermagem.checagem.dev');
	Route::get('/devChecagem/{atendimentoId}/detalhes', 'dev\ChecagemController@details')->name('painel.enfermagem.checagem.dev.detalhes');
	Route::get('/checagem/?tipoVisualizacao=d8375b48751caf44e5c23d4b0dcc2984d6081784', 'PainelEnfChecagemController@index')->name('painel.enfermagem.checagem.gestor');
    Route::get('/checagem/{atendimentoId}/detalhes', 'PainelEnfChecagemController@details')->name('painel.enfermagem.checagem.detalhes');
	Route::get('/assistencial', 'PainelEnfChecagemController@index')->name('painel.enfermagem.assistencial');
	Route::get('/gestao-a-vista', 'PainelEnfGestaoVista@index')->name('painel.enfermagem.gesta.vista');
	// Route::get('/gestao-a-vista-uti', ['uses' => 'PainelEnfGestaoVistaUti@index'])->name('painel.enfermagem.gesta.vista.uti.setores');
	Route::get('/gestao-a-vista-uti', 'PainelEnfGestaoVistaUti@index')->name('painel.enfermagem.gesta.vista.uti');
});

// Rotas não autenticadas dos painéis da farmacia
Route::group(['namespace' => 'Home\Produtos\Paineis\Farmacia', 'prefix' => 'produtos/painel/farmacia'], function () {
	Route::get('/central', 'central\PainelFarmCentralController@index')->name('painel.farmacia.central');
	Route::get('/realtime', 'central\PainelFarmCentralRealTimeController@index')->name('painel.farmacia.central.realtime');
    Route::get('/centralv2', 'central\PainelFarmCentralV2Controller@index')->name('painel.farmacia.central.v2');
	Route::get('/uti', 'uti\PainelFarmUtiController@index')->name('painel.farmacia.uti');
	Route::get('/oncologia', 'oncologia\PainelFarmOncoController@index')->name('painel.farmacia.oncologia');
	Route::get('/oncologia?tipoVisualizacao=d8375b48751caf44e5c23d4b0dcc2984d6081784', 'oncologia\PainelFarmOncoController@index')->name('painel.farmacia.oncologia.gestor');
	Route::get('/oncologia/{atendimentoId}/{preMedId}/{solSaiProId}/detalhes', 'oncologia\PainelFarmOncoController@details')->name('painel.farmacia.oncologia.detalhes');

    // paineis da oncologia, v2, foi deixado como SML porque ainda está em validação.
    Route::get('/oncologia-sml', 'oncologia\PainelFarmOncoSMLController@index')->name('painel.farmacia.oncologia.sml');
	Route::get('/oncologia-sml?tipoVisualizacao=d8375b48751caf44e5c23d4b0dcc2984d6081784', 'oncologia\PainelFarmOncoSMLController@index')->name('painel.farmacia.oncologia.gestor.sml');
	Route::get('/oncologia-sml/{atendimentoId}/{preMedId}/{solSaiProId}/detalhes', 'oncologia\PainelFarmOncoSMLController@details')->name('painel.farmacia.oncologia.detalhes.sml');

});

// Rotas não autenticadas dos painéis do Bloco cirurgico
Route::group(['namespace' => 'Home\Produtos\Paineis\BlocoCirurgico', 'prefix' => 'produtos/painel/blocoCirurgico'], function () {
	Route::get('/acompCirurgia', 'PainelAcompCirurgiaController@index')->name('painel.blocoCirurgico.acompCirurgia');
	Route::get('/acompCirurgiaObst', 'PainelAcompObstController@index')->name('painel.blocoCirurgico.acompCirurgiaObst');
});

// Rotas não autenticadas dos painéis da Urgência
Route::group(['namespace' => 'Home\Produtos\Paineis\Urgencia', 'prefix' => 'produtos/painel/urgencia'], function () {
	Route::get('/reavaliacao', 'PainelReavaliacaoController@index')->name('painel.urgencia.reavaliacao.setores');
	Route::get('/reavaliacao/{setorId?}', 'PainelReavaliacaoController@index')->name('painel.urgencia.reavaliacao');
});

// Rotas não autenticadas dos paineís do almoxarifado
Route::group(['namespace' => 'Home\Produtos\Paineis\Almoxarifado', 'prefix' => 'produtos/painel/almoxarifado'], function () {
	Route::get('/devolucoes', 'PainelDevolucaoController@index')->name('painel.almoxarifado.devolucoes');
});

// Rotas não autenticadas da integracao
Route::group(['namespace' => 'Integracao', 'prefix' => 'integracao/gerenciamento'], function () {
    // cadastro
	Route::get('/', 'GerenciamentoController@index')->name('integracao');
    Route::get('/getReturnDRG', 'GerenciamentoController@getReturnDRG')->name('getReturnDRG');
    Route::get('/searchCadastro', 'GerenciamentoController@searchCadastro')->name('searchCadastro');

    // custo
    Route::get('/getReturnDRGCusto', 'GerenciamentoController@getReturnDRGCusto')->name('getReturnDRGCusto');
    Route::get('/searchCusto', 'GerenciamentoController@searchCusto')->name('searchCadastroCusto');
    Route::post('/geraCusto', 'GerenciamentoController@geraCusto')->name('geraCusto');
    Route::get('/custo', 'GerenciamentoController@indexCusto')->name('custo');

    // admissão
    Route::get('/admissao', 'GerenciamentoController@admissao')->name('admissao');
    Route::get('/getReturnDRGAdmissao', 'GerenciamentoController@getReturnDRGAdmissao')->name('getReturnDRGAdmissao');
    Route::get('/searchCadastroAdmissao', 'GerenciamentoController@searchCadastroAdmissao')->name('searchCadastroAdmissao');

    // deparas

    Route::get('/depara', 'GerenciamentoController@depara')->name('depara');
    Route::post('/storeDepara', 'GerenciamentoController@storeDepara')->name('storeDepara');

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
	Route::get('/{id}', ['uses' => 'ProdutoController@edit'])->name('painel.config.produto.edit');
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
	Route::get('/{id}', ['uses' => 'PlanoController@edit'])->name('painel.config.plano.edit');
	Route::post('/{id}/update', ['uses' => 'PlanoController@update'])->name('painel.config.plano.update');
});

