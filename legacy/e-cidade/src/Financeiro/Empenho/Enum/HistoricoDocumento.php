<?php

namespace ECidade\Financeiro\Empenho\Enum;

/**
 * Enum que define os documentos para histórico contábil.
 *
 * @package ECidade\Financeiro\Empenho\Enum
 * @author Stephano Ramos <stephano.ramos@dbseller.com.br>
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
abstract class HistoricoDocumento
{
    /** @var integer */
    const LIQUIDACAO = 3;

    /** @var integer */
    const ANULACAO_LIQUIDACAO = 4;

    /** @var integer */
    const LIQUIDACAO_DESPESA_CAPITAL = 23;

    /** @var integer */
    const ANULACAO_LIQUIDACAO_CAPITAL = 24;

    /** @var integer */
    const LIQUIDACAO_RP = 33;

    /** @var integer */
    const ANULACAO_LIQUIDACAO_RP = 34;

    /** @var integer */
    const LIQUIDACAO_RP_ESTOQUES_PATRIMONIO = 39;

    /** @var integer */
    const ESTORNO_LIQUIDACAO_RP_ESTOQUES_PATRIMONIO = 40;

    /** @var integer */
    const LIQUIDACAO_EMPENHO_PASSIVO_SEM_SUP_ORCAMENT = 84;

    /** @var integer */
    const ESTORNO_LIQ_EMP_PASSIVO_SEM_SUP_ORCAMENT = 85;

    /** @var integer */
    const CONTROLE_DESPESA_LIQUIDACAO = 200;

    /** @var integer */
    const LIQUIDACAO_DESPESA_COM_SERVICOS = 202;

    /** @var integer */
    const ESTORNO_LIQUIDACAO_DESPESA_COM_SERVICOS = 203;

    /** @var integer */
    const LIQUIDACAO_DESPESA_MATERIAL_CONSUMO = 204;

    /** @var integer */
    const ESTORNO_LIQ_DESPESA_MATERIAL_CONSUMO = 205;

    /** @var integer */
    const LIQUIDACAO_AQUISICAO_MATERIAL_PERMANENTE = 206;

    /** @var integer */
    const ESTORNO_LIQ_AQ_MATERIAL_PERMANENTE = 207;

    /** @var integer */
    const CONTROLE_DESPESA_LIQUIDACAO_MP = 208;

    /** @var integer */
    const CONTROLE_DESPESA_LIQUIDACAO_MAT_ALMOX = 210;

    /** @var integer */
    const REGISTRO_ENTRADA_MATERIAL_VIA_RP = 212;

    /** @var integer */
    const ESTORNO_REGISTRO_ENTRADA_MATERIAL_VIA_RP = 213;

    /** @var integer */
    const CONTROLE_DESPESA_LIQUIDACAO_MP_RP = 214;

    /** @var integer */
    const LIQUIDACAO_PROVISAO_FERIAS = 306;

    /** @var integer */
    const ESTORNO_LIQUIDACAO_PROVISAO_FERIAS = 307;

    /** @var integer */
    const LIQUIDACAO_PROVISAO_13_SALARIO = 310;

    /** @var integer */
    const ESTORNO_LIQUIDACAO_PROVISAO_13_SALARIO = 311;

    /** @var integer */
    const EMPENHO_SUPRIMENTO_FUNDOS = 410;

    /** @var integer */
    const LIQUIDACAO_SUPRIMENTO_FUNDOS = 412;

    /** @var integer */
    const ESTORNO_LIQUIDACAO_SUPRIMENTO_FUNDOS = 413;

    /** @var integer */
    const LIQUIDACAO_PRECATORIOS = 502;

    /** @var integer */
    const ESTORNO_LIQUIDACAO_PRECATORIOS = 503;

    /** @var integer */
    const LIQUIDACAO_AMORT_DIVIDA = 506;

    /** @var integer */
    const ESTORNO_LIQUIDACAO_AMORT_DIVIDA = 507;

    /** @var integer */
    const CONTROLE_EXECUCAO_CONTRATO = 901;
}
