@extends('layouts.app')

@section('content')
    @include('includes.alert')
    @if(isset($formData))
    <form action="{{ route('painel.config.plano.update', ['id' => $formData->id]) }}" method="POST">
    @else
    <form action="{{ route('painel.config.plano.store') }}" method="POST">
    @endif
        @csrf
        <div class="w-100 mb-3">
            <a href="{{ route('painel.config.plano') }}" class="btn btn-secondary"><i class="fas fa-chevron-left"></i>&nbsp; Voltar</a>
            <button type="submit" class="btn btn-secondary"><i class="fas fa-save text-success"></i>&nbsp; Salvar</button>
        </div>
        <div class="card shadow mb-3">
            <div class="card-header">
                Novo plano
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Nome do plano</label>
                            @if (old('nm_plano'))
                            <input type="text" name="nm_plano" class="form-control form-control-sm" value="{{ old('nm_plano') }}" />
                            @else
                            <input type="text" name="nm_plano" class="form-control form-control-sm" value="{{ isset($formData) ? $formData->nm_plano : null }}" />
                            @endif
                            @if ($errors->has('nm_plano'))
                            <span class="invalid-feedback" style="display: block" role="alert">
                                <strong>{{ $errors->first('nm_plano') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Valor (R$)</label>
                            <input type="text" name="vl_plano" class="form-control form-control-sm" value="{{ $formData->vl_plano ?? old('vl_plano') }}">
                            @if ($errors->has('vl_plano'))
                            <span class="invalid-feedback" style="display: block" role="alert">
                                <strong>{{ $errors->first('vl_plano') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Período (em dias)</label>
                            <input type="number" name="nr_periodo" class="form-control form-control-sm" value="{{ $formData->nr_periodo ?? old('nr_periodo') }}">
                            @if ($errors->has('nr_periodo'))
                            <span class="invalid-feedback" style="display: block" role="alert">
                                <strong>{{ $errors->first('nr_periodo') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    @if(isset($formData))
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="">Ativo?</label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="ativo" class="custom-control-input" id="planoAtivo" value="1" {{ (isset($formData->ativo) && $formData->ativo == 1) ? "checked" : null }}>
                                <label class="custom-control-label" for="planoAtivo">&nbsp;</label>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Fale um pouco sobre o plano:</label>
                            <textarea name="ds_plano" class="form-control form-control-sm">{{ $formData->ds_plano ?? old('ds_plano') }}</textarea>
                            @if ($errors->has('ds_plano'))
                            <span class="invalid-feedback" style="display: block" role="alert">
                                <strong>{{ $errors->first('ds_plano') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <h2>Produtos</h2>
                <p>Selecione abaixo os produtos que irão compor o plano</p>
                @if ($errors->has('produto_id'))
                <span class="invalid-feedback" style="display: block" role="alert">
                    <strong>{{ $errors->first('produto_id') }}</strong>
                </span>
                @endif
            </div>

            <div class="card-body bg-secondary pt-5">
                <div class="row">
                    @forelse($produtos as $produto)
                    <div class="col-md-6">
                        <div class="card mb-5 p-0 shadow-sm">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="text-left ml-3 mt--3">
                                        <span class="badge badge-success badge-fill shadow">{{ $produto->tipoProduto->nm_tipo_produto }}</span>
                                    </div>

                                    <div class="card-body mt-2 p-2">
                                        <strong>{{ $produto->nm_produto }}</strong>
                                    </div>
                                    <div class="card-body p-2">
                                        <p class="text-justify">
                                            <small class="font-weight-bold">Sobre o painel:</small>
                                            <br/>
                                            {{ $produto->ds_produto }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <label class="custom-toggle mt-5">
                                        @if(isset($formData) && gettype(array_search($produto->id, array_column(json_decode($formData->produtos, true), 'produto_id'))) == 'integer')
                                        <input type="checkbox" name="produto_id[]" value="{{ $produto->id }}" checked>
                                        @elseif(!is_null(old('produto_id')) && gettype(array_search($produto->id, old('produto_id'))) == 'integer')
                                        <input type="checkbox" name="produto_id[]" value="{{ $produto->id }}" checked>
                                        @else
                                        <input type="checkbox" name="produto_id[]" value="{{ $produto->id }}">
                                        @endif
                                        <span class="custom-toggle-slider rounded-circle"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </form>
@endsection