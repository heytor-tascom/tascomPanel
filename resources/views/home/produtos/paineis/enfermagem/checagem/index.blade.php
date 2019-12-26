@extends('layouts.rhp.app')

@section('content')
@include('layouts.rhp.navbars.navbar')
@inject('helpers', 'App\Http\Helpers\Helpers')
<div class="container-fluid">
    <div class="card mt-5 pt-4">
        <div class="card-body px-0 pt-4">
            <div class="w-100 mt-5"></div>
            <table class="table table-sm table-hover" id="tablePacientes">
                <thead>
                    <tr>
                        <th class="text-center">STATUS</th>
                        <th>SETOR</th>
                        <th>LEITO</th>
                        <th class="text-center">REGISTRO</th>
                        <th class="text-center">ATD</th>
                        <th>PACIENTE</th>
                        <th class="text-center">DATA DE<br>NASCIMENTO</th>
                        <th class="text-center">DATA DE<br>ATENDIMENTO</th>
                        <th class="text-center">IDADE</th>
                        <th>NOME SOCIAL</th>
                        @if ($tipoVisualizacao == 'd8375b48751caf44e5c23d4b0dcc2984d6081784')
                        <th class="text-center">DETALHES</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($atendimentos as $atendimento)
                    @php
                    $idade = $helpers::getIdade(date("d/m/Y", strtotime($atendimento->paciente->dt_nascimento)));
                    @endphp
                    @if (isset($atendimento->status->total_atrasado) && $atendimento->status->total_atrasado > 0)
                    @if (isset($atendimento->status->total_atrasado_med) && $atendimento->status->total_atrasado_med > 0)
                    <tr class="checagemAtrasada">
                    @else
                    <tr>
                    @endif
                        <td class="text-center">
                            <i class="material-icons checagemAtrasada">alarm</i>
                            @if(isset($atendimento->status->dh_mvto_estoque) && !is_null($atendimento->status->dh_mvto_estoque))
                            <i class="material-icons mt--1" style="color: #4285f4;">move_to_inbox</i>
                            @elseif(isset($atendimento->status->dh_avaliacao) && !is_null($atendimento->status->dh_avaliacao))
                            <i class="material-icons mt--1" style="color: #665aff;">local_pharmacy</i>
                            @elseif(isset($atendimento->status->dh_aprazado) && !is_null($atendimento->status->dh_aprazado))
                            <i class="material-icons mt--1" style="color: #e67e22">access_time</i>
                            @endif
                        </td>
                    @elseif (isset($atendimento->status->total_ate_1h) && $atendimento->status->total_ate_1h >= 0)
                    @if (isset($atendimento->status->total_ate_1h_med) && $atendimento->status->total_ate_1h_med >= 0)
                    <tr class="checagemProxima">
                    @else
                    <tr>
                    @endif
                        <td class="text-center">
                            <i class="material-icons checagemProxima">info</i>
                            @if(isset($atendimento->status->dh_mvto_estoque) && !is_null($atendimento->status->dh_mvto_estoque))
                            <i class="material-icons mt--1" style="color: #4285f4;">move_to_inbox</i>
                            @elseif(isset($atendimento->status->dh_avaliacao) && !is_null($atendimento->status->dh_avaliacao))
                            <i class="material-icons mt--1" style="color: #665aff;">local_pharmacy</i>
                            @elseif(isset($atendimento->status->dh_aprazado) && !is_null($atendimento->status->dh_aprazado))
                            <i class="material-icons mt--1" style="color: #e67e22">access_time</i>
                            @endif
                        </td>
                    @else
                    <tr class="">
                        <td class="text-center"><i class="material-icons mt--1 text-success">check_circle_outline</i></td>
                    @endif
                        <td>{{ $atendimento->nm_setor }}</td>
                        <td>{{ $atendimento->ds_leito }}</td>
                        <td class="text-center">{{ $atendimento->cd_paciente }}</td>
                        <td class="text-center">{{ $atendimento->cd_atendimento }}</td>
                        <td>{{ $atendimento->paciente->nm_paciente }}</td>
                        <td class="text-center">{{ date("d/m/Y", strtotime($atendimento->paciente->dt_nascimento)) }}</td>
                        <td class="text-center">{{ date("d/m/Y", strtotime($atendimento->dt_atendimento)) }}</td>
                        @if ($idade > 0)
                        <td class="text-center">{{ $idade }} ANOS</td>
                        @else
                        <td class="text-center">{{ $idade }} ANO</td>
                        @endif
                        <td>{{ !empty($atendimento->paciente->nm_social) ? $atendimento->paciente->nm_social : '-' }}</td>
                        @if ($tipoVisualizacao == 'd8375b48751caf44e5c23d4b0dcc2984d6081784')
                        <td class="text-center"><a href="javascript:void(0)" data-toggle="modal" data-target="#modalDetalhes" onclick="viewDetails({cdAtendimento: '{{ $atendimento->cd_atendimento }}', nmPaciente: '{{ $atendimento->paciente->nm_paciente }}', dtNascimento: '{{ $atendimento->paciente->dt_nascimento }}'})"><i class="fas fa-external-link-alt fa-lg text-black"></i></a></td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center pt-3" colspan="11">Nenhum paciente encontrado</td>
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
    $(function() {
        setInterval(function(){
            console.log('refresh!');
            location.reload();
        }, {{ $refreshTime }});
    });

    function viewDetails(a) {
        console.table(a);

        $("#modalDetalhes div.modal-body").html(`<div class="text-center mt-2"><i class="fas fa-spinner fa-spin"></i><p>Carregando...</p></div>`);

        let url = '{{ route('painel.enfermagem.checagem.detalhes', ['atendimentoId' => "#atendimento#"]) }}';
        url = url.replace("#atendimento#", a.cdAtendimento);
        
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

    function getPacientes() {
        let setores = $("select[name=setores]").val();
        let url = window.location.href;
        url = new URL(url);
        let tipoVisualizacao = url.searchParams.get("tipoVisualizacao");
        let params = '';

        console.log(tipoVisualizacao);

        if (setores.length > 0) {
            params = "?setores=" + setores;
            params += (tipoVisualizacao) ? "&tipoVisualizacao="+tipoVisualizacao : '';
            window.history.replaceState("", "Painel Checagem", params);

        } else {
            // $("#lista-pacientes tbody").empty();
            params += (tipoVisualizacao) ? "?tipoVisualizacao="+tipoVisualizacao : '';
            window.history.replaceState("", "Painel Checagem", params);
        }
    }

    function searchPatient() {
        var input, filter, table, tr, td, i, txtValue;
        input   = document.getElementById("searchInput");
        filter  = input.value.toUpperCase();
        table   = document.getElementById("tablePacientes");
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

    $('.selectpicker').on('hidden.bs.select', function(e, clickedIndex, isSelected, previousValue) {
        location.reload();
    });
</script>
@endpush