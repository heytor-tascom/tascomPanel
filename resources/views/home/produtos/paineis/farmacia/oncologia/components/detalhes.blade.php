
<div class="p-2">
    <ul class="list-unstyled">
        <li class="list-inline-item">
            <span class="text-secondary">Paciente:</span>
            <h4><strong>{{ $pacienteData->paciente->nm_paciente }}</strong></h4>
        </li>
        <li class="list-inline-item ml-2">
            <span class="text-secondary">Data de Nascimento:</span>
            <h4><strong>{{ date("d/m/Y", strtotime($pacienteData->paciente->dt_nascimento)) }}</strong></h4>
        </li>
        <li class="list-inline-item ml-2">
            <span class="text-secondary">Atendimento:</span>
            <h4><strong>{{ $pacienteData->cd_atendimento }}</strong></h4>
        </li>
        <li class="list-inline-item ml-2">
            <span class="text-secondary">Prescrição:</span>
            <h4><strong>{{ $pacienteData->cd_pre_med }}</strong></h4>
        </li>
        <li class="list-inline-item ml-2">
            <span class="text-secondary">Solicitação:</span>
            <h4><strong>{{ $pacienteData->cd_solsai_pro }}</strong></h4>
        </li>
    </ul>
</div>

@forelse($prescricaoData as $prescricao)

    @php
    $statusPrescricao       = App\Http\Controllers\Home\Produtos\Paineis\Farmacia\Oncologia\PainelFarmOncoController::getStatusPrescricaoOncologia($pacienteData->cd_pre_med, $pacienteData->cd_solsai_pro);
    $statusPrescricao       = $statusPrescricao[0];
    @endphp

    <div class="container bg-gray pt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="material-icons" style="vertical-align:inherit; color:green;">how_to_reg</i>
                        <div><span class="texto_label_oncologia">PACIENTE PUNCIONADO</span></div>
                        <strong>{{ $statusPrescricao->puncao }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="material-icons" style="color:#665aff; vertical-align: inherit;">local_pharmacy</i>
                        <div><span class="texto_label_oncologia">AVALIAÇÃO FARMACÊUTICA</span></div>
                        <strong>{{ $statusPrescricao->tp_status_aval }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="material-icons" style="color:#53563b; vertical-align: inherit;">gradient</i>
                        <div><span class="texto_label_oncologia">MEDICAMENTO PREPARADO</span></div>
                        <strong>{{ $statusPrescricao->med_prep }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="material-icons" style="color:green; vertical-align: inherit;">beenhere</i>
                        <div><span class="texto_label_oncologia">RECEBIMENTO</span></div>
                        <strong>SEM INFORMAÇÃO</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (count($prescricao->itens) > 0)
        <div class="bg-gray-strong px-2 py-2">
            <span class="float-right font-weight-bold">
                <small>Validade:</small> <strong>{{ date("d/m/Y H:i:s", strtotime($prescricao->dt_validade)) }}</strong>
            </span>
            @if ($prescricao->itens_urgente > 0)
            <span class="px-1"><i class="fas fa-exclamation-triangle text-danger" title="Esta prescrição tem itens urgentes!"></i></span>
            @endif
            <strong>{{ $prescricao->cd_pre_med }} - [{{ date("d/m/Y H:i:s", strtotime($prescricao->dh_criacao)) }}] -  {{ $prescricao->prestador->nm_prestador }}</strong>
        </div>

        <table class="table table-hover table-striped">
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
                    <td class="{{ ($item->sn_urgente == 'S') ? 'text-danger' : null }}">{{ $item->cd_itpre_med }}</td>
                    <td width="450px" class="{{ ($item->sn_urgente == 'S') ? 'text-danger' : null }}">
                        {{ $item->tipoItemPrescricao->ds_tip_presc }}
                        <br/>
                        <span class="text-uppercase text-muted">{{ $item->ds_tip_fre }}</span>
                    </td>
                    <td>
                        <div class="" style="width: 600px; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch;">
                            @php 
                            $horarios = App\Http\Controllers\Home\Produtos\Paineis\Enfermagem\PainelEnfChecagemController::horariosItemPrescricao($item->cd_itpre_med);
                            @endphp

                            @forelse ($horarios as $horario)
                            
                            @php
                            $popoverContent = '';
                            $popoverContent .= '<div class="text-center mb-1"></div>';
                            $popoverContent .= ($horario->nm_usuario_checagem) ? '<div class="mb-2"><small>Por:</small><div><strong>'.$horario->nm_usuario_checagem.'</strong></div></div>' : null;
                            $popoverContent .= ($horario->sn_suspenso == 'S') ? '<div class="mb-3"><small>Motivo:</small><div class="text-justify"><strong>'.$horario->ds_justificativa_checagem.'</strong></div></div>' : null;
                            $popoverContent .= ($horario->sn_suspenso == 'S') ? '<div class="mb-3"><small>Justificativa:</small><div class="text-justify"><strong>'.$horario->ds_justificativa.'</strong></div></div>' : null;
                            $popoverContent .= '<ul class="list-unstyled">';
                            $popoverContent .= '<li><small>Última Modificação:</small><br/><strong>'.(($horario->dh_modificacao) ? $horario->dh_modificacao : "-").'</strong></li>';
                            $popoverContent .= '<li><small>Aprazamento:</small><br/><strong>'.(($horario->dh_medicacao) ? $horario->dh_medicacao : "-").'</strong></li>';
                            $popoverContent .= '<li><small>Avaliação Farmacêutica:</small><br/><strong>'.(($horario->dh_avaliacao) ? $horario->dh_avaliacao : "-").'</strong></li>';
                            $popoverContent .= '<li><small>Dispensação:</small><br/><strong>'.(($horario->dh_mvto_estoque) ? $horario->dh_mvto_estoque : "-").'</strong></li>';
                            $popoverContent .= '<li><small>Recebimento:</small><br/><strong>'.(($horario->dh_recebimento) ? $horario->dh_recebimento : "-").'</strong></li>';
                            $popoverContent .= '<li><small>Checagem:</small><br/><strong>'.(($horario->dh_checagem) ? $horario->dh_checagem : "-").'</strong></li>';
                            // $popoverContent .= '<li><small>Horário Anterior:</small><br/><strong>'.(($horario->DH_MEDICACAO_ANTERIOR) ? $horario->DH_MEDICACAO_ANTERIOR : '-').'</strong></li>';
                            $popoverContent .= '</ul>';
                            @endphp

                            <div class="my-1 mr-3" style="display: inline-block; float: none; width: 100px"><div class="text-center text-secondary"><small>{{ $horario->dt_medicacao }}</small></div>
                                <a tabindex="0" style="text-decoration: none;" class="popover-dismiss" data-toggle="popover" data-placement="bottom" data-content='{{ $popoverContent }}'>
                                @if($horario->tempo_prox_minutos <= 60 && $horario->tempo_prox_minutos >= 0 && is_null($horario->dh_checagem))
                                <div class="card my-0 cursor-pointer shadow bg-warning">
                                @elseif($horario->tempo_prox_minutos < 0)
                                <div class="card my-0 cursor-pointer shadow bg-danger">
                                @else
                                <div class="card my-0 cursor-pointer shadow">
                                @endif
                                    @if($horario->dh_checagem && $horario->sn_suspenso == 'N')
                                    <div class="card-body p-1 text-center">
                                        {{ $horario->hr_medicacao }}
                                        <br>
                                        <span class="badge badge-success">CHECADO</span>
                                    </div>
                                    @elseif($horario->dh_checagem && $horario->sn_suspenso == 'S')
                                    <div class="card-body p-1 text-center">
                                        <del>
                                            {{ $horario->hr_medicacao }}
                                            <br>
                                            <span class="badge badge-danger">SUSPENSO</span>
                                        </del>
                                    </div>
                                    @elseif($horario->tempo_prox_minutos > 0 && $horario->tempo_prox_minutos > 60)
                                    <div class="card-body p-1 text-center">
                                        {{ $horario->hr_medicacao }}
                                        <br>
                                        <span class="badge badge-primary">APRAZADO</span>
                                    </div>
                                    @elseif($horario->tempo_prox_minutos > 0)
                                    <div class="card-body p-1 text-center">
                                        {{ $horario->hr_medicacao }}
                                        <br>
                                        <small>PRÓXIMO</small>
                                    </div>
                                    @elseif($horario->tempo_prox_minutos < 0)
                                    <div class="card-body p-1 text-center">
                                        {{ $horario->hr_medicacao }}
                                        <br>
                                        <small>ATRASADO</small>
                                    </div>
                                    @else
                                    <div class="card-body p-1 text-center">
                                        {{ $horario->hr_medicacao }}
                                        <br>
                                        <span class="badge badge-primary">APRAZADO</span>
                                    </div>
                                    @endif
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
    <div class="text-center">
        <i class="fas fa-file-medical fa-lg"></i>
        <p>Nenhuma prescrição válida</p>
    </div>
@endforelse

<script>
    $('.popover-dismiss').popover({
        html: true,
        trigger: "focus",
    })
</script>