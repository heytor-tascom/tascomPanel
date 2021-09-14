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
                        <th class="text-center">ATENDIMENTO</th>
                        <th>PACIENTE</th>
                        <th>ESPECIALIDADE</th>
                        <th class="text-center">AFERIÇÃO</th>
                        <th class="text-center">CHECAGEM</th>
                        <th class="text-center">EXAMES LABORATORIAIS</th>
                        <th class="text-center">EXAMES IMAGEM</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($atendimentos as $key => $atendimento)
                    @php
                    $nomeAbreviado = $helpers::abreviarNome($atendimento->paciente->nm_paciente);
                    @endphp
                    
                    <tr>
                        <!-- Atendimento -->
                        <td class="text-center">{{ $atendimento->cd_atendimento }}</td>
                        <!-- Nome do Paciente -->
                        <td>{{ $nomeAbreviado }}</td>
                        <!-- Especialidade -->
                        <td>{{ $atendimento->ds_especialid }}</td>
                        
                        <!-- Aferição -->
                        <td class="text-center">
                        @if ($atendimento->afericao > 0)
                        <i class="material-icons text-success">check_circle_outline</i>
                        @else
                        <i class="material-icons text-danger animated pulse infinite">error_outline</i>
                        @endif
                        </td>
                        
                        <!-- Checagem -->
                        <td class="text-center">
                            @if ($atendimento->checagem == 'A')
                            <i class="material-icons text-danger animated pulse infinite">error_outline</i>
                            @elseif ($atendimento->checagem == 'C')
                            <i class="material-icons text-warning">query_builder</i>
                            @elseif ($atendimento->checagem == 'F')
                            <i class="material-icons text-success">check_circle_outline</i>
                            @else
                            <i class="material-icons text-success">check_circle_outline</i>
                            @endif
                        </td>

                        <!-- Exame Laboratorial -->
                        <!-- Realizado -->
                        <td class="text-center">
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
                        <td class="text-center">
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