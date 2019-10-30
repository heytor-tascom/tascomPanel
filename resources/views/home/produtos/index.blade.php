@extends('layouts.app')

@section('content')
    @include('layouts.navbars.navbar')
    <main role="main">
    @include('layouts.headers.padrao')    

        <div class="container">
            {{-- <div class="my-3 p-3 bg-white rounded shadow-sm ">
                <h5 class="border-bottom border-gray pb-2 mb-0">PRODUÇÃO</h5>
                    @forelse ($paineisPRD as $painelPRD)
                        <div class="media text-muted pt-3">
                            <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <strong class="text-gray-dark">{{$painelPRD->nm_produto}}</strong>
                                    <a href="{{route($painelPRD->nm_rota)}}" target="_blank">Abrir</a>
                                </div>
                                <span class="d-block">Parametros: {{$painelPRD->ds_parametros}}</span>
                            </div>
                        </div>
                    @empty
                        <div class="media text-muted pt-3">
                            <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                <span class="d-block">Sem resultados</span>
                            </div>
                        </div>                        
                    @endforelse 
            </div>   --}}

             @forelse ($paineis as $painel)
             <div class="my-3 p-3 bg-white rounded shadow-sm ">
                <h5 class="border-bottom border-gray pb-2 mb-0">{{$painel->ds_ambiente}}</h5>

                @forelse ($painel->produtos as $produto)
                    <div class="media text-muted pt-3">
                        <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <strong class="text-gray-dark">{{$produto->nm_produto}}</strong>
                                <a href="{{route($produto->nm_rota)}}" target="_blank">Abrir</a>
                            </div>
                            <span class="d-block">Parametros: {{$produto->ds_parametros}}</span>
                        </div>
                    </div>    
                @empty
                    
                @endforelse
                
             </div>
             @empty
                 
             @endforelse

      
        </div>  

    </main>
@endsection
