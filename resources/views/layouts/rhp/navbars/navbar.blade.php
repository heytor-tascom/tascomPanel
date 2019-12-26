<div class="row fixed-top bg-gray">
        <div class="col-md-1">
            <div class="logo-header text-center">
                <img src="{{ asset('rhp') }}/img/icones/logo_rhp.png" alt="">
            </div>
        </div>
        <div class="col-md-4 border-right">
            <div class="d-flex align-items-center" style="height: 100%;">
                <div class="text-painel align-middle mx-2" style="line-height: 1.5em">
                    {{ $title ?? '' }}
                </div>
                <div class=" ml-3">
                    @if(isset($setores))
                    @php
                    $cdSetor    = (isset($_GET['setores'])) ? $_GET['setores'] : null;
                    $cdSetor    = explode(",", $cdSetor);
                    @endphp
                    <select name="setores" id="" class="selectpicker" onchange="getPacientes()" data-live-search="true" data-selected-text-format="count" data-actions-box="true" multiple>
                        @forelse($setores as $setor)
                        @php
                        $selected = (gettype(array_search($setor->cd_setor, $cdSetor)) == 'integer') ? "selected" : null;
                        @endphp
                        <option value="{{ $setor->cd_setor }}" {{ $selected }}>{{ $setor->nm_setor }}</option>
                        @empty
                        @endforelse
                    </select>
                    @endif
                </div>
                <br>
                <div class="w-100">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">search</i>
                            </span>
                        </div>
                        <input type="search" class="form-control" id="searchInput" onkeyup="searchPatient()" placeholder="Pesquise pelo nome do paciente...">
                        <span class="material-icons float-right mt-2 cursor-pointer" onclick="javascript:document.getElementById('searchInput').value=''; searchPatient();">clear</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7 py-2 legendas">
            <div class="d-flex flex-column pt-4" style="height: 100%;">
                <ul class="list-unstyled w-100">
                    <li class="list-inline-item w-25 mb-3"><i class="material-icons checagemProxima float-left mt--1">info</i>&nbsp; CHECAGEM NA PRÓXIMA HORA</li>
                    <li class="list-inline-item w-25 mb-3"><i class="material-icons text-blue float-left mt--1" style="color: #e67e22">access_time</i>&nbsp; APRAZAMENTO</li>
                    <li class="list-inline-item w-25 mb-3"><i class="material-icons text-blue float-left mt--1" style="color: #4285f4;">move_to_inbox</i>&nbsp; DISPENSAÇÃO</li>
                    <li class="list-inline-item w-25 mb-3"><i class="material-icons checagemAtrasada float-left mt--1">alarm</i>&nbsp; CHECAGEM ATRASADA</li>
                    <li class="list-inline-item w-25 mb-3"><i class="material-icons text-blue float-left mt--1" style="color: #665aff;">local_pharmacy</i>&nbsp; AVALIAÇÃO FARMACÊUTICA</li>
                    <li class="list-inline-item w-25 mb-3"><i class="material-icons text-blue float-left mt--1 text-success">check_circle_outline</i>&nbsp; CHECAGEM</li>
                </ul>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        $(".selectpicker").selectpicker({
            noneSelectedText: "Selecione um setor",
            noneResultsText: "Nenhum setor encontrado",
            countSelectedText: "{0} Setores selecionados",
            deselectAllText: "Desmarcar Todos",
            selectAllText: "Marcar Todos",
        })
    </script>
    @endpush