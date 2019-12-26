@extends('layouts.rhp.app')

@section('content')
@include('layouts.rhp.navbars.gestao-a-vista')
@inject('helpers', 'App\Http\Helpers\Helpers')
<div class="container-fluid">
    <div class="card mt-5 pt-4">
        <div class="card-body px-0 pt-4">
            <div class="w-100 mt-5"></div>
            <table class="table table-sm table-hover" id="tablePacientes">
                <thead>
                    <tr>
                        <th class="text-center">LEITO</th>
                        <th class="text-center">REGISTRO</th>
                        <th class="text-center">ATENDIMENTO</th>
                        <th>PACIENTE</th>
                        <th>NOME SOCIAL</th>
                        <th class="text-center">DATA DE<br>NASCIMENTO</th>
                        <th class="text-center">IDADE</th>
                        <th class="text-center">DIAS</th>
                        <th>MÉDICO</th>
                        <th class="text-center">EVM</th>
                        <th class="text-center">EVE</th>
                        <th class="text-center">PRE</th>
                        <th class="text-center">APZ</th>
                        <th class="text-center">CHE</th>
                        <th class="text-center">PROTOCOLOS</th>
                        <th class="text-center">RQ</th>
                        <th class="text-center">LPP</th>
                        <th class="text-center">ALE</th>
                        <th class="text-center">CCIH</th>
                        <th class="text-center">LAB</th>
                        <th class="text-center">IMG</th>
                        <th class="text-center">PAR</th>
                        <th class="text-center">ALTA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($atendimentos as $key => $atendimento)
                    @php
                    $idade = $helpers::getIdade(date("d/m/Y", strtotime($atendimento->paciente->dt_nascimento)));
                    $dias = $helpers::diffDate(date("d/m/Y", strtotime($atendimento->dt_atendimento)));
                    $nomeAbreviado = $helpers::abreviarNome($atendimento->paciente->nm_paciente);
                    @endphp
                    
                    <tr>
                        <!-- Leito -->
                        <td class="text-center">{{ $atendimento->ds_resumo }}</td>
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
                        @if ($atendimento->evo_med > 0)
                        <td class="text-center"><i class="material-icons text-success">check_circle_outline</i></td>
                        @else
                        <td class="text-center"><i class="material-icons text-danger">highlight_off</i></td>
                        @endif
                        
                        <!-- Evolução Enfermagem -->
                        @if ($atendimento->evo_enf > 0)
                        <td class="text-center"><i class="material-icons text-success">check_circle_outline</i></td>
                        @else
                        <td class="text-center"><i class="material-icons text-danger">highlight_off</i></td>
                        @endif
                        
                        <!-- Prescrição -->
                        @if($atendimento->pre_med > 0)
                        <td class="text-center"><i class="material-icons text-success">check_circle_outline</i></td>
                        @else
                        <td class="text-center"><i class="material-icons text-danger">highlight_off</i></td>
                        @endif
                        <!-- Fim Prescrição -->
                        
                        <!-- Aprazamento -->
                        @if ($atendimento->aprazamento == 'N')
                        <td class="text-center"><i class="material-icons text-danger animated pulse infinite">error_outline</i></td>
                        @elseif ($atendimento->aprazamento == 'S')
                        <td class="text-center"><i class="material-icons text-success">check_circle_outline</i></td>
                        @else
                        <td class="text-center"></td>
                        @endif
                        
                        <!-- Checagem -->
                        @if ($atendimento->checagem == 'A')
                        <td class="text-center"><i class="material-icons text-danger animated pulse infinite">error_outline</i></td>
                        @elseif ($atendimento->checagem == 'C')
                        <td class="text-center"><i class="material-icons text-warning">query_builder</i></td>
                        @elseif ($atendimento->checagem == 'F')
                        <td class="text-center"><i class="material-icons text-success">check_circle_outline</i></td>
                        @else
                        <td class="text-center"></td>
                        @endif
                        
                        <!-- Protocolos -->
                        <td class="text-center">
                            
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
                        <td class="text-center">
                            @if($atendimento->rq > 0)
                            <i class="material-icons text-danger animated pulse infinite">error_outline</i>
                            @endif
                        </td>
                        
                        <!-- Risco de LPP -->
                        <td class="text-center">
                            @if($atendimento->rlpp > 0)
                            <i class="material-icons text-danger animated pulse infinite">error_outline</i>
                            @endif
                        </td>
                        
                        <!-- Alergias -->
                        @if($atendimento->alergia > 0)
                        <td class="text-center"><i class="material-icons">fiber_manual_record</i></td>
                        @else
                        <td class="text-center"></td>
                        @endif
                        <!-- Fim Alergias -->
                        
                        <!-- Classificação da CCIH -->
                        @if ($atendimento->precaucao)
                        <td class="text-center"><i class="material-icons" style="color: {{ $atendimento->precaucao }}">fiber_manual_record</i></td>
                        @else
                        <td class="text-center"></td>
                        @endif
                        <!-- Fim Classificação da CCIH -->

                        <!-- Exame Laboratorial -->
                        <!-- Realizado -->
                        @if ($atendimento->exalab == 'R')
                        <!-- <td class="text-center"><i class="medical-icon-i-laboratory text-primary" style="font-size: 2em"></i></td> -->
                        <td class="text-center"><i class="material-icons text-success">assignment_turned_in</i></td>
                        <!-- Coletado -->
                        @elseif ($atendimento->exalab == 'C')
                        <td class="text-center"><i class="medical-icon-i-laboratory text-primary" style="font-size: 2em"></i></td>
                        <!-- Solicitado -->
                        @elseif ($atendimento->exalab == 'S')
                        <td class="text-center"><i class="material-icons text-warning">query_builder</i></td>
                        @else
                        <td class="text-center"></td>
                        @endif
                        <!-- Fim Exame Laboratorial -->
                        
                        <!-- Exame Imagem -->
                        @if($atendimento->exaimg == 'R')
                        <td class="text-center"><i class="medical-icon-i-imaging-root-category text-primary" style="font-size: 2em"></i></td>
                        @elseif ($atendimento->exaimg == 'L')
                        <td class="text-center"><i class="material-icons text-success">assignment_turned_in</i></td>
                        @elseif ($atendimento->exaimg == 'NR')
                        <td class="text-center"><i class="material-icons text-warning">query_builder</i></td>
                        @else
                        <td class="text-center"></td>
                        @endif
                        <!-- Fim Exame Imagem -->
                        
                        <!-- Parecer -->
                        <td class="text-center">
                            @if($atendimento->parecer > 0)
                            <span class="badge badge-primary" style="font-size: 1.15em">{{ $atendimento->parecer }}</span>
                            @endif
                        </td>
                        
                        <!-- Status do Paciente -->
                        
                        <!-- <td class="text-center"><i class="medical-icon-i-surgery text-primary" style="font-size: 2em"></i></td> -->
                        @if (!is_null($atendimento->dt_alta_medica))
                        <td class="text-center"><i class="medical-icon-i-outpatient text-success" style="font-size: 2em"></i></td>
                        @else
                        <td class="text-center"></td>
                        @endif
                        <!-- Fim Status do Paciente -->
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center pt-3" colspan="23">Nenhum paciente encontrado</td>
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
        }, 60 * 1000);
    });
</script>
@endpush