<div class="row fixed-top bg-gray">
        <div class="col-md-1">
            <div class="logo-header text-center">
                <img src="{{ asset('rhp') }}/img/logo-hospital-rodon.png" alt="">
            </div>
        </div>
        <div class="col-md-11 border-right">
            <div class="d-flex align-items-center" style="height: 100%;">
                <div class="text-painel w-100 text-center align-middle mx-2" style="line-height: 1.5em; font-size: 35px">
                    {{ $title ?? '' }}
                </div>
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
