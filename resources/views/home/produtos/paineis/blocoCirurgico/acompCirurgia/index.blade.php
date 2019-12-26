@extends('layouts.rhp.app')

@section('content')
    @include('layouts.rhp.navbars.blocoCirurgico.acompCirurgia') 
    <div class="container-fluid">
        <div class="card mt-5 pt-4">
            <div class="card-body px-0 pt-4">
                <div class="w-100 mt-5"></div>
                <table class="table table-sm" id="">
                    <thead>
                    <tr>       
                        <th>HORÁRIO</th>
                        <th>INÍCIO</th>
                        <th>STATUS</th>
                        <th>SALA</th>
                        <th>AVISO</th>
                        <th>PACIENTE</th>
                        <th>IDADE</th>
                        <th>CIRURGIA</th>
                        <th>CIRURGIÃO</th>
                        <th>CONVENIO</th>
                        <th>LEITO</th>
                        <th>BLOCO</th>
                        <th>RPA</th>
                        <th>LEITO</th>
                        <th>MENSAGEM</th>
                    </tr>   
                        
                    </thead>
                    <tbody>
                        
                        @forelse ($cirurgias as $cirurgia)
                        <tr>
                            <td>{{ $cirurgia->hora }}</td>
                            <td>{{ $cirurgia->inicio }}</td>
                            <td>{{ $cirurgia->status }}</td>
                            <td>{{ $cirurgia->sala }}</td>
                            <td>{{ $cirurgia->aviso }}</td>
                            <td>{{ $cirurgia->paciente }}</td>
                            <td>{{ $cirurgia->idade }}</td>
                            <td>{{ $cirurgia->cirurgia }}</td>
                            <td>{{ $cirurgia->cirurgiao }}</td>
                            <td>{{ $cirurgia->convenio }}</td>
                            <td>{{ $cirurgia->leito }}</td>
                            @if ($cirurgia->status_bloco == 1)
                                <td><i class="fas fa-arrow-right"></i></td>
                            @else
                                
                            @endif
                            @if ($cirurgia->status_rpa == 1)
                                <td><i class="fas fa-arrow-right"></i></td>
                            @else
                                
                            @endif
                            @if ($cirurgia->status_leito == 1)
                                <td><i class="fas fa-arrow-right"></i></td>
                            @else
                                
                            @endif
                            <td>{{ $cirurgia->mensagem }}</td>

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

@endsection

@push('scripts')

@endpush


