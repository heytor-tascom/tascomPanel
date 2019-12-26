@extends('layouts.app')

@section('content')
    @if(isset($formData))
    <form action="{{ route('painel.config.produto.update', ['id' => $formData->id]) }}" method="POST">
    @else
    <form action="{{ route('painel.config.produto.store') }}" method="POST">
    @endif
        @csrf
        <div class="w-100 mb-3">
            <a href="{{ route('painel.config.produto') }}" class="btn btn-secondary"><i class="fas fa-chevron-left"></i>&nbsp; Voltar</a>
            <button type="submit" class="btn btn-secondary"><i class="fas fa-save text-success"></i>&nbsp; Salvar</button>
        </div>
        <div class="card shadow">
            <div class="card-header">
                Novo produto
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Nome do produto</label>
                            <input type="text" name="nm_produto" class="form-control form-control-sm" value="{{ $formData->nm_produto ?? old('nm_produto') }}">
                            @if ($errors->has('nm_produto'))
                            <span class="invalid-feedback" style="display: block" role="alert">
                                <strong>{{ $errors->first('nm_produto') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">Tipo</label>
                            <select name="tipo_produto_id" id="" class="form-control form-control-sm">
                                <option value=""></option>
                                @forelse ($tiposProduto as $tipoProduto)
                                @if ($tipoProduto->id == old('tipo_produto_id'))
                                <option value="{{ $tipoProduto->id }}" selected>{{ $tipoProduto->nm_tipo_produto }}</option>
                                @elseif (isset($formData) && $tipoProduto->id == $formData->tipoProduto->id)
                                <option value="{{ $tipoProduto->id }}" selected>{{ $tipoProduto->nm_tipo_produto }}</option>
                                @else
                                <option value="{{ $tipoProduto->id }}">{{ $tipoProduto->nm_tipo_produto }}</option>
                                @endif
                                @empty
                                    
                                @endforelse
                            </select>
                            @if ($errors->has('tipo_produto_id'))
                            <span class="invalid-feedback" style="display: block" role="alert">
                                <strong>{{ $errors->first('tipo_produto_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Ambiente</label>
                            <select name="ambiente_id" id="" class="form-control form-control-sm">
                                <option value=""></option>
                                @forelse ($ambientes as $ambiente)
                                @if ($ambiente->id == old('ambiente_id'))
                                <option value="{{ $ambiente->id }}" selected>{{ $ambiente->ds_ambiente }}</option>
                                @elseif (isset($formData) && $ambiente->id == $formData->ambiente->id)
                                <option value="{{ $ambiente->id }}" selected>{{ $ambiente->ds_ambiente }}</option>
                                @else
                                <option value="{{ $ambiente->id }}">{{ $ambiente->ds_ambiente }}</option>
                                @endif
                                @empty
                                    
                                @endforelse
                            </select>
                            @if ($errors->has('ambiente_id'))
                            <span class="invalid-feedback" style="display: block" role="alert">
                                <strong>{{ $errors->first('ambiente_id') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    @if(isset($formData))
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="">Ativo?</label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="ativo" class="custom-control-input" id="produtoAtivo" value="1" {{ (isset($formData->ativo) && $formData->ativo == 1) ? "checked" : null }}>
                                <label class="custom-control-label" for="produtoAtivo">&nbsp;</label>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Descrição do produto</label>
                            <textarea name="ds_produto" id="" rows="5" class="form-control">{{ $formData->ds_produto ?? old('ds_produto') }}</textarea>
                            @if ($errors->has('ds_produto'))
                            <span class="invalid-feedback" style="display: block" role="alert">
                                <strong>{{ $errors->first('ds_produto') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection