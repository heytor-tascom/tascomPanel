@extends('layouts.rhp.app')

@section('content')
    @include('layouts.rhp.navbars.farmacia.central') 
    <div class="container-fluid">
        <div class="card mt-5 pt-4">
            <div class="card-body px-0 pt-4">
                <div class="w-100 mt-5"></div>
                <table class="table table-sm" id="tableSolicitacoes">
                    <thead>
                            
                            @if ($aba == 'avulsas' || $aba == 'devolucoes')
                                <tr>
                                    <th class="text-center">TIPO</th>
                                    <th class="text-center">SOLICITAÇÃO</th>
                                    <th>LEITO</th>
                                    <th class="text-center">ATD</th>
                                    <th>PACIENTE</th>
                                    <th class="text-center">DATA DE<br>NASCIMENTO</th>
                                    <th>SETOR / ESTOQUE</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">DATA/HORA</th>
                                    <th class="text-center">QR CODE</th>
                                </tr>
                            @else
                                <tr>
                                    <th class="text-center">TIPO</th>
                                    <th class="text-center">SOLICITAÇÃO</th>
                                    <th>LEITO</th>
                                    <th class="text-center">ATD</th>
                                    <th>PACIENTE</th>
                                    <th class="text-center">DATA DE<br>NASCIMENTO</th>
                                    <th>SETOR / ESTOQUE</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">1º NECESSIDADE</th>
                                    <th>TURNO</th>
                                    <th class="text-center">QR CODE</th>
                                </tr>
                            @endif  
                        
                    </thead>
                    <tbody>
                        @inject('helperTpSolic', 'App\Http\Helpers\TipoSolicitacaoHelpers')
                        @inject('helperSituacaoSolic', 'App\Http\Helpers\SituacaoSolicitacaoHelpers')
                        @inject('helperAvulsa', 'App\Http\Helpers\SolicitacaoAvulsaHelpers')
                        @inject('helperControlado', 'App\Http\Helpers\SolicitacaoControladosHelpers')
                        
                        @forelse ($lista as $toda)
                            
                            @if ($toda->sn_urgente == 'S')
                                @php $classLinha = 'text-danger'; @endphp 
                            @else
                                @php $classLinha = '';  @endphp 
                            @endif  
                        
                            <tr class="{{ $classLinha }}">
                                <td class="text-center" style="font-size:1.8em;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    @if (isset($toda->dt_alta_medica) && !is_null($toda->dt_alta_medica))
                                    &nbsp;<i class="fas fa-user-check" style="color:#00e089;"></i>
                                    @endif
                                </td>
                                <td class="text-center">{{$toda->cd_solsai_pro}}</td>
                                <td>{{ $toda->ds_leito }}</td>
                                <td class="text-center">{{ $toda->cd_atendimento }}</td>
                                <td>{{ $toda->nm_paciente }}</td>
                                <td class="text-center">{{ $toda->dt_nascimento }}</td>
                                <td>{{ $toda->nm_setor }}</td>
                                <td class="text-center" style="font-size:1.8em;">
                                    {!! $helperAvulsa::avulsaSolicitacao($toda->tp_origem_solicitacao) !!}
                                    {!! $helperTpSolic::tipoSolicitacao($toda->tp_solsai_pro) !!}
                                    {!! $helperControlado::controladosSolicitacao($toda->sn_pscotropico) !!}
                                    <img style="vertical-align: bottom;" src="{{asset("tascom")}}/img/icones/{{$helperSituacaoSolic::situacaoSolicitacao($toda->tp_situacao) }}.png">
                                    
                                </td>
                                <td class="text-center">{{ $toda->pri_necessidade }}</td>
                                @if ($aba == 'avulsas' || $aba == 'devolucoes')
                                
                                @else
                                    <td>{{ $toda->ds_turno }}</td>
                                @endif
                                
                                <td class="text-center"><img src="{{asset('tascom')}}/qrcode/gerar/php/qr_img.php?d={{ $toda->cd_solsai_pro }}" style="width:75px;"></td>
                            </tr>
                        @empty    

                            <tr>
                                <td colspan="11">

                                    <p class="text-center mt-3 text-muted">
                                        <i class="fas fa-box-open"></i>
                                        <br>
                                        Sem solicitações aqui no momento
                                    </p>

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
    $(function(){

    setInterval(function(){ 
       location.reload(); 
    }, {{ $tempoAtt }} );        
        
    $('#legendas').popover({
        trigger: 'focus',
        html: true
    })

    $('#{{ $aba }}').addClass('active');

        $(".selectpicker").selectpicker({
            noneSelectedText: "Selecione um setor",
            noneResultsText: "Nenhum setor encontrado",
            countSelectedText: "{0} Setores selecionados",
        })

        $(".selectpicker-estoque").selectpicker({
            noneSelectedText: "Selecione um estoque",
            noneResultsText: "Nenhum estoque encontrado",
            countSelectedText: "{0} Estoques selecionados",
        })
    });

    function getPacientes()
    {
        let idActive = $('.btn').filter('.active');
        let idActiveAtual = idActive[0].id;
        
        let setores = $("select[name=setores]").val();
        let estoque = $("select[name=estoque]").val();
        if (estoque == null){
            estoque = 16;
        }

        if (setores.length > 0 || setores > 0) {

            window.history.replaceState("", "Painel Farmacia Central", "?estoque="+estoque+"&setores="+setores+"&aba="+idActiveAtual);
        
        } else {
            // $("#lista-pacientes tbody").empty();
        }
    }

    function trocaMetodo(metodo)
    {
        let idActive = $('.btn').filter('.active');
        
        let idActiveAtual = idActive[0].id;

        $('#'+idActiveAtual).removeClass('active');
        $('#'+idActiveAtual).removeClass('active');

        let novoAtivo = $('#'+metodo).addClass('active');
        
        let setores = $("select[name=setores]").val();

        if (setores.length > 0) {

            window.history.replaceState("", "Painel Farmacia Central", "?estoque={{$estoque}}&setores="+setores+"&aba="+metodo);
            location.reload(); 
        
        } else {
            // $("#lista-pacientes tbody").empty();
        }
    }

    function searchPatient() {
        var input, filter, table, tr, td, i, txtValue;
        input   = document.getElementById("searchInput");
        filter  = input.value.toUpperCase();
        table   = document.getElementById("tableSolicitacoes");
        tr      = table.getElementsByTagName("tr");
        
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[4];
            
            if (td) {
                txtValue = td.textContent || td.innerText;
                
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }

    $('.selectpicker, .selectpicker-estoque').on('hidden.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        location.reload(); 
    });
</script>
@endpush


