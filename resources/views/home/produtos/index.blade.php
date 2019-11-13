@extends('layouts.app-rhp')

@section('content')
    @include('layouts.navbars.navs.tascom')
    <main role="main">
    @include('layouts.headers.padrao')    

        <div class="container">
           
             @forelse ($ambientes as $ambiente)
             <div class="my-3 p-3 bg-white rounded shadow-sm animated fadeInUp">
                <h5 class="border-bottom border-gray pb-2 mb-0">{{ $ambiente->ds_ambiente }}</h5>

                @forelse ($ambiente->produtos as $produto)
                    <div class="media text-muted pt-3">
                        <div class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <strong class="text-gray-dark" id="id{{ $produto->id }}">{{ $produto->nm_produto }}</strong>
                            </div>
                                <a class="btn btn-secondary" href="{{ route($produto->nm_rota) }}" target="_blank">Abrir</a>
                                <button class="btn btn-secondary" onclick="editarProduto({ 'id': {{ $produto->id }}, 'nm_produto': '{{ $produto->nm_produto }}', 'tempo_atualizacao': '{{ $produto->tempo_atualizacao }}' })" data-toggle="modal" id="{{ $produto->id }}" data-target="#exampleModal">Editar</button>
                            
                            <span class="d-block">Parametros: {{ $produto->ds_parametros }}</span>
                        </div>
                    </div>    
                        
                @empty
                    <p class="text-center mt-3 text-muted">
                        <i class="fas fa-box-open"></i>
                        <br/>
                        Sem produtos aqui no momento
                    </p>
                @endforelse
                
             </div>

             @empty
                 
             @endforelse

      
        </div>  

    </main>
@endsection


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Editar Produto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                
                <form action="{{ route('editar_produtos') }}" method="post">
                    @csrf
                        <input type="text" id="produto_id" hidden name="id">
                    <div class="row">
                        <div class="col-md-12">
                            Nome:<br/>
                            <input type="text" class="form-control" name="nm_produto" id="nm_produto">
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-12">
                                Tempo de Atualização <span class="text-muted">(Milissegundos)</span>:<br/>
                                <input type="number" class="form-control" name="tempo_atualizacao" id="tempo_atualizacao">
                            </div>
                        </div>                            
            
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
        </form>
            </div>
        </div>
        </div>
    </div>


<script>

    function editarProduto(e) {

        $('#produto_id').val(e.id);
        $('#nm_produto').val(e.nm_produto);
        $('#tempo_atualizacao').val(e.tempo_atualizacao);

    }
</script>            