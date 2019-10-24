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
                <select name="setores" id="" class="selectpicker" onchange="getPacientes()" data-live-search="true" data-selected-text-format="count" multiple>
                </select>
            </div>
        </div>
    </div>
</div>