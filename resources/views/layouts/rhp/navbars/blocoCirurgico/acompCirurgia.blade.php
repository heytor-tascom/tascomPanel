<div class="row fixed-top bg-gray">
    <div class="col-md-1">
        <div class="logo-header text-center">
            <img src="{{ asset('rhp') }}/img/logo-hospital-rodon.png" alt="">
        </div>
    </div>
    <div class="col-md-5 ">
        <div class="row">
            <div class="col-md-6">
                <div class="text-painel align-middle text-center mx-2 mt-5" style="line-height: 1.5em">
                    {{ $title ?? '' }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center flex-wrap mt-3" style="height: 100%;">
                    <div class="flex-fill mr-3">
                        @if(isset($blocos))
                        @php
                        $cdBloco    = (isset($_GET['blocos'])) ? $_GET['blocos'] : null;
                        $cdBloco    = explode(",", $cdBloco);
                        @endphp
                        <select name="blocos" id="" class="selectpicker" onchange="getPacientes()" data-live-search="true" data-selected-text-format="count" data-actions-box="true" multiple>
                            @forelse($blocos as $bloco)
                            @php
                            $selected = (gettype(array_search($bloco->cd_bloco, $cdBloco)) == 'integer') ? "selected" : null;
                            @endphp
                            <option value="{{ $bloco->cd_bloco }}" {{ $selected }}>{{ $bloco->nm_bloco }}</option>
                            @empty
                            @endforelse
                        </select>
                        @endif
                    </div>
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
        </div>
    </div>

    <div class="col-md-6 py-2 text-center">

            <div class="row">
                <div class="col-md-12">


                      <button type="button" class="btn btn-secondary " >
                        <i class="fas fa-circle" style="color:green;"></i> CIRURGIA NO HORÁRIO
                      </button>
                      <button type="button" class="btn btn-secondary " >
                        <i class="fas fa-circle" style="color:#D50000;"></i> CIRURGIA ATRASADA
                      </button>
                      <button type="button" class="btn btn-secondary " >
                        <i class="fas fa-arrow-left" style="color:#4C6EF5;"></i> CIRURGIA ADIANTADA
                      </button>
                      <button type="button" class="btn btn-secondary " >
                        <i class="fas fa-arrow-right" style="color:#4C6EF5;"></i> PROCESSO ATUAL
                      </button>
                      <button type="button" class="btn btn-secondary " >
                        <i class="far fa-times-circle" style="color:#D50000;"></i> CIRURGIA CANCELADA
                      </button>
                  </div>
    </div>
</div>
</div>

@push('scripts')
<script>

$(".selectpicker").selectpicker({
        noneSelectedText: "Selecione um Bloco Cirúrgico",
        noneResultsText: "Nenhum Bloco Cirúrgico encontrado",
        countSelectedText: "{0} Bloco Cirúrgicos selecionados",
        deselectAllText: "Desmarcar Todos",
        selectAllText: "Marcar Todos",
    });




</script>
@endpush
