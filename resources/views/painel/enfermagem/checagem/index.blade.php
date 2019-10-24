@extends('layouts.rhp.app')

@section('content')
    @include('layouts.rhp.navbars.navbar')
    
    <div class="container">
        <div class="card">
            <div class="card-body">
                <p>{{ $pacientes->nm_paciente }}</p>
                @forelse ($pacientes->atendimentos as $atendimento)
                <p><strong>{{ $atendimento->cd_atendimento }} - {{ $atendimento->convenio->nm_convenio }}</strong></p>
                @empty
                    
                @endforelse
            </div>
        </div>
    </div>

@endsection
