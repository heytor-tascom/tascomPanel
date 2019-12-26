<?php

namespace App\Http\Controllers\Home\Produtos\Paineis\BlocoCirurgico;

use DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Produto;


class PainelAcompCirurgiaController extends Controller
{

    public function index(Request $request)
    {
        $produto = Produto::find(10);
        $title    = $produto->nm_produto;
        $tempoAtt = $produto->tempo_atualizacao;

        $blocos            = self::blocos();
        $cirurgias         = self::sqlAcompCirurgia();
        
        //dd($cirurgias);

        return view("home.produtos.paineis.blocoCirurgico.acompCirurgia.index" , compact('blocos', 'tempoAtt', 'title', 'cirurgias'));
    }

    public static function blocos()
    {
        return DB::connection('oracle')
                    ->select("SELECT CD_CEN_CIR CD_BLOCO, DS_CEN_CIR NM_BLOCO FROM CEN_CIR");
    }

    public static function sqlAcompCirurgia()
    {
        
        return DB::connection('oracle')
                    ->select(
                        "SELECT P_linha,
                        QtdeAvisos,
                        QtdeAvisosPaginar,
                        Hora,
                        Inicio,
                        Decode(Inicio,NULL,NULL,Atraso||MinAtraso|| ' ' || 'Min') Status,
                        Atraso,
                        Sala,
                        Aviso,
                        Paciente,
                        Idade,
                        Sexo,
                        Cirurgia,
                        Cirurgiao,
                        Convenio,
                        LeitoPaciente LEITO,
                        Bloco STATUS_BLOCO,
                        Rpa STATUS_RPA,
                        Leito STATUS_LEITO,
                        DCI,
                        Mensagem
                        FROM (
                        SELECT ROWNUM P_linha,
                        QtdeAvisos,
                        QtdeAvisosPaginar,
                        Hora,
                        Inicio,
                        Dt_Referencia,
                        TempoPrevista,
                        TempoDataSaida,
                        DataPrevista,
                        DataSaidaRpa,
                        Decode(Inicio,NULL,NULL,StatusAtraso) Atraso,
                        Abs(MinAtraso) MinAtraso,
                        Sala,
                        Aviso,
                        SubStr(Paciente,1,15)paciente,
                        Idade,
                        Sexo,
                        SubStr(Cirurgia,1,12) Cirurgia,
                        SubStr(Cirurgiao,1,10) Cirurgiao,
                        Convenio,
                        LeitoPaciente,
                        EntradaBloco Bloco,
                        EntradaRpa Rpa,
                        SaidaRpa Leito,
                        Consent_Anestesia CIA,
                        Consent_Cirurgia CIC,
                        SituacaoDescricao DCI,
                        GuiaOpme GOP,
                        PROCESSOATUAL Mensagem
                        FROM (
                        SELECT Substr(aviso_cirurgia.nm_paciente,1,18)||Decode(Substr(aviso_cirurgia.nm_paciente,19,50),NULL,NULL,'...') Paciente,
                        sal_cir.ds_resumida Sala,
                        aviso_cirurgia.cd_aviso_cirurgia Aviso,
                        To_Char((Nvl(age_cir.dt_inicio_age_cir, aviso_cirurgia.dt_aviso_cirurgia)),'dd/mm/yyyy hh24:mi') Hora,
                        To_Char(aviso_cirurgia.dt_inicio_anestesia,'hh24:mi') Inicio,
                        Nvl(age_cir.dt_inicio_age_cir, aviso_cirurgia.dt_aviso_cirurgia) Dt_Inicio_Cirurgia,
                        Decode(aviso_cirurgia.Tp_Situacao,'R',Nvl(aviso_cirurgia.dt_fim_cirurgia,aviso_cirurgia.dt_inicio_cirurgia), Nvl(age_cir.dt_inicio_age_cir, aviso_cirurgia.dt_aviso_cirurgia)) Dt_Referencia,
                        aviso_cirurgia.dt_inicio_anestesia Dt_Inicio_Anestesia,
                        Trunc((SYSDATE-Nvl(age_cir.dt_inicio_age_cir,aviso_cirurgia.dt_aviso_cirurgia))*24) Tempo,
                        Nvl(aviso_cirurgia.ds_idade||''||Decode(aviso_cirurgia.ds_idade,NULL,NULL,Decode(aviso_cirurgia.tp_idade,'M','m','A','a')),Decode(Aviso_cirurgia.tp_situacao,'R',Fn_Idade(Paciente.Dt_Nascimento,'a')||'a',NULL)) Idade,
                        Paciente.Tp_Sexo Sexo,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'QTDEAVISOS',Aviso_Cirurgia.Cd_Multi_Empresa) QtdeAvisos,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'QTDEAVISOSPAGINAR',Aviso_Cirurgia.Cd_Multi_Empresa) QtdeAvisosPaginar,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'MINUTOSATRASO',Aviso_Cirurgia.Cd_Multi_Empresa) MinAtraso,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'STATUSATRASO',Aviso_Cirurgia.Cd_Multi_Empresa) StatusAtraso,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'CIRURGIAO',Aviso_Cirurgia.Cd_Multi_Empresa) Cirurgiao,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'CIRURGIA',Aviso_Cirurgia.Cd_Multi_Empresa) Cirurgia,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'CONVENIO',Aviso_Cirurgia.Cd_Multi_Empresa) Convenio,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'GUIAOPME',Aviso_Cirurgia.Cd_Multi_Empresa) GuiaOpme,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'CONSENTCIRURGIA',Aviso_Cirurgia.Cd_Multi_Empresa) Consent_Cirurgia,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'CONSENTANESTESIA',Aviso_Cirurgia.Cd_Multi_Empresa) Consent_Anestesia,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'SITUACAODESCRICAO',Aviso_Cirurgia.Cd_Multi_Empresa) SituacaoDescricao,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'PROCESSOATUAL',Aviso_Cirurgia.Cd_Multi_Empresa) ProcessoAtual,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'ENTRADABLOCO',Aviso_Cirurgia.Cd_Multi_Empresa) EntradaBloco,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'ENTRADARPA',Aviso_Cirurgia.Cd_Multi_Empresa) EntradaRpa,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'SAIDARPA',Aviso_Cirurgia.Cd_Multi_Empresa) SaidaRpa,
                        DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'LEITOPACIENTE',Aviso_Cirurgia.Cd_Multi_Empresa) LeitoPaciente,
                        Nvl(age_cir.dt_inicio_age_cir,aviso_cirurgia.dt_aviso_cirurgia) DataPrevista,
                        Nvl(Mov_Cc_Rpa.Dt_Saida_Rpa,aviso_cirurgia.dt_aviso_cirurgia) DataSaidaRpa,
                        Round((SYSDATE-Nvl(age_cir.dt_inicio_age_cir,aviso_cirurgia.dt_aviso_cirurgia))*24) TempoPrevista,
                        Round((SYSDATE-Nvl(Mov_Cc_Rpa.Dt_Saida_Rpa,(Nvl(age_cir.dt_inicio_age_cir,aviso_cirurgia.dt_aviso_cirurgia))))*24) TempoDataSaida
                        FROM dbamv.Aviso_Cirurgia,
                        dbamv.Paciente,
                        dbamv.Cen_Cir,
                        dbamv.Sal_Cir,
                        dbamv.Age_Cir,
                        dbamv.Mov_Cc_Rpa
                        WHERE Aviso_cirurgia.cd_cen_cir = Cen_Cir.Cd_Cen_Cir
                        AND Aviso_Cirurgia.cd_aviso_cirurgia = Mov_Cc_Rpa.cd_aviso_cirurgia(+)
                        AND Aviso_Cirurgia.Cd_paciente = Paciente.cd_Paciente (+)
                        AND Aviso_cirurgia.cd_aviso_cirurgia = Age_Cir.Cd_Aviso_Cirurgia(+)
                        AND Nvl(age_cir.cd_sal_cir, aviso_cirurgia.cd_sal_cir) = Sal_Cir.Cd_Sal_Cir
                        AND Aviso_cirurgia.tp_situacao IN ('A','G','T','R')
                        AND abs(Round((Decode(aviso_cirurgia.Tp_Situacao,'R',Nvl(Mov_Cc_Rpa.Dt_Saida_Rpa,sysdate), Nvl(age_cir.dt_inicio_age_cir, aviso_cirurgia.dt_aviso_cirurgia))-SYSDATE)*24,0))
                        Between 0 AND (SELECT VALOR FROM DBAMV.CONFIGURACAO WHERE CHAVE = 'QTDE_HORAS_AVISO_APOS_CIRURGIA' AND CD_SISTEMA = 'PAINEL' AND cd_multi_empresa = 1)
                        --AND Aviso_Cirurgia.Cd_Multi_Empresa = 1
                        --AND cen_cir.cd_cen_cir = :cdCenCir
                        AND Nvl(age_cir.dt_inicio_age_cir, aviso_cirurgia.dt_aviso_cirurgia) >= SYSDATE - 0.3
                        ORDER BY Hora
                        ))"); 
                
    }

}

   