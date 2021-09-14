@extends('layouts.rhp.app')

@section('content')
@include('layouts.rhp.navbars.gestao-a-vista')
<div class="container-fluid">
    <div class="card mt-5 pt-4">
        <div class="card-body px-0 pt-4">
            <div class="w-100 mt-5"></div>
            <div class="p-2">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="text-center">
                        <p>Selecione um setor:</p>
                        <select name="setorId" id=""  class="selectpicker" onchange="setSetor(this)" data-dropdow-align-right="false" data-live-search="true" data-selected-text-format="count" data-actions-box="true">
                            <option value=""></option>
                            @forelse ($setores as $key => $setor)
                            <option value="{{ $setor->cd_setor }}">{{ $setor->nm_setor }}</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function setSetor(e) {
        let val = $(e).val();

        window.location.href = window.location.href + "/" + val;
    }
</script>

@endpush