<div class="row fixed-top bg-gray">
    <div class="col-md-1">
        <div class="logo-header text-center">
            <img src="{{ asset('rhp') }}/img/icones/logo_rhp.png" alt="">
        </div>
    </div>
    <div class="col-md-4 border-right">
        <div class="d-flex align-items-center" style="height: 100%;">
            <div class="text-painel align-middle text-center mx-2">
                {{ $title ?? '' }}
            </div>
            <div class="flex-fill ml-3">
                @if(isset($setores))
                @php
                $cdSetor    = (isset($_GET['setores'])) ? $_GET['setores'] : null;
                $cdSetor    = explode(",", $cdSetor);
                @endphp
                <select name="setores" id="" class="selectpicker" onchange="getPacientes()" data-live-search="true" data-selected-text-format="count" multiple>
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
            <div class="flex-fill ml-3">
                @if(count($estoques) > 0)
                @php
                $cdEstoque    = $_GET['estoque'];
                @endphp
                <select name="estoque" id="" class="selectpicker-estoque" onchange="getPacientes()" data-live-search="true">
                    @forelse($estoques as $estoque)
                    @if ($estoque->cd_estoque == $cdEstoque)
                    <option value="{{ $estoque->cd_estoque }}" selected>{{ $estoque->ds_estoque }}</option>
                    @else
                    <option value="{{ $estoque->cd_estoque }}">{{ $estoque->ds_estoque }}</option>
                    @endif
                    @empty
                    @endforelse
                </select>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6 py-2 text-center">
        <div class="d-flex flex-column pt-4" style="height: 100%;">
            <div class="row">
                <div class="col-md-12">
    
                      <button type="button" id="todas" class="btn btn-secondary " onclick="trocaMetodo('todas')">TODAS
                        <span class="badge badge-danger ml-2" id="">{{ count($todas) ?? '0'}}</span>
                      </button>
    
                      <button type="button" id="avulsas" class="btn btn-secondary button-bar" onclick="trocaMetodo('avulsas')">AVULSAS
                        <span class="badge badge-danger ml-2 " id="">{{ count($avulsas) ?? '0'}}</span>
                      </button>
    
                      <button type="button" id="transferencias" class="btn btn-secondary button-bar" onclick="trocaMetodo('transferencias')">TRANSFERÊNCIAS
                        <span class="badge badge-danger ml-2 " id="">{{ count($transferencias) ?? '0'}}</span>
                      </button>
    
                      <button type="button" id="devolucoes" class="btn btn-secondary button-bar" onclick="trocaMetodo('devolucoes')">DEVOLUÇÕES
                        <span class="badge badge-danger ml-2 " id="">{{ count($devolucoes) ?? '0'}}</span>
                      </button>

                      <button type="button" id="controlados" class="btn btn-secondary button-bar" onclick="trocaMetodo('controlados')">CONTROLADOS
                            <span class="badge badge-danger ml-2 " id="">{{ count($controlados) ?? '0'}}</span>
                      </button>

                      <button type="button" id="setor" class="btn btn-secondary button-bar" onclick="trocaMetodo('setor')">SETOR
                            <span class="badge badge-danger ml-2 " id="">{{ count($setorSolicitacao) ?? '0'}}</span>
                      </button>
    
                      <button type="button" id="atendidas" class="btn btn-secondary button-bar" onclick="trocaMetodo('atendidas')">ATENDIDAS
                        <span class="badge badge-danger ml-2 " id="">{{ count($atendidas) ?? '0'}}</span>
                      </button>

                      @inject('helperSituacaoSolic', 'App\Http\Helpers\SituacaoSolicitacaoHelpers')

                      <button type="button" id="legendas" class="btn btn-secondary button-bar" data-toggle="popover" data-placement="bottom" data-trigger="focus" data-content='
                        <div class="row">
                          <div class="col-md-12" style="font-size:1.4em;"><i class="fas fa-user-injured" style="color:#16a085;"></i>&nbsp; Pacientes </div>
                        </div><hr/>

                        <div class="row">
                            <div class="col-md-12" style="font-size:1.4em;"><i class="fas fa-clinic-medical" style="color:#e67e22;"></i>&nbsp; Setores </div>
                        </div><hr/>

                        <div class="row">
                            <div class="col-md-12" style="font-size:1.4em;"><i class="fas fa-tablets" style="color:#34495e;"></i>&nbsp; Avulsas </div>
                        </div><hr/>

                        <div class="row">
                            <div class="col-md-12" style="font-size:1.4em;"><i class="fas fa-sync-alt" style="color:#f39c12;"></i>&nbsp; Transferências </div>
                        </div><hr/>

                        <div class="row">
                            <div class="col-md-12" style="font-size:1.4em;"><i class="fas fa-reply" style="color:#c0392b;"></i>&nbsp; Devoluções </div>
                        </div><hr/>    
                        
                        <div class="row">
                            <div class="col-md-12" style="font-size:1.4em;"><i class="fas fa-capsules text-warning"></i>&nbsp; Controlados </div>
                        </div><hr/>
                        
                        <div class="row">
                            <div class="col-md-12" style="font-size:1.4em;"><img src="{{asset("tascom")}}/img/icones/{{$helperSituacaoSolic::situacaoSolicitacao('P') }}.png"> &nbsp;Não Atendido </div>
                        </div><hr/>
                            
                        <div class="row">
                            <div class="col-md-12" style="font-size:1.4em;"><img src="{{asset("tascom")}}/img/icones/{{$helperSituacaoSolic::situacaoSolicitacao('C') }}.png">&nbsp; Parcial </div>
                        </div><hr/>
                                
                        <div class="row">
                            <div class="col-md-12" style="font-size:1.4em;"><img src="{{asset("tascom")}}/img/icones/{{$helperSituacaoSolic::situacaoSolicitacao('S') }}.png">&nbsp; Atendido </div>
                        </div><hr/>
                        
                        <div class="row">
                            <div class="col-md-12" style="font-size:1.4em;"><i class="fas fa-exclamation-triangle text-danger"></i>&nbsp; Urgente </div>
                        </div><hr/>
                        
                        <div class="row">
                            <div class="col-md-12" style="font-size:1.4em;"><i class="fas fa-exclamation-triangle "></i>&nbsp; Não Urgente </div>
                        </div>
                        '
                        >LEGENDAS</button>
                    
                  </div>
            </div>
        </div>
    </div>
</div>