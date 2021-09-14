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
        rel="stylesheet" />

    <script src="{{ asset('integracao') }}/scripts/index.js" defer type="module"></script>
</head>

<body id="page-index">

    <div class="row">
        <div class="col-md-2">@include('integracao.header.index', ['deparaActive' => 'active'])</div>
        <div class="col-md-10">

            <header class="page-header">
                <div class="container-fluid">
                    CADASTRO DE DEPARA<br /><br />
                    <section id="top" class="animate-up">

                        <div class="" style="width: 55em;">
                            Pesquisa:<br />
                            <form action="{{ route('depara') }}" method="get">
                                @csrf
                                <div class="row">
                                    <div class="col">
                                        <select name="tp_depara" class="form-control">
                                            @foreach ($tiposDepara as $tipoDepara)
                                                <option value="{{ $tipoDepara->tp_depara }}">
                                                    {{ $tipoDepara->tp_depara }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col">
                                        <select name="codigo" class="form-control">
                                            <option value="mv">Código MV</option>
                                            <option value="externo">Código DRG</option>
                                        </select>
                                    </div>
                                    <div class="col"><input type="text" class="form-control" name="search" id=""></div>
                                    <div class="col"><button class="btn btn-primary">Enviar</button></div>

                                </div>
                                    <br/>
                                <a class="button orange" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <span>
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    &nbsp; Adicionar DE-PARA
                                </a>
                            </form>

                        </div>
                    </section>

                    <section id="summary" class="animate-up delay-1">
                        <h2 class="sr-only">Sumário</h2>
                        <div class="info"> </div>
                    </section>


                </div>
            </header>

            <div class="container-fluid">
                <main class="animate-up delay-2">
                    <h1 class="sr-only">Trabalhos</h1>

                    <div class="card" id="Tsuccess">
                        <div class="table-responsive">
                            <table class="table table-sm table-hovered">
                              <thead>
                                <tr>
                                  <th>TIPO</th>
                                  <th>CÓDIGO MV</th>
                                  <th>CODIGO DRG</th>
                                  <th>SISTEMA</th>
                                  {{-- <th></th> --}}
                                </tr>
                              </thead>
                              <tbody>
                        @forelse ($messages as $message)

                        <tr>
                            <td> {{ $message->tp_depara }} </td>
                            <td> {{ $message->cd_depara_mv }} </td>
                            <td> {{ $message->cd_depara_integra }} </td>
                            <td> {{ $message->cd_sistema_integra }} </td>
                        </tr>

                        @empty

                        @endforelse

                        <!-- end card -->
                    </tbody>
                </table>
                <br/>
                {{ $messages->appends($req ?? '')->links('vendor.pagination.bootstrap-4') }}
            </div>
            <!-- end card -->

          </div>

          <!-- end cards -->

        </main>
      </div>

        </div>
    </div>
            <!-- end container -->



            <div class="modal fade" id="exampleModal" style="color:black" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('storeDepara') }}" method="post">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Cadastro de DE-PARA</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-left">

                                <div class="row">
                                    <div class="col-md-6">
                                        Tipo do DEPARA<br />
                                        <select name="tp_depara" class="form-control">
                                            @foreach ($tiposDepara as $tipoDepara)
                                                <option value="{{ $tipoDepara->tp_depara }}">
                                                    {{ $tipoDepara->tp_depara }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        Código MV<br />
                                        <input type="text" class="form-control" name="cd_depara_mv">
                                    </div>
                                </div>
                                <br />
                                <div class="row">
                                    <div class="col-md-6">
                                        Código DRG<br />
                                        <input type="text" class="form-control" name="cd_depara_integra">
                                    </div>
                                    <div class="col-md-6">
                                        Sistema<br />
                                        <select name="cd_sistema_integra" class="form-control">
                                            <option value="DRG">DRG</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Salvar</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"
            integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js"
            integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous">
        </script>
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
                axios.get("{{ route('getReturnDRG') }}?id=" + e)
                    .then(function(response) {
                        // handle success
                        let ret = null;

                        if (response.data.statusCode == '{}') {
                            ret = 'Erro desconhecido';

                        } else {
                            ret = response.data.statusCode;
                            //let ret2 = JSON.parse(ret);
                            console.log(ret);

                            // if (ret2['S:Envelope']){
                            //     ret = ret2['S:Envelope']['S:Body'][0]['ns2:importaInternacaoResponse'][0]['return'][0];

                            // }

                        }

                        $('#return').html(ret);
                    })
                    .catch(function(error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function() {
                        // always executed
                    });

            }



            function getReturnXML(e) {


                //const axios = require('axios');

                // Make a request for a user with a given ID
                axios.get("{{ route('getReturnDRG') }}?id=" + e)
                    .then(function(response) {
                        // handle success
                        let ret = null;

                        if (response.data.statusCode == '{}') {
                            ret = 'Erro desconhecido';

                        } else {
                            ret = response.data.xml;
                        }

                        $('#return').text(ret);
                    })
                    .catch(function(error) {
                        // handle error
                        console.log(error);
                    })
                    .then(function() {
                        // always executed
                    });

            }

            function regerarXmlAlta(atd) {

                axios.post(`http://10.0.38.39:3003/paciente/alta/${atd}`, {})
                    .then(function(response) {

                        $('#exampleModal').modal('show');

                        $('#return').html(`Registro Regerado com sucesso para o atendimento: ${atd}`);

                    })
                    .catch(function(error) {

                        $('#exampleModal').modal('show');

                        $('#return').html(`Erro ao reintegrar: ${error}`);
                    });

            }

        </script>

</body>

</html>
