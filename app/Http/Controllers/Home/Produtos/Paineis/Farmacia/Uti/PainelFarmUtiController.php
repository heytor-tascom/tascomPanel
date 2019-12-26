<?php

namespace App\Http\Controllers\Home\Produtos\Paineis\Farmacia\Uti;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

use App\Models\Produto;
use App\Models\MV\Atendimento;
use App\Models\MV\Estoque;
use App\Models\MV\ItemPrescricao;
use App\Models\MV\Paciente;
use App\Models\MV\Prescricao;
use App\Models\MV\Setor;


class PainelFarmUtiController extends Controller
{
    public function index(Request $request)
    {
      $produto  = Produto::find(3);
      $title    = $produto->nm_produto;
      $tempoAtt = $produto->tempo_atualizacao;

      $filterSetores      = [];
      $estoques           = [];

      $params             = $request->only('setores', 'estoque');
      $metodo             = $request->only('aba');
      $metodo             = (isset($metodo['aba'])) ? $metodo['aba'] : 'todas';
      $estoque            = (isset($params['estoque'])) ? $params['estoque'] : 18;

      if (isset($params['setores'])) {
          $filterSetores = $params['setores'];
          $filterSetores = explode(',', $filterSetores);
          $estoques      = Estoque::join('config_estoque', 'config_estoque.cd_estoque', '=', 'estoque.cd_estoque')
                                  ->join('setor', 'setor.cd_setor', '=', 'config_estoque.cd_setor')
                                  ->whereIn('setor.cd_setor', $filterSetores)
                                  ->selectRaw('DISTINCT estoque.cd_estoque as cd_estoque, ds_estoque')
                                  ->orderBy('estoque.ds_estoque')
                                  ->get();
      }

      $setor = new Setor;
      $setores = $setor->getByTipo(['P']);

      $todas            = self::todas($filterSetores, $estoque);
      $avulsas          = self::avulsas($filterSetores, $estoque);
      $transferencias   = self::transferencias($filterSetores, $estoque);
      $devolucoes       = self::devolucoes($filterSetores, $estoque);
      $controlados      = self::controlados($filterSetores, $estoque);
      $atendidas        = self::atendidas($filterSetores, $estoque);
      $setorSolicitacao = self::setorSolicitacao($filterSetores, $estoque);

      switch ($metodo) {

          case 'todas':
          $lista = $todas;
          break;

          case 'avulsas':
          $lista = $avulsas;
          break;            

          case 'transferencias':
          $lista = $transferencias;
          break;            

          case 'devolucoes':
          $lista = $devolucoes;
          break;          
          
          case 'atendidas':
          $lista = $atendidas;
          break;            

          case 'controlados':
          $lista = $controlados;
          break;   
          
          case 'setor':
          $lista = $setorSolicitacao;
          break;             

          default:
          $lista = [];                
          break;
      }                            
      $aba = $metodo;
      return view("home.produtos.paineis.farmacia.central.index", compact('todas', 'avulsas', 'transferencias', 'devolucoes', 'controlados', 'atendidas', 'setorSolicitacao', 'lista', 'title', 'tempoAtt', 'setores', 'aba', 'estoque', 'estoques'));
    }

    public static function todas($parSetores, $parEstoque)
    {
        $setores = "'".implode("','",array_filter($parSetores))."'";
        
        return DB::connection('oracle')
                    ->select(
                        "SELECT solsai_pro.cd_solsai_pro,
                        TO_CHAR((SELECT Min(h.dh_medicacao) FROM hritsolsai_pro h, itsolsai_pro i 
                        WHERE  h.cd_Itsolsai_Pro = i.cd_Itsolsai_Pro
                        AND i.cd_solsai_pro = solsai_pro.cd_solsai_pro 
                        ),'DD/MM/RRRR HH24:MI') pri_necessidade,
                        setor.nm_setor, 
                        solsai_pro.tp_solsai_pro, 
                        leito.ds_leito,
                        solsai_pro.tp_situacao, 
                        solsai_pro.cd_atendimento, 
                        paciente.nm_paciente, 
                        turno_setor.ds_turno,  
                        solsai_pro.sn_urgente, 
                        solsai_pro.tp_origem_solicitacao,
                        to_char(paciente.dt_nascimento,'dd/mm/rrrr') DT_NASCIMENTO,
                        SOLSAI_PRO.DT_SOLSAI_PRO,
                        SOLSAI_PRO.HR_SOLSAI_PRO,
                        'N' SN_PSCOTROPICO
                        FROM   solsai_pro, 
                        atendime, 
                        paciente, 
                        prestador, 
                        turno_setor, 
                        setor,
                        leito
                        WHERE solsai_pro.cd_atendimento = atendime.cd_atendimento 
                        AND atendime.cd_paciente = paciente.cd_paciente 
                        AND solsai_pro.cd_prestador = prestador.cd_prestador
                        AND leito.cd_leito = atendime.cd_leito 
                        AND solsai_pro.cd_turno = turno_setor.cd_turno(+) 
                        AND solsai_pro.cd_setor(+) = setor.cd_setor 
                        AND solsai_pro.cd_setor in ($setores)
                        AND solsai_pro.cd_estoque = $parEstoque
                        AND solsai_pro.tp_situacao IN ( 'P' ) 
                        AND solsai_pro.tp_solsai_pro IN ( 'P' )
                        AND Trunc(solsai_pro.dt_solsai_pro) >= Trunc(SYSDATE) 
                        AND SOLSAI_PRO.CD_SOLSAI_PRO IN (
                              SELECT CD_SOLSAI_PRO FROM ITSOLSAI_PRO WHERE CD_PRODUTO IN (
                                SELECT CD_PRODUTO FROM PRODUTO WHERE sn_pscotropico = 'N'
                              )
                            )
                        AND solsai_pro.cd_turno IN (
                        SELECT turno_setor.cd_turno
                        FROM turno_setor WHERE 
                        To_char(SYSDATE + ((turno_setor.qt_minutos_impressao / 60) / 24), 'HH24:MI:SS')
                        BETWEEN To_char(turno_setor.hr_inicial, 'HH24:MI:SS') AND To_char(turno_setor.hr_final - (1/60/60/24), 'HH24:MI:SS') 
                        or (solsai_pro.ds_obs like '%URGENTE%') or (solsai_pro.ds_obs like '%ENTREGA%IMEDIATA%')
                        )     
                        ORDER BY   
                            tp_situacao DESC,  
                            sn_urgente DESC,                                                                                 
                            dt_solsai_pro ASC, 
                            hr_solsai_pro ASC "); 
                
    }



    public static function avulsas($parSetores, $parEstoque)
    {
        $setores = "'".implode("','",array_filter($parSetores))."'";
        
        return DB::connection('oracle')
                    ->select(
                        "SELECT solsai_pro.cd_solsai_pro,
                        TO_CHAR(solsai_pro.hr_solsai_pro,'DD/MM/RRRR HH24:MI') pri_necessidade,
                        setor.nm_setor, 
                        solsai_pro.tp_solsai_pro, 
                        leito.ds_leito,
                        solsai_pro.tp_situacao, 
                        solsai_pro.cd_atendimento, 
                        paciente.nm_paciente, 
                        turno_setor.ds_turno,  
                        solsai_pro.sn_urgente, 
                        solsai_pro.tp_origem_solicitacao,
                        to_char(paciente.dt_nascimento,'dd/mm/rrrr') DT_NASCIMENTO,
                        SOLSAI_PRO.DT_SOLSAI_PRO,
                        SOLSAI_PRO.HR_SOLSAI_PRO,
                        'N' SN_PSCOTROPICO
                        FROM   solsai_pro, 
                        atendime, 
                        paciente, 
                        prestador, 
                        turno_setor, 
                        setor,
                        leito
                 WHERE  solsai_pro.cd_atendimento = atendime.cd_atendimento 
                        AND atendime.cd_paciente = paciente.cd_paciente 
                        AND solsai_pro.cd_prestador = prestador.cd_prestador 
                        AND solsai_pro.cd_turno = turno_setor.cd_turno(+) 
                        AND solsai_pro.cd_setor(+) = setor.cd_setor 
                        AND atendime.cd_leito = leito.cd_leito
                        AND solsai_pro.tp_situacao IN ( 'P' ) 
                        AND solsai_pro.tp_solsai_pro IN ( 'P', 'S' ) 
                        AND SOLSAI_PRO.TP_ORIGEM_SOLICITACAO = 'AVU'
                        AND solsai_pro.cd_setor in ($setores)
                        AND SOLSAI_PRO.CD_SOLSAI_PRO IN (
                              SELECT CD_SOLSAI_PRO FROM ITSOLSAI_PRO WHERE CD_PRODUTO IN (
                                SELECT CD_PRODUTO FROM PRODUTO WHERE sn_pscotropico = 'N'
                              )
                            )     
                        AND solsai_pro.cd_estoque = $parEstoque    
                        AND Trunc(solsai_pro.dt_solsai_pro) = Trunc(SYSDATE) 
                        ORDER BY   tp_situacao DESC,
                        tp_solsai_pro desc,  
                        sn_urgente DESC,
                        dt_solsai_pro ASC, 
                        hr_solsai_pro ASC  ");
                                    
    }

    public static function transferencias($parSetores, $parEstoque)
    {
        $setores = "'".implode("','",array_filter($parSetores))."'";
        
        return DB::connection('oracle')
                    ->select(
                        "SELECT SOLSAI_PRO.CD_SOLSAI_PRO, 
                        '' PRI_NECESSIDADE,
                        nvl((select nm_setor from setor where cd_setor = solsai_pro.cd_setor),
                        (SELECT DS_ESTOQUE FROM ESTOQUE WHERE CD_ESTOQUE = solsai_pro.cd_estoque_solicitante)) NM_SETOR,
                        SOLSAI_PRO.TP_SOLSAI_PRO,
                        '' DS_LEITO,
                        SOLSAI_PRO.TP_SITUACAO,    
                        '' CD_ATENDIMENTO,
                        '' NM_PACIENTE,
                        '' DS_TURNO,
                        SOLSAI_PRO.SN_URGENTE,
                        solsai_pro.tp_origem_solicitacao,
                        '' DT_NASCIMENTO,                          
                        (SELECT DISTINCT(SN_PSCOTROPICO) FROM PRODUTO WHERE CD_PRODUTO IN (
                                 SELECT CD_PRODUTO FROM ITSOLSAI_PRO WHERE CD_SOLSAI_PRO = SOLSAI_PRO.CD_SOLSAI_PRO
                              ) AND ROWNUM = 1) SN_PSCOTROPICO                       
                        FROM   SOLSAI_PRO
                                                
                        WHERE  SOLSAI_PRO.CD_ESTOQUE = $parEstoque
                        AND SOLSAI_PRO.TP_SITUACAO IN ( 'P') 
                        AND SOLSAI_PRO.TP_SOLSAI_PRO IN ( 'E' )
                        AND TRUNC(SOLSAI_PRO.DT_SOLSAI_PRO) = TRUNC(SYSDATE) 
                        -- AND SOLSAI_PRO.CD_SOLSAI_PRO IN (
                        --       SELECT CD_SOLSAI_PRO FROM ITSOLSAI_PRO WHERE CD_PRODUTO IN (
                        --         SELECT CD_PRODUTO FROM PRODUTO WHERE sn_pscotropico = 'N'
                        --       )
                        --     )
                        ORDER BY   
                                tp_situacao DESC,
                                sn_urgente DESC,
                                dt_solsai_pro ASC, 
                                hr_solsai_pro ASC     "); 
                
    }

    public static function setorSolicitacao($parSetores, $parEstoque)
    {
        $setores = "'".implode("','",array_filter($parSetores))."'";
        
        return DB::connection('oracle')
                    ->select(
                        "SELECT SOLSAI_PRO.CD_SOLSAI_PRO, 
                        '' PRI_NECESSIDADE,
                        nvl((select nm_setor from setor where cd_setor = solsai_pro.cd_setor),
                        (SELECT DS_ESTOQUE FROM ESTOQUE WHERE CD_ESTOQUE = solsai_pro.cd_estoque_solicitante)) NM_SETOR,
                        SOLSAI_PRO.TP_SOLSAI_PRO,
                        '' DS_LEITO,
                        SOLSAI_PRO.TP_SITUACAO,    
                        '' CD_ATENDIMENTO,
                        '' NM_PACIENTE,
                        '' DS_TURNO,
                        SOLSAI_PRO.SN_URGENTE,
                        solsai_pro.tp_origem_solicitacao,
                        '' DT_NASCIMENTO,
                        'N' SN_PSCOTROPICO
                                                
                        FROM   SOLSAI_PRO
                                                
                        WHERE  SOLSAI_PRO.CD_ESTOQUE = $parEstoque
                        AND SOLSAI_PRO.TP_SITUACAO IN ( 'P') 
                        AND SOLSAI_PRO.TP_SOLSAI_PRO IN ( 'S' )
                        AND TRUNC(SOLSAI_PRO.DT_SOLSAI_PRO) = TRUNC(SYSDATE) 
                        AND SOLSAI_PRO.CD_SOLSAI_PRO IN (
                              SELECT CD_SOLSAI_PRO FROM ITSOLSAI_PRO WHERE CD_PRODUTO IN (
                                SELECT CD_PRODUTO FROM PRODUTO WHERE sn_pscotropico = 'N'
                              )
                            )
                        ORDER BY   
                                tp_situacao DESC,
                                sn_urgente DESC,
                                dt_solsai_pro ASC, 
                                hr_solsai_pro ASC     "); 
                
    }

    public static function devolucoes($parSetores, $parEstoque)
    {
        $setores = "'".implode("','",array_filter($parSetores))."'";
        
        return DB::connection('oracle')
                    ->select(
                        "SELECT solsai_pro.cd_solsai_pro,
                        TO_CHAR(solsai_pro.hr_solsai_pro,'DD/MM/RRRR HH24:MI') pri_necessidade,
                        setor.nm_setor, 
                        solsai_pro.tp_solsai_pro, 
                        leito.ds_leito,
                        solsai_pro.tp_situacao, 
                        solsai_pro.cd_atendimento, 
                        paciente.nm_paciente, 
                        turno_setor.ds_turno,  
                        solsai_pro.sn_urgente, 
                        solsai_pro.tp_origem_solicitacao,
                        to_char(paciente.dt_nascimento,'dd/mm/rrrr') DT_NASCIMENTO,
                        SOLSAI_PRO.DT_SOLSAI_PRO,
                        SOLSAI_PRO.HR_SOLSAI_PRO,
                        (SELECT DISTINCT(SN_PSCOTROPICO) FROM PRODUTO WHERE CD_PRODUTO IN (
                                 SELECT CD_PRODUTO FROM ITSOLSAI_PRO WHERE CD_SOLSAI_PRO = SOLSAI_PRO.CD_SOLSAI_PRO
                              ) AND ROWNUM = 1) SN_PSCOTROPICO
                        FROM   solsai_pro, 
                        atendime, 
                        paciente, 
                        prestador, 
                        turno_setor, 
                        setor,
                        leito
                 WHERE  solsai_pro.cd_atendimento = atendime.cd_atendimento 
                        AND atendime.cd_paciente = paciente.cd_paciente 
                        AND solsai_pro.cd_prestador = prestador.cd_prestador 
                        AND solsai_pro.cd_turno = turno_setor.cd_turno(+) 
                        AND solsai_pro.cd_setor(+) = setor.cd_setor 
                             AND solsai_pro.cd_setor in ($setores)
                        AND  ATENDIME.CD_LEITO = LEITO.CD_LEITO    
                        AND solsai_pro.cd_estoque = $parEstoque    
                          AND solsai_pro.tp_situacao IN ( 'P' ) 
                        AND solsai_pro.tp_solsai_pro IN ( 'C')
                        AND Trunc(solsai_pro.dt_solsai_pro) = Trunc(SYSDATE) 
                        -- AND SOLSAI_PRO.CD_SOLSAI_PRO IN (
                        --       SELECT CD_SOLSAI_PRO FROM ITSOLSAI_PRO WHERE CD_PRODUTO IN (
                        --         SELECT CD_PRODUTO FROM PRODUTO WHERE sn_pscotropico = 'N'
                        --       )
                        --     )
                                                   ORDER BY   
                                                               tp_situacao DESC,
                                                               tp_solsai_pro desc,  
                                                               sn_urgente DESC,
                                                               dt_solsai_pro ASC, 
                                                               hr_solsai_pro ASC    "); //, [":parSetores" => $setores]
                
    }

    public static function atendidas($parSetores, $parEstoque)
    {
        $setores = "'".implode("','",array_filter($parSetores))."'";
        
        return DB::connection('oracle')
                    ->select(
                        "SELECT * FROM (
                            SELECT SOLSAI_PRO.CD_SOLSAI_PRO,
                            TO_CHAR((SELECT MIN(H.DH_MEDICACAO) FROM HRITSOLSAI_PRO H, ITSOLSAI_PRO I 
                            WHERE  H.CD_ITSOLSAI_PRO = I.CD_ITSOLSAI_PRO
                            AND I.CD_SOLSAI_PRO = SOLSAI_PRO.CD_SOLSAI_PRO 
                            ),'DD/MM/RRRR HH24:MI') PRI_NECESSIDADE,
                            SETOR.NM_SETOR, 
                            SOLSAI_PRO.TP_SOLSAI_PRO, 
                            LEITO.DS_LEITO,
                            SOLSAI_PRO.TP_SITUACAO, 
                            TO_CHAR(SOLSAI_PRO.CD_ATENDIMENTO) CD_ATENDIMENTO, 
                            PACIENTE.NM_PACIENTE, 
                            TURNO_SETOR.DS_TURNO,  
                            SOLSAI_PRO.SN_URGENTE, 
                            solsai_pro.tp_origem_solicitacao,
                            TO_CHAR(PACIENTE.DT_NASCIMENTO,'DD/MM/RRRR') DT_NASCIMENTO,
                              (SELECT DISTINCT(SN_PSCOTROPICO) FROM PRODUTO WHERE CD_PRODUTO IN (
                                 SELECT CD_PRODUTO FROM ITSOLSAI_PRO WHERE CD_SOLSAI_PRO = SOLSAI_PRO.CD_SOLSAI_PRO
                              ) AND ROWNUM = 1) SN_PSCOTROPICO

                            FROM   SOLSAI_PRO, 
                            ATENDIME, 
                            PACIENTE, 
                            PRESTADOR, 
                            TURNO_SETOR, 
                            SETOR,
                            LEITO
                        
                            WHERE  SOLSAI_PRO.CD_ATENDIMENTO = ATENDIME.CD_ATENDIMENTO 
                            AND ATENDIME.CD_PACIENTE = PACIENTE.CD_PACIENTE 
                            AND SOLSAI_PRO.CD_PRESTADOR = PRESTADOR.CD_PRESTADOR 
                            AND SOLSAI_PRO.CD_TURNO = TURNO_SETOR.CD_TURNO(+) 
                            AND SOLSAI_PRO.CD_SETOR(+) = SETOR.CD_SETOR 
                            AND ATENDIME.CD_LEITO = LEITO.CD_LEITO
                            AND SOLSAI_PRO.CD_SETOR IN ($setores)
                            AND SOLSAI_PRO.CD_ESTOQUE = $parEstoque                    
                            AND SOLSAI_PRO.TP_SITUACAO IN ( 'C' ,'S' ) 
                            AND SOLSAI_PRO.TP_SOLSAI_PRO IN ( 'P', 'C' ) 
                            AND TRUNC(SOLSAI_PRO.DT_SOLSAI_PRO) = TRUNC(SYSDATE - 6/24) 
                                            
                        UNION ALL
                                            
                        SELECT SOLSAI_PRO.CD_SOLSAI_PRO, 
                                '' PRI_NECESSIDADE,
                                nvl((select nm_setor from setor where cd_setor = solsai_pro.cd_setor),
                                (SELECT DS_ESTOQUE FROM ESTOQUE WHERE CD_ESTOQUE = solsai_pro.cd_estoque_solicitante)) NM_SETOR,
                                SOLSAI_PRO.TP_SOLSAI_PRO,
                                '' DS_LEITO,
                                SOLSAI_PRO.TP_SITUACAO,    
                                '' CD_ATENDIMENTO,
                                '' NM_PACIENTE,
                                '' DS_TURNO,
                                SOLSAI_PRO.SN_URGENTE,
                                solsai_pro.tp_origem_solicitacao,
                                '' DT_NASCIMENTO,                          
                              (SELECT DISTINCT(SN_PSCOTROPICO) FROM PRODUTO WHERE CD_PRODUTO IN (
                                 SELECT CD_PRODUTO FROM ITSOLSAI_PRO WHERE CD_SOLSAI_PRO = SOLSAI_PRO.CD_SOLSAI_PRO
                              ) AND ROWNUM = 1) SN_PSCOTROPICO


                                FROM   SOLSAI_PRO
                        
                                WHERE  SOLSAI_PRO.CD_ESTOQUE = $parEstoque
                                AND SOLSAI_PRO.TP_SITUACAO IN ( 'C', 'S') 
                                AND SOLSAI_PRO.TP_SOLSAI_PRO IN ( 'E','S' )
                                AND TRUNC(SOLSAI_PRO.DT_SOLSAI_PRO) = TRUNC(SYSDATE) 
                                )
                        ORDER  BY TP_SITUACAO,NM_PACIENTE "); 
                
    }

    public static function controlados($parSetores, $parEstoque)
    {
        $setores = "'".implode("','",array_filter($parSetores))."'";
        
        return DB::connection('oracle')
                    ->select(
                        "SELECT SOLSAI_PRO.CD_SOLSAI_PRO,
                            TO_CHAR((SELECT MIN(H.DH_MEDICACAO) FROM HRITSOLSAI_PRO H, ITSOLSAI_PRO I 
                            WHERE  H.CD_ITSOLSAI_PRO = I.CD_ITSOLSAI_PRO
                            AND I.CD_SOLSAI_PRO = SOLSAI_PRO.CD_SOLSAI_PRO 
                            ),'DD/MM/RRRR HH24:MI') PRI_NECESSIDADE,
                            SETOR.NM_SETOR, 
                            SOLSAI_PRO.TP_SOLSAI_PRO, 
                            LEITO.DS_LEITO,
                            SOLSAI_PRO.TP_SITUACAO, 
                            TO_CHAR(SOLSAI_PRO.CD_ATENDIMENTO) CD_ATENDIMENTO, 
                            PACIENTE.NM_PACIENTE, 
                            TURNO_SETOR.DS_TURNO,  
                            SOLSAI_PRO.SN_URGENTE, 
                            solsai_pro.tp_origem_solicitacao,
                            TO_CHAR(PACIENTE.DT_NASCIMENTO,'DD/MM/RRRR') DT_NASCIMENTO,
                            SOLSAI_PRO.DT_SOLSAI_PRO,
                            SOLSAI_PRO.HR_SOLSAI_PRO,
                            'S' SN_PSCOTROPICO
                        
                            FROM   SOLSAI_PRO, 
                            ATENDIME, 
                            PACIENTE, 
                            PRESTADOR, 
                            TURNO_SETOR, 
                            SETOR,
                            LEITO
                        
                            WHERE solsai_pro.cd_atendimento = atendime.cd_atendimento 
                            AND atendime.cd_paciente = paciente.cd_paciente 
                            AND solsai_pro.cd_prestador = prestador.cd_prestador
                            AND leito.cd_leito = atendime.cd_leito 
                            AND solsai_pro.cd_turno = turno_setor.cd_turno(+) 
                            AND solsai_pro.cd_setor(+) = setor.cd_setor 
                            AND solsai_pro.cd_setor in ($setores)
                            AND solsai_pro.cd_estoque = $parEstoque
                            AND solsai_pro.tp_situacao IN ( 'P' ) 
                            AND solsai_pro.tp_solsai_pro IN ( 'P', 'S' )
                            AND Trunc(solsai_pro.dt_solsai_pro) >= Trunc(SYSDATE) 
                            AND SOLSAI_PRO.CD_SOLSAI_PRO IN (
                                SELECT CD_SOLSAI_PRO FROM ITSOLSAI_PRO WHERE CD_PRODUTO IN (
                                    SELECT CD_PRODUTO FROM PRODUTO WHERE sn_pscotropico = 'S'
                                )
                                )
                            AND solsai_pro.cd_turno IN (
                            SELECT turno_setor.cd_turno
                            FROM turno_setor WHERE 
                            To_char(SYSDATE + ((turno_setor.qt_minutos_impressao / 60) / 24), 'HH24:MI:SS')
                            BETWEEN To_char(turno_setor.hr_inicial, 'HH24:MI:SS') AND To_char(turno_setor.hr_final - (1/60/60/24), 'HH24:MI:SS') 
                            or (solsai_pro.ds_obs like '%URGENTE%') or (solsai_pro.ds_obs like '%ENTREGA%IMEDIATA%')
                            )  
                                ORDER BY
                                        tp_situacao DESC,
                                        tp_solsai_pro desc,  
                                        sn_urgente DESC,
                                        dt_solsai_pro ASC,
                                        hr_solsai_pro ASC "); 
                
    }
}
