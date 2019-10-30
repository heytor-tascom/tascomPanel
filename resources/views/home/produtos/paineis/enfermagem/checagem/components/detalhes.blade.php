
<div class="p-2">
    <ul class="list-unstyled">
        <li class="list-inline-item">
            <span class="text-secondary">Paciente:</span>
            <h4>{{ $pacienteData->paciente->nm_paciente }}</h4>
        </li>
        <li class="list-inline-item ml-2">
            <span class="text-secondary">Data de Nascimento:</span>
            <h4>{{ date("d/m/Y", strtotime($pacienteData->paciente->dt_nascimento)) }}</h4>
        </li>
        <li class="list-inline-item ml-2">
            <span class="text-secondary">Atendimento:</span>
            <h4>{{ $pacienteData->cd_atendimento }}</h4>
        </li>
    </ul>
</div>

@forelse($prescricaoData as $prescricao)
    @if (count($prescricao->itens) > 0)
        <div class="bg-gray-strong px-2 py-2">
            <span class="float-right font-weight-bold">
                <small>Validade:</small> {{ date("d/m/Y H:i:s", strtotime($prescricao->dt_validade)) }}
            </span>
            {{ $prescricao->cd_pre_med }} - [{{ date("d/m/Y H:i:s", strtotime($prescricao->dh_criacao)) }}] -  {{ $prescricao->prestador->nm_prestador }}
        </div>

        <table class="table table-striped">
            <thead class="py-2">
                <tr>
                    <td><strong>Cód</strong></td>
                    <td><strong>Descrição</strong></td>
                    <td class="text-center"></td>
                </tr>
            </thead>
            <tbody>
                @forelse($prescricao->itens as $item)
                <tr>
                    <td>{{ $item->cd_itpre_med }}</td>
                    <td width="450px">{{ $item->tipoItemPrescricao->ds_tip_presc }}</td>
                    <td>
                        <div class="" style="width: 600px; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch;">
                            @php 
                            $horarios = App\Http\Controllers\Home\Produtos\Paineis\Enfermagem\PainelEnfChecagemController::horariosItemPrescricao($item->cd_itpre_med);
                            @endphp

                            @forelse ($horarios as $horario)
                            <div class="my-1 mr-3" style="display: inline-block; float: none; width: 100px"><div class="text-center text-secondary"><small>{{ $horario->dt_medicacao }}</small></div>
                                <a tabindex="0" style="text-decoration: none;" class="popover-dismiss" data-toggle="popover" data-placement="bottom" data-content=''>
                                    <div class="card my-0 cursor-pointer">
                                        <div class="card-body p-1 text-center">
                                            {{ $horario->hr_medicacao }}
                                            <br>
                                            <small>PRÓXIMO</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @empty
                            nenhum horario
                            @endforelse
                        </div>
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    @endif
@empty
    nenhuma prescricao
@endforelse