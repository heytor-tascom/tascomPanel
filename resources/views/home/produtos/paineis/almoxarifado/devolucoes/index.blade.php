@extends('layouts.rhp.app')

@section('content')
@include('layouts.rhp.navbars.almoxarifado.devolucoes')
@inject('helpers', 'App\Http\Helpers\Helpers')
<div class="container-fluid">
    <div class="card mt-5 pt-4">
        <div class="card-body px-0 pt-4">
            <div class="w-100 mt-4 p-2"></div>
            <table class="table table-hover mb-0" id="tableSolicitacoes" style="font-size: 1.4em">
                <thead>
                    <tr>
                        <th class="text-center bg-red-pastel" style="width: 250px">Cód. Movimentação</th>
                        <th class="text-center bg-teal-pastel" style="width: 250px">Cód. Transferência</th>
                        <th>Estoque</th>
                        <th>Setor</th>
                        <th>Estoque Destino</th>
                        <th class="text-center" style="width: 250px">Data/Hora Saída</th>
                        <th class="text-center" style="width: 250px">Usuário Devolução</th>
                        <th class="text-center" style="width: 250px">Usuário Transferência</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($solicitacoes as $solicitacao)
                    <tr>
                        <td class="p-2 text-center bg-red-pastel">{{ $solicitacao->cd_mvto_estoque_me }}</td>
                        <td class="p-2 text-center bg-teal-pastel">{{ $solicitacao->cd_mvto_estoquedev }}</td>
                        <td class="p-2">{{ $solicitacao->cd_estoque_me }} - {{ $solicitacao->ds_estoque_me }}</td>
                        <td class="p-2">{{ $solicitacao->cd_setor_me }} - {{ $solicitacao->nm_setor_me }}</td>
                        <td class="p-2">{{ $solicitacao->cd_estoque_destino }} - {{ $solicitacao->ds_estoque_destino }}</td>
                        <td class="p-2 text-center">{{ $solicitacao->dh_mvto_estoque_me }}</td>
                        <td class="p-2 text-center">{{ $solicitacao->cd_usuario }}</td>
                        <td class="p-2 text-center">{{ $solicitacao->cd_usuariodev }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center" colspan="6">
                            <i class="fas fa-info-circle"></i>
                            <br />
                            Nenhuma devolução até o momento
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        setInterval(function(){
            location.reload();
        }, {{ $tempoAtt }});
    });
</script>
@endpush