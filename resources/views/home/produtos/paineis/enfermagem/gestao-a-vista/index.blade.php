@extends('layouts.rhp.app')

@section('content')
@include('layouts.rhp.navbars.gestao-a-vista')
@inject('helpers', 'App\Http\Helpers\Helpers')
<div class="container-fluid">
    <div class="card mt-5 pt-4">
        <div class="card-body px-0 pt-4">
            <div class="w-100 mt-4 p-2"></div>
            <table class="table table-sm table-hover" id="tablePacientes">
                <thead>
                    <tr>
                        <th class="text-center" colspan="10"></th>
                        <th class="text-center bg-green-pastel" colspan="2">EVOLUÇÕES</th>
                        <th class="text-center bg-red-pastel" colspan="4">MEDICAÇÕES</th>
                        <th class="text-center bg-yellow-pastel">PROTOCOLOS</th>
                        <th class="text-center bg-purple-pastel" colspan="2">RISCOS</th>
                        <th class="text-center bg-teal-pastel" colspan="2">EXAMES</th>
                        <th class="text-center bg-pink-pastel" colspan="4">GERAL</th>
                    </tr>
                    <tr>
                        <th class="text-center">LEITO</th>
                        <th class="text-center">SETOR</th>
                        <th class="text-center">REGISTRO</th>
                        <th class="text-center">ATENDIMENTO</th>
                        <th>PACIENTE</th>
                        <th>NOME SOCIAL</th>
                        <th class="text-center">DATA DE<br>NASCIMENTO</th>
                        <th class="text-center">IDADE</th>
                        <th class="text-center">DIAS</th>
                        <th>MÉDICO</th>
                        <th class="text-center bg-green-pastel">MED</th>
                        <th class="text-center bg-green-pastel">ENF</th>
                        <th class="text-center bg-red-pastel">PRE</th>
                        <th class="text-center bg-red-pastel">APZ</th>
                        <th class="text-center bg-red-pastel">VLD</th>
                        <!-- <th class="text-center bg-red-pastel">DSP</th> -->
                        <th class="text-center bg-red-pastel">CHE</th>
                        <th class="text-center bg-yellow-pastel" style="width: 8%"></th>
                        <th class="text-center bg-purple-pastel">RQ</th>
                        <th class="text-center bg-purple-pastel">LPP</th>
                        <th class="text-center bg-teal-pastel">LAB</th>
                        <th class="text-center bg-teal-pastel">IMG</th>
                        <th class="text-center bg-pink-pastel">ALE</th>
                        <th class="text-center bg-pink-pastel">CCIH</th>
                        <th class="text-center bg-pink-pastel">PAR</th>
                        <th class="text-center bg-pink-pastel">ALTA MÉDICA</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($atendimentos))
                        @forelse ($atendimentos as $key => $atendimento)
                        @php
                        $idade = $helpers::getIdade(date("d/m/Y", strtotime($atendimento->paciente->dt_nascimento)));
                        $dias = $helpers::diffDate(date("d/m/Y", strtotime($atendimento->dt_atendimento)));
                        $nomeAbreviado = $helpers::abreviarNome($atendimento->paciente->nm_paciente);
                        @endphp
                        
                        @if($atendimento->tempo_alta > 60 && !$atendimento->dt_alta_medica)
                        <tr class="checagemAtrasada">
                        @else
                        <tr>
                        @endif
                            <!-- Leito -->
                            <td class="text-center">{{ $atendimento->ds_resumo }}</td>
                            <!-- Setor -->
                            <td class="text-center">{{ $atendimento->nm_setor }}</td>
                            <!-- Prontuário -->
                            <td class="text-center">{{ $atendimento->cd_paciente }}</td>
                            <!-- Atendimento -->
                            <td class="text-center">{{ $atendimento->cd_atendimento }}</td>
                            <!-- Nome do Paciente -->
                            <td>{{ $nomeAbreviado }}</td>
                            <!-- Nome Social -->
                            <td>{{ !empty($atendimento->paciente->nm_social) ? $helpers::abreviarNome($atendimento->paciente->nm_social) : '-' }}</td>
                            <!-- Data de Nascimento -->
                            <td class="text-center">{{ date("d/m/Y", strtotime($atendimento->paciente->dt_nascimento)) }}</td>
                            <!-- Idade -->
                            @if ($idade > 0)
                            <td class="text-center">{{ $idade }} ANOS</td>
                            @else
                            <td class="text-center">{{ $idade }} ANO</td>
                            @endif
                            <!-- Fim Idade -->
                            
                            <!-- Dias de Internação -->
                            <td class="text-center">{{ $dias }}</td>
                            
                            <!-- Nome do Médico(a) -->
                            <td>{{ $helpers::abreviarNome($atendimento->prestador->nm_prestador) }}</td>
                            
                            <!-- Evolução Médica -->
                            <td class="text-center bg-green-pastel">
                            @if ($atendimento->evo_med > 0)
                            <i class="material-icons text-success">check_circle_outline</i>
                            @else
                            <i class="material-icons text-danger">highlight_off</i>
                            @endif
                            </td>
                            
                            <!-- Evolução Enfermagem -->
                            <td class="text-center bg-green-pastel">
                            @if ($atendimento->evo_enf > 0)
                            <i class="material-icons text-success">check_circle_outline</i>
                            @else
                            <i class="material-icons text-danger">highlight_off</i>
                            @endif
                            </td>
                            
                            <!-- Prescrição -->
                            <td class="text-center bg-red-pastel">
                                @if($atendimento->pre_med > 0)
                                <i class="material-icons text-success">check_circle_outline</i>
                                @else
                                <i class="material-icons text-danger">highlight_off</i>
                                @endif
                            </td>
                            <!-- Fim Prescrição -->
                            
                            <!-- Aprazamento -->
                            <td class="text-center bg-red-pastel">
                                @if ($atendimento->aprazamento == 'N')
                                <!-- <i class="material-icons text-danger animated pulse infinite">error_outline</i> -->
                                <i class="material-icons text-danger">highlight_off</i>
                                @elseif ($atendimento->aprazamento == 'S')
                                <i class="material-icons text-success">check_circle_outline</i>
                                @else
                                @endif
                            </td>
                            
                            <!-- Avaliação -->
                            <td class="text-center bg-red-pastel">
                                @if ($atendimento->avfarmac > 0)
                                <i class="material-icons text-danger">highlight_off</i>
                                @else
                                <i class="material-icons text-success">check_circle_outline</i>
                                @endif
                            </td>
                            <!-- Fim Avaliação -->
                            
                            <!-- Dispensação -->
                            <!-- <td class="text-center bg-red-pastel">
                                @if ($atendimento->dispensacao > 0)
                                <i class="material-icons text-success">check_circle_outline</i>
                                @else
                                <i class="material-icons text-danger animated pulse infinite">error_outline</i>
                                @endif
                            </td>
                            -->
                            <!-- Checagem -->
                            <td class="text-center bg-red-pastel">
                                @if ($atendimento->checagem == 'A')
                                <i class="material-icons text-danger animated pulse infinite">error_outline</i>
                                @elseif ($atendimento->checagem == 'C')
                                <i class="material-icons text-warning">query_builder</i>
                                @elseif ($atendimento->checagem == 'F')
                                <i class="material-icons text-success">check_circle_outline</i>
                                @else
                                @endif
                            </td>
                            
                            <!-- Protocolos -->
                            <td class="text-center bg-yellow-pastel">
                                
                                <!-- SEPSE -->
                                @if (isset($atendimento->psepse->cd_etapa_protocolo) && $atendimento->psepse->cd_etapa_protocolo == 2)
                                <span class="badge" style="color: #fff; font-size: 1.05em; background: #{{ $atendimento->psepse->cor }}">{{ $atendimento->psepse->ds_sigla_protocolo }}</span>
                                @else
                                @endif
                                
                                <!-- SEPSE PED -->
                                @if (isset($atendimento->psepseped->cd_etapa_protocolo) && $atendimento->psepseped->cd_etapa_protocolo == 2)
                                <span class="badge" style="color: #fff; font-size: 1.05em; background: #{{ $atendimento->psepseped->cor }}">{{ $atendimento->psepseped->ds_sigla_protocolo }}</span>
                                @else
                                @endif
                                
                                <!-- TEV -->
                                @if (isset($atendimento->ptev->cd_etapa_protocolo) && $atendimento->ptev->cd_etapa_protocolo == 2)
                                <span class="badge" style="color: #fff; font-size: 1.05em; background: #{{ $atendimento->ptev->cor }}">{{ $atendimento->ptev->ds_sigla_protocolo }}</span>
                                @else
                                @endif
                                
                                <!-- TEV CIR-->
                                @if (isset($atendimento->ptevcir->cd_etapa_protocolo) && $atendimento->ptevcir->cd_etapa_protocolo == 2)
                                <span class="badge" style="color: #fff; font-size: 1.05em; background: #{{ $atendimento->ptevcir->cor }}">{{ $atendimento->ptevcir->ds_sigla_protocolo }}</span>
                                @else
                                @endif
                                
                                <!-- QUEDA -->
                                @if (isset($atendimento->pqueda->cd_etapa_protocolo) && $atendimento->pqueda->cd_etapa_protocolo == 2)
                                <span class="badge" style="color: #fff; font-size: 1.05em; background: #{{ $atendimento->pqueda->cor }}">{{ $atendimento->pqueda->ds_sigla_protocolo }}</span>
                                @else
                                @endif
                                
                                <!-- BRONCO -->
                                @if (isset($atendimento->pbronco->cd_etapa_protocolo) && $atendimento->pbronco->cd_etapa_protocolo == 2)
                                <span class="badge" style="color: #fff; font-size: 1.05em; background: #{{ $atendimento->pbronco->cor }}">{{ $atendimento->pbronco->ds_sigla_protocolo }}</span>
                                @else
                                @endif
                                
                                <!-- BRONCO NEO/PED-->
                                @if (isset($atendimento->pbronconeoped->cd_etapa_protocolo) && $atendimento->pbronconeoped->cd_etapa_protocolo == 2)
                                <span class="badge" style="color: #fff; font-size: 1.05em; background: #{{ $atendimento->pbronconeoped->cor }}">{{ $atendimento->pbronconeoped->ds_sigla_protocolo }}</span>
                                @else
                                @endif
                            </td>
                            
                            <!-- Risco de Queda -->
                            <td class="text-center bg-purple-pastel">
                                @if($atendimento->rq > 0)
                                <i class="material-icons text-danger animated pulse infinite">error_outline</i>
                                @endif
                            </td>
                            
                            <!-- Risco de LPP -->
                            <td class="text-center bg-purple-pastel">
                                @if($atendimento->rlpp > 0)
                                <i class="material-icons text-danger animated pulse infinite">error_outline</i>
                                @endif
                            </td>

                            <!-- Exame Laboratorial -->
                            <!-- Realizado -->
                            <td class="text-center bg-teal-pastel">
                                @if ($atendimento->exalab == 'R')
                                <!-- <td class="text-center"><i class="medical-icon-i-laboratory text-primary" style="font-size: 2em"></i></td> -->
                                <i class="material-icons text-success">assignment_turned_in</i>
                                <!-- Coletado -->
                                @elseif ($atendimento->exalab == 'C')
                                <i class="medical-icon-i-laboratory text-primary" style="font-size: 2em"></i>
                                <!-- Solicitado -->
                                @elseif ($atendimento->exalab == 'S')
                                <i class="material-icons text-warning">query_builder</i>
                                @else
                                @endif
                            </td>
                            <!-- Fim Exame Laboratorial -->
                            
                            <!-- Exame Imagem -->
                            <td class="text-center bg-teal-pastel">
                                @if($atendimento->exaimg == 'R')
                                <i class="medical-icon-i-imaging-root-category text-primary" style="font-size: 2em"></i>
                                @elseif ($atendimento->exaimg == 'L')
                                <i class="material-icons text-success">assignment_turned_in</i>
                                @elseif ($atendimento->exaimg == 'NR')
                                <i class="material-icons text-warning">query_builder</i>
                                @else
                                @endif
                            </td>
                            <!-- Fim Exame Imagem -->
                            
                            <!-- Alergias -->
                            <td class="text-center bg-pink-pastel">
                                @if($atendimento->alergia > 0)
                                <i class="material-icons">fiber_manual_record</i>
                                @else
                                @endif
                            </td>
                            <!-- Fim Alergias -->
                            
                            <!-- Classificação da CCIH -->
                            <td class="text-center bg-pink-pastel">
                                @if ($atendimento->precaucao)
                                <i class="material-icons" style="color: {{ $atendimento->precaucao }}">fiber_manual_record</i>
                                @else
                                @endif
                            </td>
                            <!-- Fim Classificação da CCIH -->
                            
                            <!-- Parecer -->
                            <td class="text-center bg-pink-pastel">
                                @if($atendimento->parecer > 0)
                                <span class="badge badge-primary" style="font-size: 1.15em">{{ $atendimento->parecer }}</span>
                                @endif
                            </td>
                            
                            <!-- Status do Paciente -->
                            
                            <!-- <td class="text-center"><i class="medical-icon-i-surgery text-primary" style="font-size: 2em"></i></td> -->
                            @if (!is_null($atendimento->dt_alta_medica))
                            <td class="text-center text-success bg-pink-pastel"><i class="medical-icon-i-outpatient" style="font-size: 2em"></i><br/>{{ $atendimento->dt_alta_medica }}</td>
                            @else
                            <td class="text-center bg-pink-pastel"></td>
                            @endif
                            <!-- Fim Status do Paciente -->
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center pt-3" colspan="25">Nenhum paciente encontrado</td>
                        </tr>
                        @endforelse
                    @else
                        <tr>
                            <td class="text-center pt-3" colspan="25"><i class="fas fa-info-circle"></i><br/>Selecione um setor</td>
                        </tr>
                    @endif
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
        }, 60 * 1000);
    });

    function setSetor(e) {
        let val = $(e).val();

        window.history.replaceState("", "Painel Farmacia Central", "?setores=" + val);
        // location.reload();
    }

    $('.selectpicker').on('hidden.bs.select', function(e, clickedIndex, isSelected, previousValue) {
        location.reload();
    });
</script>
@endpush