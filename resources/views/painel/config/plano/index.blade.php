@extends('layouts.app')

@section('content')
    @include('includes.alert')
    @inject('helpers', 'App\Http\Helpers\Helpers')
    <div class="w-100 mb-3">
        <a href="{{ route('painel.config.plano.create') }}" class="btn btn-secondary" title="Novo plano"><i class="fas fa-plus text-success"></i>&nbsp; Novo plano</a>
    </div>
    <div class="card shadow">
        <div class="card-header">
            <form action="{{ route('painel.config.plano') }}" method="get">
                <ul class="list-unstyled mb-0 float-right">
                    <li class="list-inline-item">
                        <div class="form-group mb-0">
                            <input type="search" name="s" class="form-control form-control-sm" placeholder="Pesquise aqui..." value="{{ app('request')->input('s') }}" autocomplete="off" />
                        </div>
                    </li>
                </ul>
            </form>
            Planos
        </div>
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead>
                    
                    <tr>
                        <th class="text-center" width="100">Cód</th>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Período</th>
                        <th>Produtos</th>
                        <th width="100" class="text-center">Ativo?</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($planos as $key => $plano)
                        <tr class="pointer" onclick="javascript:window.location = '{{ route("painel.config.plano.edit", ["id" => $plano->id]) }}'">
                            <td class="text-center">{{ $plano->id }}</td>
                            <td>{{ $plano->nm_plano }}</td>
                            <td>R$ {{ number_format($plano->vl_plano, 2, ',', '.') }}</td>
                            <td>{{ $plano->nr_periodo }} Dia(s)</td>
                            <td>
                                {{ count($plano->produtos) }}
                            </td>
                            <td class="text-center">{!! $helpers::activeIcon($plano->ativo) !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="mt-3">
                                    <i class="fas fa-clipboard-check fa-lg"></i>
                                    <p class="mb-0"><small>Nenhum plano cadastrado</small></p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @component('painel.components.pagination', ['lista' => $planos])
            @endcomponent
        </div>
    </div>
@endsection