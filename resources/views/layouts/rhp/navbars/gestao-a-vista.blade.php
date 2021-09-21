<div class="row fixed-top bg-gray">
        <div class="col-md-1">
            <div class="logo-header text-center">
                <img src="{{ asset('rhp') }}/img/logo-hospital-rodon.png" alt="">
            </div>
        </div>
        <div class="col-md-2 border-right">
            <div class="d-flex align-items-center">
                <div class="text-painel w-100 text-center align-middle mx-2 py-2" style="line-height: 1.5em">
                    {{ $title ?? '' }}
                </div>
            </div>

            <div class="ml-3">
                @if(isset($setores))
                @php
                $cdSetor    = (isset($_GET['setores'])) ? $_GET['setores'] : null;
                $cdSetor    = explode(",", $cdSetor);
                @endphp
                <select name="setores" id="" class="selectpicker" onchange="setSetor(this)" data-live-search="true" data-selected-text-format="count" data-actions-box="true" multiple>
                    <option value="">&nbsp;</option>
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
        </div>
        <div class="col-md-9 py-2 legendas">
            <div class="d-flex flex-column align-items-center pt-4">
                <ul class="list-unstyled w-100 mt-3">
                    <li class="list-inline-item mb-3" style="width: 10%"><i class="material-icons text-success float-left">check_circle_outline</i>&nbsp; FEITO</li>
                    <li class="list-inline-item mb-3" style="width: 10%"><i class="material-icons text-danger float-left">highlight_off</i>&nbsp; NÃO FEITO</li>
                    <li class="list-inline-item mb-3" style="width: 10%"><i class="material-icons text-danger float-left animated pulse infinite">error_outline</i>&nbsp; ALERTA</li>
                    <li class="list-inline-item mb-3" style="width: 10%"><i class="material-icons text-warning float-left">query_builder</i>&nbsp; SOLICITADO</li>
                    <li class="list-inline-item mb-3" style="width: 10%"><i class="material-icons text-black float-left"><i class="material-icons">fiber_manual_record</i></i>&nbsp; ALERGIA</li>
                    <li class="list-inline-item mb-3" style="width: 10%"><i class="medical-icon-i-outpatient text-success float-left" style="font-size: 2em;"></i>&nbsp; ALTA</li>
                    <li class="list-inline-item mb-3" style="width: 10%"><i class="medical-icon-i-laboratory text-primary float-left" style="font-size: 2em"></i>&nbsp; COLETADO</li>
                    <li class="list-inline-item mb-3" style="width: 10%"><i class="medical-icon-i-imaging-root-category text-primary float-left" style="font-size: 2em"></i>&nbsp; IMAGEM</li>
                    <li class="list-inline-item mb-3" style="width: 10%"><i class="material-icons text-success float-left">assignment_turned_in</i>&nbsp; RESULTADO/LAUDO</li>
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
