@extends('layouts.app')

@section('content')
    @include('includes.alert')
    @inject('helpers', 'App\Http\Helpers\Helpers')
    <div class="w-100 mb-3">
        <a href="{{ route('painel.config.produto.create') }}" class="btn btn-secondary" title="Novo produto"><i class="fas fa-plus text-success"></i>&nbsp; Novo produto</a>
    </div>
    <div class="card shadow">
        <div class="card-header">
            <form action="{{ route('painel.config.produto') }}" method="get">
                <ul class="list-unstyled mb-0 float-right">
                    <li class="list-inline-item">
                        <div class="form-group mb-0">
                            <input type="search" name="s" class="form-control form-control-sm" placeholder="Pesquise aqui..." value="{{ app('request')->input('s') }}" autocomplete="off" />
                        </div>
                    </li>
                </ul>
            </form>
            Produtos
        </div>
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead>
                    
                    <tr>
                        <th class="text-center" width="100">Cód</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th width="200">Ambiente</th>
                        <th width="100" class="text-center">Ativo?</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produtos as $key => $produto)
                        <tr class="pointer" onclick="javascript:window.location = '{{ route("painel.config.produto.edit", ["id" => $produto->id]) }}'">
                            <td>{{ $produto->id }}</td>
                            <td>{{ $produto->nm_produto }}</td>
                            <td>{{ $produto->ds_produto }}</td>
                            <td>{{ $produto->ambiente->ds_ambiente }}</td>
                            <td class="text-center">{!! $helpers::activeIcon($produto->ativo) !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="mt-3">
                                    <i class="fas fa-box-open fa-lg"></i>
                                    <p class="mb-0"><small>Nenhum produto cadastrado</small></p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $produtos->links() }}
        </div>
    </div>
@endsection