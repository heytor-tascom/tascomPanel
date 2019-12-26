@extends('layouts.app')

@section('content')
    @if(isset($formData))
    <form action="{{ route('painel.config.cliente.update', ['id' => $formData->id]) }}" method="POST">
    @else
    <form action="{{ route('painel.config.cliente.store') }}" method="POST">
    @endif
        @csrf
        <div class="w-100 mb-3">
            <a href="{{ route('painel.config.cliente') }}" class="btn btn-secondary"><i class="fas fa-chevron-left"></i>&nbsp; Voltar</a>
            <button type="submit" class="btn btn-secondary"><i class="fas fa-save text-success"></i>&nbsp; Salvar</button>
        </div>
        <div class="card shadow mb-3">
            <div class="card-header">
                Dados do Cliente
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="">Nome do cliente</label>
                            <input type="text" name="nm_cliente" class="form-control form-control-sm" value="{{ $formData->nm_cliente ?? old('nm_cliente') }}">
                            @if ($errors->has('nm_cliente'))
                            <span class="invalid-feedback" style="display: block" role="alert">
                                <strong>{{ $errors->first('nm_cliente') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="">CPF/CNPJ</label>
                            <input type="text" name="nr_cpf_cnpj" class="form-control form-control-sm" value="{{ $formData->nr_cpf_cnpj ?? old('nr_cpf_cnpj') }}">
                            @if ($errors->has('nr_cpf_cnpj'))
                            <span class="invalid-feedback" style="display: block" role="alert">
                                <strong>{{ $errors->first('nr_cpf_cnpj') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    @if(isset($formData))
                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="">Ativo?</label>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="ativo" class="custom-control-input" id="clienteAtivo" value="1" {{ (isset($formData->ativo) && $formData->ativo == 1) ? "checked" : null }}>
                                <label class="custom-control-label" for="clienteAtivo">&nbsp;</label>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="float-right w-25">
                <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="search" name="p_search" class="form-control form-control-alternative" placeholder="Digite para buscar o plano..." />
                </div>
            </div>
            <h2>Planos</h2>
            <p>Você pode selecionar os planos para o cliente</p>
        </div>

        <div class="py-3 px-2" style="width: 100%; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch;">
            @forelse ($planos as $plano)
            <div class="my-1 mr-3" style="display: inline-block; float: none; width: 400px;">
                <div class="card shadow border">
                    <div class="card-body py-2 px-3" style="white-space: normal;">
                        <h1 class="text-center text-primary">{{ $plano->nm_plano }}</h1>
                        <p class="text-center mb-3">
                            {{ $plano->ds_plano }}
                        </p>

                        <div class="text-center mb-3">
                            <small>R$</small>&nbsp;
                            <strong style="font-size: 35px">{{ number_format($plano->vl_plano, 2, ',', '.') }}</strong>
                            <small>/ {{ $plano->nr_periodo }} Dia(s)</small>
                        </div>

                        <p class="font-weight-bold mb-2">Produtos:</p>
                        <ul class="list-unstyled">
                        @forelse ($plano->produtos as $produto)
                            <li class="mb-2"><i class="fas fa-check text-success"></i>&nbsp; {{ $produto->produto->nm_produto }}</li>
                        @empty

                        @endforelse
                        </ul>

                        <div class="custom-control custom-radio mb-3">
                            <input type="radio" name="plano_id" class="custom-control-input" id="customRadio{{ $plano->id }}" value="{{ $plano->id }}">
                            <label class="custom-control-label" for="customRadio{{ $plano->id }}">Selecionar</label>
                        </div>

                    </div>
                </div>
            </div>
            @empty
            @endforelse
        </div>
    </form>
@endsection