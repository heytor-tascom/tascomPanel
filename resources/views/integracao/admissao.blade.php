<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#41414C" />
    <title>Integra Tascom</title>

    <link type="text/css" href="{{ asset('argon') }}/css/argon.css?v=1.0.0" rel="stylesheet">
    {{-- <link href="https://getbootstrap.com/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="{{ asset('integracao') }}/styles/main.css" />
    <link rel="stylesheet" href="{{ asset('integracao') }}/styles/partials/page-header.css" />
    <link rel="stylesheet" href="{{ asset('integracao') }}/styles/partials/cards.css" />
    <link rel="stylesheet" href="{{ asset('integracao') }}/styles/partials/buttons.css" />
    {{-- <link rel="stylesheet" href="{{ asset('integracao') }}/styles/partials/modal.css" /> --}}
    <link rel="stylesheet" href="{{ asset('integracao') }}/styles/partials/animations.css" />
    <link rel="stylesheet" href="{{ asset('integracao') }}/styles/pages/index.css" />
    <link href="{{ asset('argon') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">

    <link href="https://getbootstrap.com/docs/5.0/examples/dashboard/dashboard.css" rel="stylesheet">



    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <script src="{{ asset('integracao') }}/scripts/index.js" defer type="module"></script>
  </head>
  <body id="page-index">

    <div class="row">
        <div class="col-md-2">@include('integracao.header.index', ['admissaoActive' => 'active'])</div>
        <div class="col-md-10">

            <header class="page-header">
                <div class="container-fluid">
                  INTEGRAÇÃO ATENDIMENTOS ADMISSÃO DRG<br/><br/>
                  <section id="top" class="animate-up">



                    <div class="" style="width: 80em;">
                      Pesquisa:<br/>
                      <form action="{{ route('searchCadastroAdmissao') }}" method="get">
                          @csrf
                          <div class="row">

                              <div class="col">
                                  <select name="status" class="form-control">
                                      <option value="S">Integrado</option>
                                      <option value="F">Erro</option>
                                      <option value="N">Aguardando</option>
                                  </select>


                              </div>

                              <div class="col">
                                  <select name="type" class="form-control">
                                      <option value="nome">Nome</option>
                                      <option value="atendimento">Atendimento</option>
                                      <option value="data">Data</option>
                                  </select>

                              </div>
                              <div class="col"><input type="text"  class="form-control" name="search" id=""></div>
                              <div class="col"><button class="btn btn-primary">Enviar</button></div>
                          </div>


                      </form>

                    </div>
                  </section>

                  {{-- <div class="separator"></div> --}}

                  <section id="summary" class="animate-up delay-1">
                    <h2 class="sr-only">Sumário</h2>
                    <div class="info">
                      <div class="total" style="cursor:pointer;" onclick="tradeS()" >
                        <strong>{{ count($success) }}</strong>
                        Com sucesso
                      </div>
                      <div class="in-progress" style="cursor:pointer;" onclick="tradeA()">
                        <strong>{{ count($aguard) }}</strong>
                        Em andamento
                      </div>
                      <div class="finished" style="cursor:pointer;" onclick="tradeE()">
                        <strong>{{ count($error) }}</strong>
                        Com erro
                      </div>


                    </div>


                  </section>


                </div>
              </header>

              <div class="container-fluid">
                <main class="animate-up delay-2">
                  <h1 class="sr-only"></h1>

                  <div class="card" id="Tsuccess">
                    <div class="table-responsive">
                        <table class="table table-sm table-hovered">
                          <thead>
                            <tr >
                              <th style="font-size: 1.2em">Nome</th>
                              <th style="font-size: 1.2em">Atendimento</th>
                              <th style="font-size: 1.2em">Data do envio</th>
                              <th style="font-size: 1.2em">Status</th>
                              <th style="font-size: 1.2em"></th>
                            </tr>
                          </thead>
                          <tbody>
                      @forelse ($messages as $message)

                                  <tr>
                                      <td> {{ $message->bnome }} </td>
                                      <td> {{ $message->numeroatendimento }} </td>
                                      <td> {{ date("d/m/Y", strtotime($message->updated_at)) }} </td>
                                      <td>
                                        @if ($message->sn_integrado == 'S') <p class="text-success">INTEGRADO</p> @endif
                                        @if ($message->sn_integrado == 'F') <p class="text-danger">ERRO</p> @endif
                                        @if ($message->sn_integrado == 'N') <p class="text-info">AGUARDANDO</p> @endif
                                      </td>
                                      <td>
                                        <a
                                        href="#"
                                        class="button white edit"
                                        title="Ver informações"
                                        style="background-color:#eaeaea; color:#5a67a9;"
                                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                                        onclick="getReturnDRG({{ $message->id }})">
                                        <i class="fas fa-eye"></i>
                                      </a>
                                      <button class="delete button" title="Ver XML" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="getReturnXML({{ $message->id }})">
                                          <i class="fas fa-search" style="color: dimgrey"></i>
                                      </button>
                                      <button class="delete button" title="Regerar XML" onclick="regerarXmlAlta('{{ $message->numeroatendimento }}')">
                                        <i class="fas fa-sync-alt"; style="color:#f39c12;"></i>
                                      </button>
                                      </td>
                                  </tr>
                        @empty

                      @endforelse
                    </tbody>
                </table>
                {{ $messages->appends($req ?? '')->links('vendor.pagination.bootstrap-4') }}
            </div>
                    <!-- end card -->

                  </div>

                  <!-- end cards -->

                </main>
              </div>

                </div>
            </div>

    <div class="modal fade" style="color:black;" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="return"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>

        $(document).ready(function() {
            $("#exampleModal").css({
            "background-color": "rgba(0,0,0,0.8)"
            });



        });



        function getReturnDRG(e) {


            //const axios = require('axios');

            // Make a request for a user with a given ID
            axios.get("{{ route('getReturnDRGAdmissao') }}?id="+e)
            .then(function (response) {
                // handle success
                let ret = null;

                if (response.data.statusCode == '{}'){
                    ret = 'Erro desconhecido';

                }else{
                    ret = response.data.statusCode;
                    //let ret2 = JSON.parse(ret);
                    console.log(ret);

                    // if (ret2['S:Envelope']){
                    //     ret = ret2['S:Envelope']['S:Body'][0]['ns2:importaInternacaoResponse'][0]['return'][0];

                    // }

                }

                $('#return').html(ret);
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .then(function () {
                // always executed
            });

        }



        function getReturnXML(e) {


        //const axios = require('axios');

        // Make a request for a user with a given ID
        axios.get("{{ route('getReturnDRGAdmissao') }}?id="+e)
        .then(function (response) {
            // handle success
            let ret = null;

            if (response.data.statusCode == '{}'){
                ret = 'Erro desconhecido';

            }else{
                ret = response.data.xml;
            }

            $('#return').text(ret);
        })
        .catch(function (error) {
            // handle error
            console.log(error);
        })
        .then(function () {
            // always executed
        });

        }

        function regerarXmlAlta(atd) {

            axios.post(`http://10.0.38.39:3003/admissional/${atd}`, {
            })
            .then(function (response) {

                $('#exampleModal').modal('show');

                $('#return').html(`Registro Regerado com sucesso para o atendimento: ${atd}`);

            })
            .catch(function (error) {

                $('#exampleModal').modal('show');

                $('#return').html(`Erro ao reintegrar: ${error}`);
            });

        }






    </script>

  </body>
</html>
