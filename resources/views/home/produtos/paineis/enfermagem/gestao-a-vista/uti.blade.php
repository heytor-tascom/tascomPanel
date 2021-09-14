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
                        <th class="text-center" rowspan="2" width="80">LEITO</th>
                        <th class="text-center" rowspan="2">SETOR</th>
                        <th class="text-center" rowspan="2">REGISTRO</th>
                        <th class="text-center" rowspan="2">ATENDIMENTO</th>
                        <th rowspan="2" width="140">PACIENTE</th>
                        <th rowspan="2" width="140">NOME SOCIAL</th>
                        <th rowspan="2" class="text-center">DATA DE<br>NASCIMENTO</th>
                        <!-- <th class="text-center">IDADE</th> -->
                        <!-- <th class="text-center">DIAS</th> -->
                        <!-- <th>MÉDICO</th> -->
                        <th class="text-center bg-red-pastel" colspan="5">MEDICAÇÃO</th>
                        <th class="text-center bg-blue-pastel" colspan="3">LABORATÓRIO</th>
                        <th class="text-center bg-teal-pastel" colspan="3">IMAGENS</th>
                        <th class="text-center bg-pink-pastel" colspan="2">ALTA</th>
                    </tr>
                    <tr>
                        <!-- Medicação -->
                        <th class="text-center bg-red-pastel">PRESCRITA</th>
                        <th class="text-center bg-red-pastel">APRAZAMENTO</th>
                        <th class="text-center bg-red-pastel">VALIDADA</th>
                        <th class="text-center bg-red-pastel">DISPENSADA</th>
                        <th class="text-center bg-red-pastel">CHECADA</th>
                        <!-- Laboratório -->
                        <th class="text-center bg-blue-pastel">EXAME SOLICITADO</th>
                        <th class="text-center bg-blue-pastel">EXAME COLETADO</th>
                        <th class="text-center bg-blue-pastel">EXAME LIBERADO</th>
                        <!-- Imagens -->
                        <th class="text-center bg-teal-pastel">IMAGEM SOLICITADA</th>
                        <th class="text-center bg-teal-pastel">IMAGEM REALIZADA</th>
                        <th class="text-center bg-teal-pastel">IMAGEM LIBERADA</th>
                        <!-- Alta -->
                        {{-- <th class="text-center bg-pink-pastel">ALTA MÉDICA</th> --}}
                        <th class="text-center bg-pink-pastel">LEITO SOLICITADO</th>
                        <th class="text-center bg-pink-pastel">LEITO LIBERADO</th>
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

                        <tr>
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
                            <!-- @if ($idade > 0) -->
                            <!-- <td class="text-center">{{ $idade }} ANOS</td> -->
                            <!-- @else -->
                            <!-- <td class="text-center">{{ $idade }} ANO</td> -->
                            <!-- @endif -->
                            <!-- Fim Idade -->

                            <!-- Dias de Internação -->
                            <!-- <td class="text-center">{{ $dias }}</td> -->

                            <!-- Nome do Médico(a) -->
                            <!-- <td>{{ $helpers::abreviarNome($atendimento->prestador->nm_prestador) }}</td> -->

                            <!-- Prescrição -->
                            @if($atendimento->pre_med > 0)
                            <td class="text-center bg-red-pastel"><i class="material-icons text-success">check_circle_outline</i></td>
                            @else
                            <td class="text-center bg-red-pastel"><i class="material-icons text-danger">highlight_off</i></td>
                            @endif
                            <!-- Fim Prescrição -->

                            <!-- Aprazamento -->
                            @if($atendimento->aprazamento == 'S')
                            <td class="text-center bg-red-pastel"><i class="material-icons text-success">check_circle_outline</i></td>
                            @elseif($atendimento->aprazamento == 'N')
                            <td class="text-center bg-red-pastel"><i class="material-icons text-danger">highlight_off</i></td>
                            @else
                            <td class="text-center bg-red-pastel"><i class="material-icons text-success">check_circle_outline</i></td>
                            @endif
                            <!-- Fim Aprazamento -->

                            <!-- Avaliação -->
                            @if ($atendimento->avfarmac > 0)
                            <td class="text-center bg-red-pastel"><i class="material-icons text-danger">highlight_off</i></td>
                            @else
                            <td class="text-center bg-red-pastel"><i class="material-icons text-success">check_circle_outline</i></td>
                            @endif
                            <!-- Fim Avaliação -->

                            <!-- Dispensação -->
                            @if ($atendimento->dispensacao > 0)
                            <td class="text-center bg-red-pastel"><i class="material-icons text-success">check_circle_outline</i></td>
                            @else
                            <td class="text-center bg-red-pastel"><i class="material-icons text-danger">highlight_off</i></td>
                            @endif
                            <!-- Fim Dispensação -->

                            <!-- Checagem -->
                            @if ($atendimento->checagem == 'A')
                            <td class="text-center bg-red-pastel"><i class="material-icons text-danger animated pulse infinite">error_outline</i></td>
                            @elseif ($atendimento->checagem == 'C')
                            <td class="text-center bg-red-pastel"><i class="material-icons text-warning">query_builder</i></td>
                            @elseif ($atendimento->checagem == 'F')
                            <td class="text-center bg-red-pastel"><i class="material-icons text-success">check_circle_outline</i></td>
                            @else
                            <td class="text-center bg-red-pastel"></td>
                            @endif
                            <!-- Fim Checagem -->

                            <!-- Exame Laboratorial -->
                            <!-- Realizado -->
                            @if ($atendimento->exalab == 'R')
                            <!-- <td class="text-center"><i class="medical-icon-i-laboratory text-primary" style="font-size: 2em"></i></td> -->
                            <!-- Solicitação -->
                            <td class="text-center bg-blue-pastel"><i class="material-icons text-success">check_circle_outline</i></td>
                            <!-- Coleta -->
                            <td class="text-center bg-blue-pastel"><i class="medical-icon-i-laboratory text-primary" style="font-size: 2em"></i></td>
                            <!-- Liberado -->
                            <td class="text-center bg-blue-pastel"><i class="material-icons text-success">assignment_turned_in</i></td>

                            <!-- Coletado -->
                            @elseif ($atendimento->exalab == 'C')
                            <td class="text-center bg-blue-pastel"><i class="material-icons text-success">check_circle_outline</i></td>
                            <td class="text-center bg-blue-pastel"><i class="medical-icon-i-laboratory text-primary" style="font-size: 2em"></i></td>
                            <td class="text-center bg-blue-pastel"></td>

                            <!-- Solicitado -->
                            @elseif ($atendimento->exalab == 'S')
                            <td class="text-center bg-blue-pastel"><i class="material-icons text-warning">query_builder</i></td>
                            <td class="text-center bg-blue-pastel"></td>
                            <td class="text-center bg-blue-pastel"></td>
                            @else
                            <td class="text-center bg-blue-pastel"></td>
                            <td class="text-center bg-blue-pastel"></td>
                            <td class="text-center bg-blue-pastel"></td>
                            @endif
                            <!-- Fim Exame Laboratorial -->

                            <!-- Exame Imagem -->
                            @if($atendimento->exaimg == 'R')
                            <td class="text-center bg-teal-pastel"><i class="material-icons text-success">check_circle_outline</i></td>
                            <td class="text-center bg-teal-pastel"><i class="medical-icon-i-imaging-root-category text-primary" style="font-size: 2em"></i></td>
                            <td class="text-center bg-teal-pastel"></td>
                            @elseif ($atendimento->exaimg == 'L')
                            <td class="text-center bg-teal-pastel"><i class="material-icons text-success">check_circle_outline</i></td>
                            <td class="text-center bg-teal-pastel"><i class="medical-icon-i-imaging-root-category text-primary" style="font-size: 2em"></i></td>
                            <td class="text-center bg-teal-pastel"><i class="material-icons text-success">assignment_turned_in</i></td>
                            @elseif ($atendimento->exaimg == 'NR')
                            <td class="text-center bg-teal-pastel"><i class="material-icons text-warning">query_builder</i></td>
                            <td class="text-center bg-teal-pastel"></td>
                            <td class="text-center bg-teal-pastel"></td>
                            @else
                            <td class="text-center bg-teal-pastel"></td>
                            <td class="text-center bg-teal-pastel"></td>
                            <td class="text-center bg-teal-pastel"></td>
                            @endif
                            <!-- Fim Exame Imagem -->

                            <!-- Parecer -->
                            <!-- <td class="text-center">
                                @if($atendimento->parecer > 0)
                                <span class="badge badge-primary" style="font-size: 1.15em">{{ $atendimento->parecer }}</span>
                                @endif
                            </td>
                            -->
                            <!-- Status do Paciente -->

                            <!-- <td class="text-center"><i class="medical-icon-i-surgery text-primary" style="font-size: 2em"></i></td> -->
                            {{-- @if (!is_null($atendimento->dt_alta_medica)) --}}
                            {{-- <td class="text-center bg-pink-pastel"><i class="medical-icon-i-outpatient text-success" style="font-size: 2em"></i></td> --}}
                            {{-- @else --}}
                            {{-- <td class="text-center bg-pink-pastel"></td> --}}
                            {{-- @endif --}}

                            @if ($atendimento->leitoSolicitado > 0 && $atendimento->leitoAtendido == 0)
                            <td class="text-center bg-pink-pastel"><i class="material-icons text-warning">query_builder</i></td>
                            @elseif ($atendimento->leitoAtendido > 0)
                            <td class="text-center bg-pink-pastel"><i class="material-icons text-success">check_circle_outline</i></td>
                            @else
                            <td class="text-center bg-pink-pastel"></td>
                            @endif

                            @if ($atendimento->leitoAtendido > 0)
                            <td class="text-center bg-pink-pastel"><i class="material-icons text-success">check_circle_outline</i></td>
                            @else
                            <td class="text-center bg-pink-pastel"></td>
                            @endif

                            <!-- Fim Status do Paciente -->
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center pt-3" colspan="24"><i class="fas fa-info-circle"></i><br/>Nenhum paciente encontrado</td>
                        </tr>
                        @endforelse
                    @else
                        <tr>
                            <td class="text-center pt-3" colspan="24"><i class="fas fa-info-circle"></i><br/>Selecione um setor</td>
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
        location.reload();
    }
</script>
@endpush
