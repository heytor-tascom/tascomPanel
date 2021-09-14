<?php
namespace App\Http\Helpers;

use DB;

class AlmoxarifadoHelpers
{
    public static function devolucoes()
    {
        // return DB::connection('oracle')
        //             ->select("SELECT    MVTO_ESTOQUE.CD_USUARIO CD_USUARIO,
        //                                 MVTO_ESTOQUE.CD_MVTO_ESTOQUE CD_MVTO_ESTOQUE_ME,
        //                                 MVTO_ESTOQUEDEV.CD_MVTO_ESTOQUE CD_MVTO_ESTOQUEDEV,
        //                                 MVTO_ESTOQUE.CD_MVTO_FILHA CD_MVTO_FILHA,
        //                                 MVTO_ESTOQUE.CD_SOLSAI_PRO CD_SOLSAI_PRO_ME,
        //                                 MVTO_ESTOQUE.CD_ATENDIMENTO CD_ATENDIMENTO_ME,
        //                                 MVTO_ESTOQUE.CD_ESTOQUE CD_ESTOQUE_ME,
        //                                 ESTOQUE.DS_ESTOQUE DS_ESTOQUE_ME,
        //                                 MVTO_ESTOQUE.CD_PRESTADOR CD_PRESTADOR,
        //                                 PRESTADOR.NM_MNEMONICO NM_MNEMONICO_ME,
        //                                 MVTO_ESTOQUE.CD_SETOR CD_SETOR_ME,
        //                                 SETOR.NM_SETOR NM_SETOR_ME,
        //                                 MVTO_ESTOQUE.CD_UNID_INT CD_UNID_INT_ME,
        //                                 UNID_INT.DS_UNID_INT DS_UNID_INT_ME,
        //                                 MVTO_ESTOQUE.DT_MVTO_ESTOQUE DT_MVTO_ESTOQUE_ME,
        //                                 TO_CHAR(MVTO_ESTOQUE.HR_MVTO_ESTOQUE, 'HH24:MI') HR_MVTO_ESTOQUE_ME,
        //                                 MVTO_ESTOQUE.NR_DOCUMENTO NR_DOCUMENTO_ME,
        //                                 PACIENTE.NM_PACIENTE NM_PACIENTE_ME,
        //                                 LEITO.CD_LEITO CD_LEITO_ME,
        //                                 LEITO.DS_LEITO DS_LEITO_ME,
        //                                 CONVENIO.CD_CONVENIO CD_CONVENIO_ME,
        //                                 CONVENIO.NM_CONVENIO DS_CONVENIO_ME,
        //                                 MVTO_ESTOQUE.DT_IMPRESSAO DT_IMPRESSAO,
        //                                 ITMVTO_ESTOQUE.CD_LOTE CD_LOTE_IE,
        //                                 ITMVTO_ESTOQUE.CD_MVTO_ESTOQUE CD_MVTO_ESTOQUE_IE,
        //                                 ITMVTO_ESTOQUE.CD_PRODUTO CD_PRODUTO_IE,
        //                                 PRODUTO.DS_PRODUTO DS_PRODUTO_IE,
        //                                 ITMVTO_ESTOQUE.CD_PRODUTO_KIT CD_PRODUTO_KIT,
        //                                 PRODUTO_KIT.DS_PRODUTO DS_PRODUTO_KIT,
        //                                 ITMVTO_ESTOQUE.CD_UNI_PRO CD_UNI_PRO_IE,
        //                                 UNI_PRO.DS_UNIDADE DS_UNIDADE_IE,
        //                                 ITMVTO_ESTOQUE.DT_VALIDADE DT_VALIDADE_IE,
        //                                 SUM( ITMVTO_ESTOQUE.QT_MOVIMENTACAO ) QT_MOVIMENTACAO,
        //                                 SUM( ITMVTO_ESTOQUE.QT_PERDA ) QT_PERDA_IE,
        //                                 MOTIVO_DIVERG_ATEND.DS_MOTIVO_DIVERG_ATEND,
        //                                 PACIENTE.DT_NASCIMENTO DT_NASCIMENTO,
        //                                 FORNECEDOR.CD_FORNECEDOR CD_FORNECEDOR_IE,
        //                                 FORNECEDOR.NM_FORNECEDOR NM_FORNECEDOR_IE
        //             FROM    DBAMV.MVTO_ESTOQUE MVTO_ESTOQUE,
        //                     DBAMV.MVTO_ESTOQUE MVTO_ESTOQUEDEV,
        //                     DBAMV.ITMVTO_ESTOQUE ITMVTO_ESTOQUE,
        //                     DBAMV.ESTOQUE ESTOQUE,
        //                     DBAMV.PRESTADOR PRESTADOR,
        //                     DBAMV.UNID_INT UNID_INT,
        //                     DBAMV.SETOR SETOR,
        //                     DBAMV.ATENDIME ATENDIME,
        //                     DBAMV.PACIENTE PACIENTE,
        //                     DBAMV.LEITO LEITO,
        //                     DBAMV.CONVENIO CONVENIO,
        //                     DBAMV.UNI_PRO UNI_PRO,
        //                     DBAMV.PRODUTO PRODUTO,
        //                     DBAMV.PRODUTO PRODUTO_KIT,
        //                     DBAMV.MOT_DEV MOT_DEV,
        //                     DBAMV.FORNECEDOR FORNECEDOR,
        //                     DBAMV.ITSOLSAI_PRO ITSOLSAI_PRO,
        //                     DBAMV.MOTIVO_DIVERG_ATEND MOTIVO_DIVERG_ATEND
        //             WHERE   ESTOQUE.CD_MULTI_EMPRESA            IN (1)
        //             AND     MVTO_ESTOQUE.CD_MVTO_ESTOQUE        IN (1)
        //             AND     ATENDIME.CD_MULTI_EMPRESA           IN (1)
        //             --AND     ITMVTO_ESTOQUE.CD_PRODUTO           = DECODE({V_AUX_PRODUTO}, 0, ITMVTO_ESTOQUE.CD_PRODUTO, NULL, ITMVTO_ESTOQUE.CD_PRODUTO,{V_AUX_PRODUTO})
        //             AND     MVTO_ESTOQUE.TP_MVTO_ESTOQUE        = 'C'
        //             AND     MVTO_ESTOQUE.CD_MVTO_ESTOQUE        = ITMVTO_ESTOQUE.CD_MVTO_ESTOQUE(+)
        //             AND     MVTO_ESTOQUE.CD_ESTOQUE             = ESTOQUE.CD_ESTOQUE
        //             AND     MVTO_ESTOQUE.CD_PRESTADOR           = PRESTADOR.CD_PRESTADOR(+)
        //             AND     MVTO_ESTOQUE.CD_UNID_INT            = UNID_INT.CD_UNID_INT(+)
        //             AND     MVTO_ESTOQUE.CD_SETOR               = SETOR.CD_SETOR(+)
        //             AND     MVTO_ESTOQUE.CD_ATENDIMENTO         = ATENDIME.CD_ATENDIMENTO(+)
        //             AND     ATENDIME.CD_PACIENTE                = PACIENTE.CD_PACIENTE(+)
        //             AND     ATENDIME.CD_LEITO                   = LEITO.CD_LEITO(+)
        //             AND     ATENDIME.CD_CONVENIO                = CONVENIO.CD_CONVENIO(+)
        //             AND     ITMVTO_ESTOQUE.CD_UNI_PRO           = UNI_PRO.CD_UNI_PRO(+)
        //             AND     ITMVTO_ESTOQUE.CD_PRODUTO           = PRODUTO.CD_PRODUTO(+)
        //             AND     ITMVTO_ESTOQUE.CD_FORNECEDOR        = FORNECEDOR.CD_FORNECEDOR(+)
        //             AND     MVTO_ESTOQUE.CD_MOT_DEV             = MOT_DEV.CD_MOT_DEV
        //             AND     ITMVTO_ESTOQUE.CD_PRODUTO_KIT       = PRODUTO_KIT.CD_PRODUTO(+)
        //             AND     MVTO_ESTOQUE.CD_MVTO_ESTOQUE        = MVTO_ESTOQUEDEV.CD_MVTO_FILHA(+)
        //             AND     ITMVTO_ESTOQUE.CD_ITSOLSAI_PRO      = ITSOLSAI_PRO.CD_ITSOLSAI_PRO(+)
        //             AND     ITSOLSAI_PRO.CD_MOTIVO_DIVERG_ATEND = MOTIVO_DIVERG_ATEND.CD_MOTIVO_DIVERG_ATEND(+)
        //             AND 	(
        //                         SELECT 	COUNT(*)
        //                         FROM 	DBAMV.ITMVTO_ESTOQUE
        //                         WHERE 	QT_MOVIMENTACAO = QT_RECEBIDO
        //                         AND 	CD_MVTO_ESTOQUE = MVTO_ESTOQUE.CD_MVTO_ESTOQUE
        //                     ) = 0
        //             GROUP BY    MVTO_ESTOQUE.CD_USUARIO,
        //                         MVTO_ESTOQUE.CD_MVTO_ESTOQUE,
        //                         MVTO_ESTOQUE.CD_MVTO_FILHA,
        //                         MVTO_ESTOQUEDEV.CD_MVTO_ESTOQUE,
        //                         MVTO_ESTOQUE.CD_SOLSAI_PRO,
        //                         MVTO_ESTOQUE.CD_ATENDIMENTO,
        //                         MVTO_ESTOQUE.CD_ESTOQUE,
        //                         ESTOQUE.DS_ESTOQUE,
        //                         MVTO_ESTOQUE.CD_PRESTADOR,
        //                         PRESTADOR.NM_MNEMONICO,
        //                         MVTO_ESTOQUE.CD_SETOR,
        //                         SETOR.NM_SETOR,
        //                         MVTO_ESTOQUE.CD_UNID_INT,
        //                         UNID_INT.DS_UNID_INT,
        //                         MVTO_ESTOQUE.DT_MVTO_ESTOQUE,
        //                         MVTO_ESTOQUE.HR_MVTO_ESTOQUE,
        //                         MVTO_ESTOQUE.NR_DOCUMENTO,
        //                         PACIENTE.NM_PACIENTE,
        //                         LEITO.CD_LEITO,
        //                         LEITO.DS_LEITO,
        //                         CONVENIO.CD_CONVENIO,
        //                         CONVENIO.NM_CONVENIO,
        //                         MVTO_ESTOQUE.DT_IMPRESSAO,
        //                         ITMVTO_ESTOQUE.CD_LOTE,
        //                         ITMVTO_ESTOQUE.CD_MVTO_ESTOQUE,
        //                         ITMVTO_ESTOQUE.CD_PRODUTO,
        //                         PRODUTO.DS_PRODUTO,
        //                         ITMVTO_ESTOQUE.CD_PRODUTO_KIT,
        //                         PRODUTO_KIT.DS_PRODUTO,
        //                         MOTIVO_DIVERG_ATEND.DS_MOTIVO_DIVERG_ATEND,
        //                         ITMVTO_ESTOQUE.CD_UNI_PRO,
        //                         UNI_PRO.DS_UNIDADE,
        //                         ITMVTO_ESTOQUE.DT_VALIDADE,
        //                         PACIENTE.DT_NASCIMENTO,
        //                         FORNECEDOR.CD_FORNECEDOR,
        //                         FORNECEDOR.NM_FORNECEDOR
        //             ORDER BY MVTO_ESTOQUE.CD_MVTO_ESTOQUE, PRODUTO_KIT.DS_PRODUTO ASC");

        return DB::connection('oracle')
                    ->select("  SELECT  MVTO_ESTOQUEDEV.CD_MVTO_ESTOQUE,
                                        MVTO_ESTOQUE.TP_MVTO_ESTOQUE, 
                                        MVTO_ESTOQUEDEV.CD_ESTOQUE_DESTINO,
                                        (SELECT DS_ESTOQUE FROM ESTOQUE WHERE CD_ESTOQUE = MVTO_ESTOQUEDEV.CD_ESTOQUE_DESTINO) AS DS_ESTOQUE_DESTINO,
                                        MVTO_ESTOQUEDEV.TP_STATUS_CONFIRMACAO,
                                        MVTO_ESTOQUE.CD_AVISO_CIRURGIA,
                                        MVTO_ESTOQUE.CD_USUARIO CD_USUARIO,
                                        MVTO_ESTOQUE.CD_MVTO_ESTOQUE CD_MVTO_ESTOQUE_ME,
                                        MVTO_ESTOQUEDEV.CD_MVTO_ESTOQUE CD_MVTO_ESTOQUEDEV,
                                        MVTO_ESTOQUEDEV.CD_USUARIO CD_USUARIODEV,
                                        MVTO_ESTOQUEDEV.CD_MVTO_FILHA CD_MVTO_FILHA,
                                        MVTO_ESTOQUE.CD_SOLSAI_PRO CD_SOLSAI_PRO_ME,
                                        MVTO_ESTOQUE.CD_ATENDIMENTO CD_ATENDIMENTO_ME,
                                        MVTO_ESTOQUE.CD_ESTOQUE CD_ESTOQUE_ME,
                                        ESTOQUE.DS_ESTOQUE DS_ESTOQUE_ME,
                                        MVTO_ESTOQUE.CD_PRESTADOR CD_PRESTADOR,
                                        PRESTADOR.NM_MNEMONICO NM_MNEMONICO_ME,
                                        MVTO_ESTOQUE.CD_SETOR CD_SETOR_ME,
                                        SETOR.NM_SETOR NM_SETOR_ME,
                                        MVTO_ESTOQUE.CD_UNID_INT CD_UNID_INT_ME,
                                        UNID_INT.DS_UNID_INT DS_UNID_INT_ME,
                                        MVTO_ESTOQUE.DT_MVTO_ESTOQUE DT_MVTO_ESTOQUE_ME,
                                        TO_CHAR(MVTO_ESTOQUE.DT_MVTO_ESTOQUE, 'DD/MM/RRRR') || ' ' || TO_CHAR(MVTO_ESTOQUE.HR_MVTO_ESTOQUE, 'HH24:MI') DH_MVTO_ESTOQUE_ME,
                                        MVTO_ESTOQUE.NR_DOCUMENTO NR_DOCUMENTO_ME,
                                        PACIENTE.NM_PACIENTE NM_PACIENTE_ME,
                                        LEITO.CD_LEITO CD_LEITO_ME,
                                        LEITO.DS_LEITO DS_LEITO_ME,
                                        CONVENIO.CD_CONVENIO CD_CONVENIO_ME,
                                        CONVENIO.NM_CONVENIO DS_CONVENIO_ME,
                                        MVTO_ESTOQUE.DT_IMPRESSAO DT_IMPRESSAO,
                                        PACIENTE.DT_NASCIMENTO DT_NASCIMENTO
                                FROM    DBAMV.MVTO_ESTOQUE MVTO_ESTOQUE,
                                        DBAMV.MVTO_ESTOQUE MVTO_ESTOQUEDEV,
                                        DBAMV.ESTOQUE ESTOQUE,
                                        DBAMV.PRESTADOR PRESTADOR,
                                        DBAMV.UNID_INT UNID_INT,
                                        DBAMV.SETOR SETOR,
                                        DBAMV.ATENDIME ATENDIME,
                                        DBAMV.PACIENTE PACIENTE,
                                        DBAMV.LEITO LEITO,
                                        DBAMV.CONVENIO CONVENIO,
                                        DBAMV.MOT_DEV MOT_DEV--,
                                WHERE   ESTOQUE.CD_MULTI_EMPRESA                IN (1)
                                AND     MVTO_ESTOQUE.TP_MVTO_ESTOQUE            = 'C'
                                AND     MVTO_ESTOQUEDEV.CD_ESTOQUE_DESTINO      = 117
                                AND     ATENDIME.CD_MULTI_EMPRESA               IN (1)
                                AND     MVTO_ESTOQUEDEV.TP_STATUS_CONFIRMACAO   NOT IN ('C') -- 'A'
                                AND     NOT MVTO_ESTOQUE.CD_AVISO_CIRURGIA      IS NULL
                                --AND     MVTO_ESTOQUE.CD_MVTO_ESTOQUE            = 3873316
                                AND     MVTO_ESTOQUE.CD_ESTOQUE                 = ESTOQUE.CD_ESTOQUE(+)
                                AND     MVTO_ESTOQUE.CD_PRESTADOR               = PRESTADOR.CD_PRESTADOR(+)
                                AND     MVTO_ESTOQUE.CD_UNID_INT                = UNID_INT.CD_UNID_INT(+)
                                AND     MVTO_ESTOQUE.CD_SETOR                   = SETOR.CD_SETOR(+)
                                AND     MVTO_ESTOQUE.CD_ATENDIMENTO             = ATENDIME.CD_ATENDIMENTO(+)
                                AND     ATENDIME.CD_PACIENTE                    = PACIENTE.CD_PACIENTE(+)
                                AND     ATENDIME.CD_LEITO                       = LEITO.CD_LEITO(+)
                                AND     ATENDIME.CD_CONVENIO                    = CONVENIO.CD_CONVENIO(+)
                                AND     MVTO_ESTOQUE.CD_MOT_DEV                 = MOT_DEV.CD_MOT_DEV(+)
                                AND     MVTO_ESTOQUE.CD_MVTO_ESTOQUE            = MVTO_ESTOQUEDEV.CD_MVTO_FILHA(+)
                                ORDER BY MVTO_ESTOQUE.HR_MVTO_ESTOQUE ASC");
    }
}

?>