<?php

namespace App\Http\Controllers\Home\Produtos\Paineis\BlocoCirurgico;

use DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Produto;

class PainelAcompObstController extends Controller
{
   
    public function index(Request $request)
    {
        $produto = Produto::find(15);
        $title    = $produto->nm_produto;
        $tempoAtt = $produto->tempo_atualizacao;

//        $blocosGet           = [];
  
        $params             = $request->only('blocos');

        $filteblc = (isset($params['blocos'])) ? $params['blocos'] : 0;
        $filteblc = explode(',', $filteblc);

        $blocos            = self::blocos();
        $cirurgias         = self::sqlAcompCirurgia($filteblc);
        
        //dd($cirurgias);

        return view("home.produtos.paineis.blocoCirurgico.acompCirurgiaObst.index" , compact('blocos', 'tempoAtt', 'title', 'cirurgias'));
    }

    public static function blocos()
    {
        return DB::connection('oracle')
                    ->select("SELECT CD_CEN_CIR CD_BLOCO, DS_CEN_CIR NM_BLOCO FROM CEN_CIR WHERE CD_CEN_CIR = 1");
    }

    public static function sqlAcompCirurgia($parBlc)
    {
        $cdBlocos = "'".implode("','",array_filter($parBlc))."'";
        
        return DB::connection('oracle')
                    ->select("SELECT P_linha,
                    dt_nascimento,
                    nvl(nm_social,Paciente) nm_social_or_nome,
                    Hora,
                    Inicio,
                    hr_admissao_bloco,
                    Sala,
                    Aviso,
                    Paciente,
                    Sexo,
                    Cirurgia,
                    Cirurgiao,
                    Convenio,
                    LeitoPaciente LEITO,
                    Bloco STATUS_BLOCO,
                    Rpa STATUS_RPA,
                    Leito STATUS_LEITO,
                    SIGNIN,
                    SIGNOUT,
                    TIMEOUT,     
                    CONFIRMACAO,
                    CLASSIFICACAO,
                    unid_int,
                    mensagem,
                    cd_atendimento,
                    admissao_co,
                    TP_ATENDIMENTO
                    FROM (
                    SELECT ROWNUM P_linha,
                    dt_nascimento,
                    nm_social,
                    Hora,
                    Inicio,
                    hr_admissao_bloco,
                    Dt_Referencia,
                    Sala,
                    Aviso,
                    paciente,
                    Sexo,
                    SubStr(Cirurgia,1,12) Cirurgia,
                    Cirurgiao,
                    Convenio,
                    LeitoPaciente,
                    EntradaBloco Bloco,
                    EntradaRpa Rpa,
                    SaidaRpa Leito,
                    SIGNIN,
                    SIGNOUT,
                    TIMEOUT,     
                    CONFIRMACAO,
                    CLASSIFICACAO,
                    unid_int,
                    ProcessoAtual mensagem,
                    cd_atendimento,
                    admissao_co,
                    TP_ATENDIMENTO
                    FROM (
                    SELECT to_char( paciente.dt_nascimento, 'dd/mm/rrrr') dt_nascimento,
                    decode(paciente.nm_social_paciente, 'N', NULL , paciente.nm_social_paciente) nm_social,
                    aviso_cirurgia.nm_paciente Paciente,
                    DBAMV.FNCDES_P_STATUS_BLC(aviso_cirurgia.cd_aviso_cirurgia,'HORAADMISSAO') hr_admissao_bloco,
                    sal_cir.ds_resumida Sala,
                    (SELECT ui.ds_unid_int FROM aviso_cirurgia ac, atendime a, leito l, unid_int ui WHERE ac.cd_aviso_cirurgia = aviso_cirurgia.cd_aviso_cirurgia
                    AND ac.cd_atendimento = a.cd_atendimento
                    AND a.cd_leito = l.cd_leito
                    AND ui.cd_unid_int = l.cd_unid_Int ) unid_int,
                    aviso_cirurgia.cd_aviso_cirurgia Aviso,
                    To_Char((Nvl(age_cir.dt_inicio_age_cir, aviso_cirurgia.dt_aviso_cirurgia)),'dd/mm/yyyy hh24:mi') Hora,
                    To_Char(aviso_cirurgia.dt_inicio_anestesia,'hh24:mi') Inicio,
                    Nvl(age_cir.dt_inicio_age_cir, aviso_cirurgia.dt_aviso_cirurgia) Dt_Inicio_Cirurgia,
                    Decode(aviso_cirurgia.Tp_Situacao,'R',Nvl(aviso_cirurgia.dt_fim_cirurgia,aviso_cirurgia.dt_inicio_cirurgia), Nvl(age_cir.dt_inicio_age_cir, aviso_cirurgia.dt_aviso_cirurgia)) Dt_Referencia,
                    aviso_cirurgia.dt_inicio_anestesia Dt_Inicio_Anestesia,
                    Trunc((SYSDATE-Nvl(age_cir.dt_inicio_age_cir,aviso_cirurgia.dt_aviso_cirurgia))*24) Tempo,
                    Fn_Idade(Paciente.Dt_Nascimento,'a A') Idade,
                    Paciente.Tp_Sexo Sexo,
                    aviso_cirurgia.cd_atendimento cd_atendimento,
                    (SELECT DECODE(TP_ATENDIMENTO,'A','AMBULATORIAL','I','INTERNAÇÃO','EXTERNO') TP_ATENDIMENTO FROM ATENDIME WHERE
                    CD_ATENDIMENTO = AVISO_CIRURGIA.CD_ATENDIMENTO) TP_ATENDIMENTO,
                    (SELECT 'S' FROM ADMISSAO_CO WHERE CD_ATENDIMENTO = AVISO_CIRURGIA.CD_ATENDIMENTO) admissao_co,                    DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'PROCESSOATUAL',Aviso_Cirurgia.Cd_Multi_Empresa) ProcessoAtual,
                    DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'CIRURGIAO',Aviso_Cirurgia.Cd_Multi_Empresa) Cirurgiao,
                    DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'CIRURGIA',Aviso_Cirurgia.Cd_Multi_Empresa) Cirurgia,
                    DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'CONVENIO',Aviso_Cirurgia.Cd_Multi_Empresa) Convenio,
                    DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'LEITOPACIENTE',Aviso_Cirurgia.Cd_Multi_Empresa) LeitoPaciente,
                    DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'ENTRADABLOCO',Aviso_Cirurgia.Cd_Multi_Empresa) EntradaBloco,
                    DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'ENTRADARPA',Aviso_Cirurgia.Cd_Multi_Empresa) EntradaRpa,
                    DBAMV.FNCDES_PAINEL_BLOCO(Aviso_Cirurgia.Cd_Aviso_Cirurgia,Aviso_Cirurgia.Cd_Cen_Cir,'SAIDARPA',Aviso_Cirurgia.Cd_Multi_Empresa) SaidaRpa,
                    DBAMV.FNCDES_P_STATUS_BLC(aviso_cirurgia.cd_aviso_cirurgia,'CLASSIFICACAO') CLASSIFICACAO,
                    DBAMV.FNCDES_P_STATUS_BLC(aviso_cirurgia.cd_aviso_cirurgia,'SIGNIN') SIGNIN,
                    DBAMV.FNCDES_P_STATUS_BLC(aviso_cirurgia.cd_aviso_cirurgia,'SIGNOUT') SIGNOUT,
                    DBAMV.FNCDES_P_STATUS_BLC(aviso_cirurgia.cd_aviso_cirurgia,'TIMEOUT') TIMEOUT,     
                    DBAMV.FNCDES_P_STATUS_BLC(aviso_cirurgia.cd_aviso_cirurgia,'CONFIRMACAO') CONFIRMACAO,
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
                    AND cen_cir.cd_cen_cir in ($cdBlocos)
                    AND Nvl(age_cir.dt_inicio_age_cir, aviso_cirurgia.dt_aviso_cirurgia) >= SYSDATE - 0.3
                    ORDER BY Hora
                    ))"); 
                
    }
}
