@extends('layouts.rhp.app')

@section('content')
    @include('layouts.rhp.navbars.farmacia.oncologia') 
    <div class="container-fluid">
        <div class="card mt-5 pt-4">
            <div class="card-body px-0 pt-4">
                <div class="w-100 mt-5"></div>
                <table class="table table-sm" id="tableSolicitacoes">
                    <thead>
                        <tr>
                            <th class="text-center">Status</th>
                            <th>Setor</th>
                            <th>Leito</th>
                            <th>Registro</th>
                            <th>Atendimento</th>
                            <th>Paciente</th>
                            <th>Data Nascimento</th>
                            <th>Data Atendimento</th>
                            <th>Prescrição</th>
                            <th>Solicitação</th>
                            <th>Idade</th>
                            <th>Nome Social</th>
                            @if ($tipoVisualizacao == 'd8375b48751caf44e5c23d4b0dcc2984d6081784')
                            <th>Detalhes</th>
                            @endif
                        </tr> 
                    </thead>
                    <tbody>                        
                        @forelse ($solicitacoes as $solicitacao)
                        
                        @if ($solicitacao->fnc_itpremed == 2)
                           <tr style="color: #FF0000; font-weight:600; font-size:0.9em;">
                        @elseif ($solicitacao->fnc_itpremed == 1)
                            <tr style="color: #F1C40F; font-weight:600; font-size:0.9em;">
                        @else
                            <tr style="color: #34495e; font-weight:600; font-size:0.9em;">
                        @endif
                                <td class="text-center">
                                    @if ($solicitacao->fnc_itpremed == 2)
                                    <i class="material-icons" style="color: #FF0000; vertical-align:inherit;">alarm</i>
                                    @elseif ($solicitacao->fnc_itpremed == 1)
                                    <i class="material-icons" style="color: #F1C40F; vertical-align:inherit;">access_time</i>
                                    @else
                                    @endif

                                    @switch($solicitacao->status)
                                        @case('RECEB')
                                        <i class="material-icons" style="color:green; vertical-align: inherit;">beenhere</i>
                                        @break

                                        @case('BAI_PED')
                                        <i class="material-icons" style="vertical-align:inherit; color:#4285f4;">move_to_inbox</i>
                                        @break

                                        @case('MED_PREP')
                                        <i class="material-icons" style="vertical-align:inherit; color:#4285f4;">move_to_inbox</i>
                                        @break

                                        @case('VALID_FARM')
                                        <i class="material-icons" style="vertical-align:inherit; color:#4285f4;">move_to_inbox</i>
                                        @break

                                        @case('PUNCAO')
                                        <i class="material-icons" style="vertical-align:inherit; color:#4285f4;">move_to_inbox</i>
                                        @break

                                        @default
                                        <i class="material-icons" style="color:#bf4646; vertical-align: inherit;">error</i>
                                        @break
                                    @endswitch
                                </td>
                                <td>{{ $solicitacao->nm_setor }}</td>
                                <td>{{ $solicitacao->leito }}</td>
                                <td>{{ $solicitacao->cod_paciente }}</td>
                                <td>{{ $solicitacao->atendimento }}</td>
                                <td>{{ $solicitacao->nome_paciente }}</td>
                                <td>{{ $solicitacao->dt_nascimento }}</td>
                                <td>{{ $solicitacao->dt_atendimento }}</td>
                                <td>{{ $solicitacao->cd_pre_med }}</td>
                                <td>{{ $solicitacao->cd_solsai_pro }}</td>
                                <td>{{ $solicitacao->idade }}</td>
                                <td>{{ !empty($solicitacao->nm_social) ? $solicitacao->nm_social : '-' }}</td>
                                @if ($tipoVisualizacao == 'd8375b48751caf44e5c23d4b0dcc2984d6081784')
                                <td class="text-center"><a href="javascript:void(0)" data-toggle="modal" data-target="#modalDetalhes" onclick="viewDetails({cdAtendimento: '{{ $solicitacao->atendimento }}', nmPaciente: '{{ $solicitacao->nome_paciente }}', dtNascimento: '{{ $solicitacao->dt_nascimento }}', cdPreMed: '{{ $solicitacao->cd_pre_med }}', cdSolSaiPro: '{{ $solicitacao->cd_solsai_pro }}'})"><i class="fas fa-external-link-alt fa-lg text-black"></i></a></td>
                                @endif
                           </tr>
                        @empty    

                            <tr>
                                <td colspan="13">

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

    <!-- Modal -->
    <div class="modal fade" id="modalDetalhes" tabindex="-1" role="dialog" aria-labelledby="modalDetalhes" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">

                </div>
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

    function viewDetails(a) {
        console.table(a);

        $("#modalDetalhes div.modal-body").html(`<div class="text-center mt-2"><i class="fas fa-spinner fa-spin"></i><p>Carregando...</p></div>`);

        let url = '{{ route('painel.farmacia.oncologia.detalhes', ['atendimentoId' => "#atendimento#", 'preMedId' => "#premed#", 'solSaiProId' => "#solsaipro#"]) }}';
        url = url.replace("#atendimento#", a.cdAtendimento);
        url = url.replace("#premed#", a.cdPreMed);
        url = url.replace("#solsaipro#", a.cdSolSaiPro);
        
        $.ajax({
            type: "GET",
            url: url,
            data: {
                pacienteData: a,
            },
            success: function(response) {
                $("#modalDetalhes div.modal-body").html(response);
            }
        });
    }

    function getPacientes()
    {        
        let setores = $("select[name=setores]").val();
        
        if (setores.length > 0 || setores > 0) {

            window.history.replaceState("", "Painel Farmacia Oncologia", "?tipoVisualizacao={{ $tipoVisualizacao ?? 'guest' }}&setores="+setores);
        
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
            td = tr[i].getElementsByTagName("td")[5];
            
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


