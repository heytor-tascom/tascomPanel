<div class="row fixed-top bg-gray">
    <div class="col-md-1">
        <div class="logo-header text-center">
            <img src="{{ asset('rhp') }}/img/icones/logo_rhp.png" alt="">
        </div>
    </div>
    <div class="col-md-5 border-right">
        <div class="d-flex align-items-center" style="height: 100%;">
            <div class="text-painel align-middle mx-2">
                {{ $title ?? '' }}
            </div>
            <div class="flex-fill ml-3">
                @if(isset($setores))
                <select name="setores" id="" class="selectpicker" onchange="getPacientes()" data-live-search="true" data-selected-text-format="count" multiple>
                    @forelse($setores as $setor)
                    <option value="{{ $setor->cd_setor }}">{{ $setor->nm_setor }}</option>
                    @empty
                    @endforelse
                </select>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 py-2 legendas">
        <div class="d-flex flex-column pt-4" style="height: 100%;">
            <div class="mb-3"><i class="material-icons checagemAtrasada float-left mt--1">alarm</i>&nbsp; PACIENTE COM ITENS ATRASADOS</div>
            <div class=""><i class="material-icons checagemProxima float-left mt--1">info</i>&nbsp; PACIENTE COM ITENS A SEREM CHECADOS NA PRÓXIMA HORA</div>
        </div>
    </div>
</div>