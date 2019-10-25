@extends('layouts.rhp.app')

@section('content')
    @include('layouts.rhp.navbars.navbar') 
    <div class="container-fluid">
        <div class="card mt-5 pt-4">
            <div class="card-body px-0 pt-4">
                <div class="w-100 mt-5"></div>
                <table class="table table-sm">
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
                            <th class="text-center">DETALHES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($atendimentos as $atendimento)
                        <tr>
                            <td class="text-center"></td>
                            <td>{{ $atendimento->nm_setor }}</td>
                            <td>{{ $atendimento->ds_leito }}</td>
                            <td class="text-center">{{ $atendimento->cd_paciente }}</td>
                            <td class="text-center">{{ $atendimento->cd_atendimento }}</td>
                            <td>{{ $atendimento->paciente->nm_paciente }}</td>
                            <td class="text-center">{{ date("d/m/Y", strtotime($atendimento->paciente->dt_nascimento)) }}</td>
                            <td class="text-center">{{ date("d/m/Y", strtotime($atendimento->dt_atendimento)) }}</td>
                            <td class="text-center"></td>
                            <td>{{ !empty($atendimento->paciente->nm_social) ? $atendimento->paciente->nm_social : '-' }}</td>
                            <td class="text-center"><i class="fas fa-link"></i></td>
                        </tr>
                        @empty    
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
