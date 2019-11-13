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
        <div class="card shadow">
            <div class="card-header">
                Novo Cliente
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
    </form>
@endsection