@extends('layouts.rhp.app')

@section('content')

<link href="{{ asset('rhp') }}/css/tableFixed.css" rel="stylesheet">

    @include('layouts.rhp.navbars.blocoCirurgico.acompCirurgia') 
    @inject('helperBloco', 'App\Http\Helpers\AcompCirurgiaHelpers')
    @inject('helpers', 'App\Http\Helpers\Helpers')
    <div class="container-fluid">
        <div class="card mt-5 pt-4">
            <div class="card-body px-0 pt-4">
                <div class="w-100 mt-5"></div>

                <table class="table table-sm " id="tableCirurgias">
                    <thead>
                        <tr>
                            <td colspan="14"></td>
                            <td colspan="9" class="text-center" style="background-color:#3CAD81; color:white;">STATUS</td>
                        </tr>
                    <tr>       
                        <th class="text-center" >AGENDAMENTO</th>
                        <th class="text-center" >ADM. BLOCO</th>
                        <th class="text-center" >CLASSIFICAÇÃO</th>
                        <th class="text-center">SALA</th>
                        <th class="text-center">AVISO</th>
                        <th class="text-center">ATENDIMENTO</th>
                        <th class="text-center">TIPO</th>
                        <th>PACIENTE</th>
                        <th class="text-center">DATA DE NASCIMENTO</th>
                        <th>CIRURGIA</th>
                        <th>CIRURGIÃO</th>
                        <th>CONVÊNIO</th>
                        <th>UNID. INTERNAÇÃO</th>
                        <th class="text-center">LEITO</th>

                        <th class="text-center bg-blue-pastel">SIGN-IN</th>
                        <th class="text-center bg-blue-pastel">TIME-OUT</th>
                        <th class="text-center bg-blue-pastel">SIGN-OUT</th>
                        <th class="text-center bg-blue-pastel">CONFIRMAÇÃO</th>

                        <th class="text-center bg-green-pastel">BLOCO</th>
                        <th class="text-center bg-green-pastel">RPA</th>
                        <th class="text-center bg-green-pastel">LEITO</th>
                    </tr>   
                        
                    </thead>
                    <tbody>
                        
                        @forelse ($cirurgias as $cirurgia)
                        @if ($cirurgia->mensagem == 1)
                            <tr style="background-color:#F5CC01;">
                        @else
                            <tr>
                        @endif
                        
                            <td class="text-center">{{ $cirurgia->hora }}</td>
                            <td class="text-center">{{ $cirurgia->hr_admissao_bloco }}</td>
                            <td class="text-center">
                                @php echo $helperBloco::status($cirurgia->classificacao); @endphp 
                            </td>
                            <td class="text-center">{{ $cirurgia->sala }}</td>
                            <td class="text-center">{{ $cirurgia->aviso }}</td>
                            <td class="text-center">{{ $cirurgia->cd_atendimento }}</td>
                            <td class="text-center">{{ $cirurgia->tp_atendimento }}</td>
                            <td style="color:#1976D2;">
                            @php
                                echo $nomeAbreviado = $helpers::abreviarNome($cirurgia->nm_social_or_nome);    
                            @endphp
                            </td>
                            
                            <td class="text-center" style="color:#1976D2;">{{ $cirurgia->dt_nascimento }}</td>
                            <td  style="color:#673AB7;">{{ $cirurgia->cirurgia }}</td>
                            <td style="color:#00796B;">{{ $cirurgia->cirurgiao }}</td>
                            <td>{{ $cirurgia->convenio }}</td>
                            <td>{{ $cirurgia->unid_int }}</td>
                            <td class="text-center">{{ $cirurgia->leito }}</td>
                            
                            <td class="text-center bg-blue-pastel">
                                @php echo $helperBloco::status_doc_c($cirurgia->signin); @endphp 
                            </td>
   
                            <td class="text-center bg-blue-pastel">
                                @php echo $helperBloco::status_doc_c($cirurgia->timeout); @endphp 
                            </td>

                            <td class="text-center bg-blue-pastel">
                                @php echo $helperBloco::status_doc_c($cirurgia->signout); @endphp 
                            </td>

   

                            <td class="text-center bg-blue-pastel">
                                @php echo $helperBloco::status_doc_c($cirurgia->confirmacao); @endphp 
                            </td>

                            <td class="text-center bg-green-pastel">
                                @php echo $helperBloco::statusBloco($cirurgia->status_bloco); @endphp
                            </td>
                            <td class="text-center bg-green-pastel">
                                @php echo $helperBloco::statusRPA($cirurgia->status_rpa); @endphp
                            </td>
                            <td class="text-center bg-green-pastel">
                                @php echo $helperBloco::statusLeito($cirurgia->status_leito); @endphp
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center pt-3" colspan="11">Nenhuma cirurgia encontrada</td>
                        </tr>
                        @endforelse
                      
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

<script>
    $(function(){

        $('table').on('scroll', function () {
    $("table > *").width($("table").width() + $("table").scrollLeft());
});


        setInterval(function(){ 
        location.reload(); 
        }, {{ $tempoAtt }} );        

})
function getPacientes()
{

    
    let blocos = $("select[name=blocos]").val();
    if (blocos == null){
        blocos = 2;
    }

    if (blocos.length > 0 || blocos > 0) {

        window.history.replaceState("", "Painel Acomp. Cirurgias", "?blocos="+blocos);
    
    } else {
        // $("#lista-pacientes tbody").empty();
    }
}

function searchPatient() {
        var input, filter, table, tr, td, i, txtValue;
        input   = document.getElementById("searchInput");
        filter  = input.value.toUpperCase();
        table   = document.getElementById("tableCirurgias");
        tr      = table.getElementsByTagName("tr");
        
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[5];
            
            if (td) {
                txtValue = td.textContent || td.innerText;
                
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }

$('.selectpicker').on('hidden.bs.select', function (e, clickedIndex, isSelected, previousValue) {
    location.reload(); 
});

</script>
@endpush


