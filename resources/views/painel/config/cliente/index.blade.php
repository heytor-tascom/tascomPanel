@extends('layouts.app')

@section('content')
    @include('includes.alert')
    @inject('helpers', 'App\Http\Helpers\Helpers')
    <div class="w-100 mb-3">
        <a href="{{ route('painel.config.cliente.create') }}" class="btn btn-secondary" title="Novo cliente"><i class="fas fa-plus text-success"></i>&nbsp; Novo cliente</a>
    </div>
    <div class="card shadow">
        <div class="card-header">
            <form action="{{ route('painel.config.cliente') }}" method="get">
                <ul class="list-unstyled mb-0 float-right">
                    <li class="list-inline-item">
                        <div class="form-group mb-0">
                            <input type="search" name="s" class="form-control form-control-sm" placeholder="Pesquise aqui..." />
                        </div>
                    </li>
                </ul>
            </form>
            Clientes
        </div>
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead>
                    
                    <tr>
                        <th class="text-center" width="100">Cód</th>
                        <th>Nome</th>
                        <th width="200">CPF/CNPJ</th>
                        <th width="100" class="text-center">Ativo?</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $key => $cliente)
                        <tr onclick="javascript:window.location = '{{ route("painel.config.cliente.view", ["id" => $cliente->id]) }}'">
                            <td>{{ $cliente->id }}</td>
                            <td>{{ $cliente->nm_cliente }}</td>
                            <td>{{ $cliente->nr_cpf_cnpj }}</td>
                            <td class="text-center">{!! $helpers::activeIcon($cliente->ativo) !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="mt-3">
                                    <i class="fas fa-user-slash fa-lg"></i>
                                    <p class="mb-0"><small>Nenhum cliente cadastrado</small></p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $clientes->links() }}
        </div>
    </div>
@endsection