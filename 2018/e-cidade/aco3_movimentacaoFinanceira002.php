<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");
require_once("dbforms/db_funcoes.php");
require_once("std/DBDate.php");
require_once("model/configuracao/DBDepartamento.model.php");

$oGet          = db_utils::postMemory($_GET);
$iAnousu       = db_getsession("DB_anousu");
$iInstituicao  = db_getsession("DB_instit");
/**
 * Busca descrio do Municpio da Instituio
 */
$rsInstituicao          = db_query("select munic from db_config where codigo = {$iInstituicao} ");
$oInstituicao           = db_utils::fieldsmemory($rsInstituicao,0);
$sDescricaoInstituicao  = "MUNICPIO DE " . $oInstituicao->munic;

/**
 * Inicia Impresso do PDF
 */
$iHeader = 2;
$sHeader = "head";

${$sHeader.$iHeader} = $sDescricaoInstituicao;
$iHeader++;
${$sHeader.$iHeader} = "CONTRATOS - MOVIMENTAES FINANCEIRAS";
$iHeader++;

function imprimeCabecalho(&$oPdf, $iAlturaLinha) {

  $oPdf->setfont('arial', 'b', 6);
  $oPdf->cell(108, $iAlturaLinha, "Movimentação Contrato", "TBR",  0, "C", 1);
  $oPdf->cell(108, $iAlturaLinha, "Movimentação Empenho",  "LTBR", 0, "C", 1);
  $oPdf->cell( 64, $iAlturaLinha, "Saldo a Pagar",         "LTB", 1, "C", 1);
}

function imprimeCabecalhoCentral(&$oPdf, $iAlturaLinha) {

  $oPdf->setfont('arial', 'b', 6);

  //Dados do Contrato
  $oPdf->cell(11, $iAlturaLinha, "Contrato",   "TB",  0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, "Valor ",     "LTB", 0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, "Executado",  "LTB", 0, "C", 1);
  $oPdf->cell(25, $iAlturaLinha, "Vigência",   "LTB", 0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, "Empenhado",  "LTB", 0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, "A Empenhar", "LTBR", 0, "C", 1);

  //Dados do Empenho
  $oPdf->cell(18, $iAlturaLinha, "Empenho",   "LTB",  0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, "Emissão",   "LTB", 0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, "Empenhado", "LTB", 0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, "Anulado",   "LTB", 0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, "Liquidado", "LTB", 0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, "Pago",      "LTBR", 0, "C", 1);

  //Dados finais
  $oPdf->cell(22, $iAlturaLinha, "Liquidado",     "LTBR",  0, "C", 1);
  $oPdf->cell(21, $iAlturaLinha, "Não liquidado", "LTB", 0, "C", 1);
  $oPdf->cell(21, $iAlturaLinha, "Geral",         "LTB", 1, "C", 1);
  $oPdf->setfont('arial', '', 6);
}

function imprimirLinha (&$oPdf, $iAlturaLinha, $oStdLinha) {

  //Dados do Contrato
  $sVigenciaContrato  = db_formatar($oStdLinha->dt_contrato_inicio, "d");
  $sVigenciaContrato .= " a ";
  $sVigenciaContrato .= db_formatar($oStdLinha->dt_contrato_fim, "d");

  $oPdf->cell(11, $iAlturaLinha, $oStdLinha->i_contrato,                             "TB",  0, "C", 0);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdLinha->n_contrato_valor,"f"),      "LTB", 0, "C", 0);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdLinha->n_contrato_executado,"f"),  "LTB", 0, "C", 0);
  $oPdf->cell(25, $iAlturaLinha, $sVigenciaContrato,                                 "LTB", 0, "C", 0);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdLinha->n_contrato_empenhado, "f"), "LTB", 0, "C", 0);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdLinha->n_contrato_empenhar, "f"),  "LTBR", 0, "C", 0);

  //Dados do Empenho
  $oPdf->cell(18, $iAlturaLinha, "{$oStdLinha->s_empenho}",                          "LTB",  0, "C", 0);
  $oPdf->cell(18, $iAlturaLinha, $oStdLinha->dt_empenho_emissao , "LTB", 0, "C", 0);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdLinha->n_empenho_valor, "f"),     "LTB", 0, "C", 0);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdLinha->n_anulado_empenho, "f"),   "LTB", 0, "C", 0);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdLinha->n_liquidado_empenho, "f"), "LTB", 0, "C", 0);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdLinha->n_pago_empenho, "f"),      "LTBR", 0, "C", 0);

  //Dados finais
  $oPdf->cell(22, $iAlturaLinha, db_formatar($oStdLinha->n_liquidado_saldo, "f"),     "LTB",  0, "C", 0);
  $oPdf->cell(21, $iAlturaLinha, db_formatar($oStdLinha->n_nao_liquidado_saldo, "f"), "LTB", 0, "C", 0);
  $oPdf->cell(21, $iAlturaLinha, db_formatar($oStdLinha->n_geral_saldo, "f"),         "LTB", 1, "C", 0);

  return verificaQuebraPagina($oPdf, $iAlturaLinha);
}

function verificaQuebraPagina ( &$oPdf, $iAlturaLinha = '7', $iTamanhoFonte = '6' ) {

  if ( $oPdf->GetY() > $oPdf->h - 40) {

    $oPdf->SetFont('arial', '', $iTamanhoFonte);

    $oPdf->Cell(282, ($iAlturaLinha + 5), 'Continua na página '.($oPdf->PageNo() + 1)."/{nb}",    '', 1, "R", 0);
    $oPdf->AddPage("L");

    $oPdf->Cell(282, ($iAlturaLinha), 'Continuação '.($oPdf->PageNo())."/{nb}",             '', 1, "R", 0);

    return true;
  }
  return false;
}

function imprimeCabecalhoCGM ( &$oPdf, $iAlturaLinha, $oStdCGM) {

  verificaQuebraPagina($oPdf, $iAlturaLinha);
  $oPdf->setfont('arial', 'b', 6);
  $oPdf->cell( 18, $iAlturaLinha, "CGM",                                                                                      "TB",  0, "C", 1);
  $oPdf->cell(262, $iAlturaLinha, $oStdCGM->ac16_contratado . " - " . $oStdCGM->cgm . " - CNPJ/CPF: " . $oStdCGM->s_cnpj_cpf, "LTB", 1, "L", 1);
  $oPdf->setfont('arial', '', 6);
}

function imprimirAgrupamentoCGM ( &$oPdf, $iAlturaLinha, $oStdCGM ) {

  imprimeCabecalhoCGM($oPdf, $iAlturaLinha, $oStdCGM);
  imprimeCabecalho($oPdf, $iAlturaLinha);
  imprimeCabecalhoCentral($oPdf, $iAlturaLinha);

  foreach ($oStdCGM->aContratos as $oContrato){

    foreach ($oContrato->aEmpenhos as $oStdLinha) {

      $oStdLinha->n_geral_saldo = $oStdLinha->n_liquidado_saldo + $oStdLinha->n_nao_liquidado_saldo;
      if ( imprimirLinha ( $oPdf, $iAlturaLinha, $oStdLinha ) ) {

        imprimeCabecalhoCGM($oPdf, $iAlturaLinha, $oStdCGM);
        imprimeCabecalho($oPdf, $iAlturaLinha);
        imprimeCabecalhoCentral($oPdf, $iAlturaLinha);
      }
    }
  }

  $oPdf->setfont('arial', 'b', 6);
  $oPdf->cell(11, $iAlturaLinha, "TOTAL",                                          "TBR",   0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdCGM->n_contrato_valor, "f"),     "LTBR", 0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdCGM->n_contrato_executado, "f"), "LTBR", 0, "C", 1);
  $oPdf->cell(25, $iAlturaLinha, "",                                               "LTBR",   0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdCGM->n_contrato_empenhado, "f"), "LTBR", 0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdCGM->n_contrato_empenhar, "f"),  "LTBR", 0, "C", 1);

  //Dados do Empenho
  $oPdf->cell(18, $iAlturaLinha, "",                                               "LTBR",  0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, "",                                               "LTBR",  0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdCGM->n_empenho_valor, "f") ,    "LTBR", 0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdCGM->n_anulado_empenho, "f") ,  "LTBR", 0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdCGM->n_liquidado_empenho, "f"), "LTBR", 0, "C", 1);
  $oPdf->cell(18, $iAlturaLinha, db_formatar($oStdCGM->n_pago_empenho, "f") ,     "LTBR", 0, "C", 1);

  //Dados finais
  $oPdf->cell(22, $iAlturaLinha, db_formatar($oStdCGM->n_liquidado_saldo, "f"),     "LTBR",  0, "C", 1);
  $oPdf->cell(21, $iAlturaLinha, db_formatar($oStdCGM->n_nao_liquidado_saldo, "f"), "LTBR", 0, "C", 1);
  $oPdf->cell(21, $iAlturaLinha, db_formatar($oStdCGM->n_geral_saldo, "f"),         "LTB", 1, "C", 1);

  $oPdf->setfont('arial', '', 6);
}

/**
 * Varivel utilizada para todas as sql executadas
 * Caso haver uma particularidade com alguma outra sql, concatenar com esta, pois nesse relatrio
 * s iro constar os contratos que estiverem homologados
 */
$sWhereGeral = " ac16_acordosituacao = 4 ";

/**
 * Caso venha algum filtro de vicência do contrato
 */
if ( !empty($oGet->dtVigenciaInicial) || !empty($oGet->dtVigenciaFinal)) {

  if (!empty($oGet->dtVigenciaInicial)) {

    $oDataVigenciaInicial = new DBDate($oGet->dtVigenciaInicial);
    $dtVigenciaInicial    = $oDataVigenciaInicial->getDate();
  }

  if (!empty($oGet->dtVigenciaFinal)) {

    $oDataVigenciaFinal = new DBDate($oGet->dtVigenciaFinal);
    $dtVigenciaFinal    = $oDataVigenciaFinal->getDate();
  }


  /**
   * Se vierem as duas vigências, pegamos os contratos entre as datas informadas
   */
  if ( !empty($oGet->dtVigenciaInicial) && !empty($oGet->dtVigenciaFinal)) {

    $sWhereGeral .= " and ac16_datainicio >= '{$dtVigenciaInicial}'";
    $sWhereGeral .= " and ac16_datafim    <= '{$dtVigenciaFinal}'";
  } else if(isset($oGet->dtVigenciaInicial) && !empty($oGet->dtVigenciaInicial) && (!isset($oGet->dtVigenciaFinal) || empty($oGet->dtVigenciaFinal))) {

    /**
     * Caso venha só a vigência inicial, buscamos somente os contratos daquela data
     */
    $sWhereGeral .= " and ac16_datainicio = '{$dtVigenciaInicial}'";
  } else {
    $sWhereGeral .= " and ac16_datafim    <= '{$dtVigenciaFinal}'";
  }
}

/**
 * Caso venha filtro do departamento responsável
 */
if (!empty($oGet->iCodigoDepartamento)) {

  $sWhereGeral         .= " and ac16_deptoresponsavel = {$oGet->iCodigoDepartamento}";
  $oDepartamento        = new DBDepartamento($oGet->iCodigoDepartamento);
  ${$sHeader.$iHeader}  = "DEPTO. RESPONSÁVEL: {$oDepartamento->getNomeDepartamento()}";
  $iHeader++;
}

if (!empty($oGet->iOrigem)) {
  /**
   * Tabela acordoorigem
   * 1 | Processo de Compras
   * 2 | Licitação
   * 3 | Manual
   * 4 | Interno
   * 5 | Custo Fixo
   * 6 | Empenho
   */
  $sWhereGeral .= " and ac16_origem in ({$oGet->iOrigem})";
  $aOrigens     = array();
  $aOrigens[1]  = "PROCESSO DE COMPRAS";
  $aOrigens[2]  = "LICITAÇÃO";
  $aOrigens[3]  = "MANUAL";
  $aOrigens[4]  = "INTERNO";
  $aOrigens[5]  = "CUSTO FIXO";
  $aOrigens[6]  = "EMPENHO";
  $aOrigens[0]  = "TODAS";
  ${$sHeader.$iHeader} = "ORIGEM: {$aOrigens[$oGet->iOrigem]}";
} else if (empty($oGet->iOrigem)) {
  ${$sHeader.$iHeader} = "ORIGEM: TODAS";
}
$iHeader++;

/**
 * Esses campos vem da tela atravéz de input's do tipo hidden
 */

$sWhereEmpenho = '';
/**
 * Empenho Inicial setado
 * - Empenho deve ser de ano maior do que o empenho escolhido
 * - ou o empenho deve ser do mesmo ano mas ter o código maior ou igual ao escolhido
 */
if (!empty($oGet->iCodigoEmpenhoInicial)) {

  ${$sHeader.$iHeader} = "EMPENHO INICIAL: {$oGet->iCodigoEmpenhoInicial}";
  $iHeader++;
  $aFiltro             = explode("/", $oGet->iCodigoEmpenhoInicial);
  $iCodigoEmpenho      = $aFiltro[0];
  $iAnoEmpenho         = $aFiltro[1];
  $sWhereEmpenho       = " and (e60_codemp >= '{$iCodigoEmpenho}' and e60_anousu = {$iAnoEmpenho})";
  $sWhereEmpenho      .= " or (e60_anousu  > {$iAnoEmpenho} )";
}

/**
 * Empenho Final setado
 * - Empenho deve ser de ano menor do que o empenho escolhido
 * - ou o empenho deve ser do mesmo ano mas ter o código menor ou igual ao escolhido
 */
if (!empty($oGet->iCodigoEmpenhoFinal)) {

  ${$sHeader.$iHeader}  = "EMPENHO FINAL: {$oGet->iCodigoEmpenhoFinal}";
  $iHeader++;
  $aFiltro              = implode("/", $oGet->iCodigoEmpenhoInicial);
  $iCodigoEmpenho       = $aFiltro[0];
  $iAnoEmpenho          = $aFiltro[1];
  $sWhereEmpenho        = " and (e60_codemp <= '{$iCodigoEmpenho}' and e60_anousu = {$iAnoEmpenho})";
  $sWhereEmpenho       .= " or (e60_anousu < {$iAnoEmpenho} )";
}

if (!empty($oGet->iCodigoEmpenhoInicial) && !empty($oGet->iCodigoEmpenhoFinal)) {


  $aFiltroInicial        = implode("/", $oGet->iCodigoEmpenhoInicial);
  $iCodigoEmpenhoInicial = $aFiltroInicial[0];
  $iAnoEmpenhoInicial    = $aFiltroInicial[1];


  $aFiltroFinal        = implode("/", $oGet->iCodigoEmpenhoFinal);
  $iCodigoEmpenhoFinal = $aFiltroFinal[0];
  $iAnoEmpenhoFinal    = $aFiltroFinal[1];

  $sWhereEmpenho  = " and ((e60_codemp >= '{$iCodigoEmpenhoInicial}' and e60_anousu  = {$iAnoEmpenhoInicial})";
  $sWhereEmpenho .= "   or (e60_codemp <= '{$iCodigoEmpenhoFinal}'   and e60_anousu  = {$iAnoEmpenhoFinal})";
  $sWhereEmpenho .= "   or ( e60_anousu > {$iAnoEmpenhoInicial}      and e60_anousu < {$iAnoEmpenhoFinal})";
}

/**
 * Busca pelos contratos escolhidos
 */
if (!empty($oGet->aContratos)) {

  $sWhereGeral .= " and ac16_sequencial in({$oGet->aContratos}) ";
}

/**
 * Filtra pela categoria escolhida
 */
if (!empty($oGet->iCategoria)) {

  $sWhereGeral         .= " and ac16_acordocategoria = {$oGet->iCategoria}";
  ${$sHeader.$iHeader}  = "CATEGORIA: ". urldecode($oGet->sCategoria);
  $iHeader++;
}

$sCampoExecutado = "( select sum(ac29_valor)
                      from       acordo w
                      inner join acordoposicao              on w.ac16_sequencial                         = acordoposicao.ac26_acordo
                      inner join acordoitem                 on acordoposicao.ac26_sequencial              = acordoitem.ac20_acordoposicao
                      inner join acordoitemexecutado        on acordoitem.ac20_sequencial                 = acordoitemexecutado.ac29_acordoitem
                     where ac29_tipo = 2 and  w.ac16_sequencial = acordo.ac16_sequencial)                      as n_contrato_executado";

/**
 * Movimentao de contrato de origem manual
 */
$oDAOAcordo    = db_utils::getDao("acordo");
$sCamposManual = "
  acordoitemexecutado.ac29_sequencial 				       as periodo_item_executado,
  e60_numemp,
  e60_anousu,
  z01_nome,
  z01_cgccpf                                          as s_cnpj_cpf,
  ac16_sequencial                                     as i_contrato,
  (select sum (ac20_valortotal)
  from acordo x
  inner join acordoposicao y on x.ac16_sequencial = y.ac26_acordo
  inner join acordoitem    z on y.ac26_sequencial = z.ac20_acordoposicao
  where x.ac16_sequencial = acordo.ac16_sequencial) as n_contrato_valor,
  0                                                   as n_contrato_empenhado,
  0                                                   as n_contrato_empenhar,
  e60_codemp                                          as s_empenho,
  e60_emiss                                           as dt_empenho_emissao,
  e60_vlremp                                          as n_empenho_valor,
  e60_vlranu                                          as n_anulado_empenho,
  e60_vlrliq                                          as n_liquidado_empenho,
  e60_vlrpag                                          as n_pago_empenho,
  ac16_contratado                                     as cgm,
  ac38_datainicial                                    as execucao,
  true                                                as l_origem_execucao_manual ,
  ac16_datainicio                                     as dt_contrato_inicio,
  ac16_datafim                                        as dt_contrato_fim, {$sCampoExecutado}";

$sSqlOrigemManual  = $oDAOAcordo->sql_movimentacao_acordo_origem_manual(null, $sCamposManual, "", $sWhereGeral.$sWhereEmpenho);

/**
 * Movimentaes de contratos empenhados
 */
$sCamposEmpenhados = "
    null                                             as periodo_item_executado,
    e60_numemp,
  e60_anousu,
  z01_nome,
  z01_cgccpf as s_cnpj_cpf,
  ac45_acordo                                         as i_contrato,
  (select sum (ac20_valortotal)
  from acordo as x
  inner join acordoposicao as y on x.ac16_sequencial = y.ac26_acordo
  inner join acordoitem    as z on y.ac26_sequencial = z.ac20_acordoposicao
  where x.ac16_sequencial = acordo.ac16_sequencial) as n_contrato_valor,
  0                                                   as n_contrato_empenhado,
  0                                                   as n_contrato_empenhar,
  e60_codemp                                          as s_empenho,
  e60_emiss                                           as dt_empenho_emissao,
  e60_vlremp                                          as n_empenho_valor,
  e60_vlranu                                          as n_anulado_empenho,
  e60_vlrliq                                          as n_liquidado_empenho,
  e60_vlrpag                                          as n_pago_empenho,
  ac16_contratado                                     as cgm,
  null                                                as execucao,
  false                                               as l_origem_execucao_manual,
  ac16_datainicio                                     as dt_contrato_inicio,
  ac16_datafim                                        as dt_contrato_fim , {$sCampoExecutado}";

$sSqlAcordosEmpenhados = $oDAOAcordo->sql_movimentacao_acordo_empenhado(null, $sCamposEmpenhados, "", $sWhereGeral);

/**
 * Movimentaes de contratos origem empenho
 */
$sCamposEmpenho = "
  null                                              as periodo_item_executado,
  e60_numemp,
  e60_anousu,
  z01_nome,
  z01_cgccpf as s_cnpj_cpf,
  ac16_sequencial                                     as i_contrato,

(select sum (ac20_valortotal)
  from acordo as x
  inner join acordoposicao as y on x.ac16_sequencial = y.ac26_acordo
  inner join acordoitem    as z on y.ac26_sequencial = z.ac20_acordoposicao
  where x.ac16_sequencial = acordo.ac16_sequencial)

as n_contrato_valor,
  0                                                   as n_contrato_empenhado,
  0                                                   as n_contrato_empenhar,
  e60_codemp                                          as s_empenho,
  e60_emiss                                           as dt_empenho_emissao,
  e60_vlremp                                          as n_empenho_valor,
  e60_vlranu                                          as n_anulado_empenho,
  e60_vlrliq                                          as n_liquidado_empenho,
  e60_vlrpag                                          as n_pago_empenhos,
  ac16_contratado                                     as cgm,
  null                                                as execucao,
  false                                               as l_origem_execucao_manual,
  ac16_datainicio                                     as dt_contrato_inicio,
  ac16_datafim                                        as dt_contrato_fim, {$sCampoExecutado}";

/* (select sum (acordoitem.ac20_valortotal)
     from acordo x
          inner join empempenhocontrato as y on x.ac16_sequencial                = y.e100_acordo
          inner join empempenho         as z on y.e100_numemp                    = z.e60_numemp
          inner join empempitem              on z.e60_numemp                     = empempitem.e62_numemp
          inner join acordoempempitem        on empempitem.e62_sequencial        = acordoempempitem.ac44_empempitem
          inner join acordoitem              on acordoempempitem.ac44_acordoitem = acordoitem.ac20_sequencial
    where x.ac16_sequencial = acordo.ac16_sequencial) 
*/

$sOrderEmpenho     = "cgm asc, i_contrato asc, dt_empenho_emissao asc";
$sSqlOrigemEmpenho = $oDAOAcordo->sql_movimentacao_acordo_origem_empenho(null, $sCamposEmpenho, $sOrderEmpenho, $sWhereGeral);
$sSql              = $sSqlOrigemManual. " union " . $sSqlAcordosEmpenhados . " union " . $sSqlOrigemEmpenho;
$rsContratos       = $oDAOAcordo->sql_record($sSql);

if ($oDAOAcordo->numrows <= 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}

$aCGM = array();

/**
 *  Seta as propriedades do pdf
 */
$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$iAlturaLinha = 7;
$oPdf->AddPage();
$oPdf->SetFont('arial', '', 6);
$aContratos = array();

/**
 * Percorre cada movimentao de contrato, agrupando por CGM
 */
for ($iLinha = 0; $iLinha < $oDAOAcordo->numrows; $iLinha++) {

  unset($oLinha);
  $oLinha  = db_utils::fieldsMemory($rsContratos, $iLinha);

  if (empty($aCGM[$oLinha->cgm])) {

    $oCgm                  = new stdClass();
    $oCgm->cgm             = $oLinha->z01_nome;
    $oCgm->ac16_contratado = $oLinha->cgm;
    $oCgm->s_cnpj_cpf      = $oLinha->s_cnpj_cpf;
    $oCgm->aContratos      = array();
    $aCGM[$oLinha->cgm]    = $oCgm;
  }

  //Caso contrato no esteja setado ainda no CGM, insere no array de contratos
  if (empty($oCgm->aContratos[$oLinha->i_contrato])) {

    $oContrato                             = new stdClass();
    $oContrato->aEmpenhos                  = array();
    $oContrato->n_contrato_valor           = $oLinha->n_contrato_valor;
    $oContrato->n_contrato_empenhar        = $oLinha->n_contrato_valor;
    $oContrato->n_contrato_empenhado       = 0;
    $oCgm->n_contrato_valor               += $oLinha->n_contrato_valor;
    $oCgm->aContratos[$oLinha->i_contrato] = $oContrato;
    $oCgm->n_contrato_executado           += $oLinha->n_contrato_executado;
    $aContratos[]                          = $oLinha->i_contrato;
  }

  //Atualiza status do Contrato a cada movimentação de empenho percorrido
  $oContrato = $oCgm->aContratos[$oLinha->i_contrato];

  if (empty($oContrato->aEmpenhos[$oLinha->e60_numemp])) {

    $oLinha->dt_empenho_emissao      = db_formatar($oLinha->dt_empenho_emissao, "d");
    $oLinha->s_empenho               = "{$oLinha->s_empenho}/{$oLinha->e60_anousu}";
    $oContrato->n_contrato_empenhar  = $oContrato->n_contrato_empenhar  - $oLinha->n_empenho_valor;
    $oContrato->n_contrato_empenhado = $oContrato->n_contrato_empenhado + $oLinha->n_empenho_valor;

    $oLinha->n_contrato_empenhar     = $oContrato->n_contrato_empenhar;
    $oLinha->n_contrato_empenhado    = $oContrato->n_contrato_empenhado;

    //saldo liquidado  o total empenhado subtraido do j pago
    $oLinha->n_liquidado_saldo       = $oLinha->n_liquidado_empenho - $oLinha->n_pago_empenho;

    //saldo liquidado  o total empenhado subtraido do j pago
    $oLinha->n_nao_liquidado_saldo   = $oLinha->n_empenho_valor     - $oLinha->n_liquidado_empenho;

    //Totalizador por CGM
    $oCgm->n_contrato_empenhado     += $oLinha->n_contrato_empenhado;
    $oCgm->n_contrato_empenhar      += $oLinha->n_contrato_empenhar;
    $oCgm->n_empenho_valor          += $oLinha->n_empenho_valor;
    $oCgm->n_liquidado_empenho      += $oLinha->n_liquidado_empenho;
    $oCgm->n_pago_empenho           += $oLinha->n_pago_empenho;
    $oCgm->n_anulado_empenho        += $oLinha->n_anulado_empenho;
    $oCgm->n_liquidado_saldo        += $oLinha->n_liquidado_saldo;
    $oCgm->n_nao_liquidado_saldo    += $oLinha->n_nao_liquidado_saldo;
    $oCgm->n_geral_saldo            += $oLinha->n_liquidado_saldo + $oLinha->n_nao_liquidado_saldo;

    //adiciona a linha correspondente ao empenho, no array de empenhos do contrato correspondente
    $oContrato->aEmpenhos[$oLinha->e60_numemp]= $oLinha;

  }
}

//Contratos sem vinculos com empenhos
$oDAOAcordo             = db_utils::getDao("acordo");
$sCampos                = " acordo.*,
                            cgm.* ,
                            (select sum (ac20_valortotal)
                            from acordo as x
                            inner join acordoposicao as y on x.ac16_sequencial = y.ac26_acordo
                            inner join acordoitem    as z on y.ac26_sequencial = z.ac20_acordoposicao
                            where x.ac16_sequencial = acordo.ac16_sequencial) as n_contrato_valor, {$sCampoExecutado}";

$sOrder                 = "ac16_sequencial asc";
$sNotIn                 = implode(",", $aContratos);
$sWhere                 = " and ac16_sequencial not in ({$sNotIn})";
$sSqlAcordosSemEmpenhos = $oDAOAcordo->sql_query(null, $sCampos, $sOrder, $sWhereGeral.$sWhere);
$rsContratos            = $oDAOAcordo->sql_record($sSqlAcordosSemEmpenhos);


for ($iLinha = 0; $iLinha < $oDAOAcordo->numrows; $iLinha++) {

  unset($oLinha);
  $oLinha = db_utils::fieldsMemory($rsContratos, $iLinha);

  if (empty($aCGM[$oLinha->z01_numcgm])) {

    $oCgm                        = new stdClass();
    $oCgm->cgm                   = $oLinha->z01_nome;
    $oCgm->ac16_contratado       = $oLinha->z01_numcgm;
    $oCgm->n_contrato_executado  = $oLinha->n_contrato_executado;
    $oCgm->n_contrato_empenhado  = 0;
    $oCgm->n_contrato_empenhar   = 0;
    $oCgm->n_empenho_valor       = 0;
    $oCgm->n_anulado_empenho     = 0;
    $oCgm->n_liquidado_empenho   = 0;
    $oCgm->n_nao_liquidado_saldo = 0;
    $oCgm->n_pago_empenho        = 0;
    $oCgm->n_liquidado_saldo     = 0;
    $oCgm->n_geral_saldo         = 0;
    $oCgm->s_cnpj_cpf            = $oLinha->z01_cgccpf;
    $oCgm->aContratos            = array();
    $aCGM[$oLinha->z01_numcgm]   = $oCgm;
  }

  //Caso contrato no esteja setado ainda no CGM, insere no array de contratos
  if (empty($oCgm->aContratos[$oLinha->ac16_sequencial])) {

    $oContrato                             = new stdClass();
    $oContrato->aEmpenhos                  = array();
    $oContrato->n_contrato_valor           = 0;
    $oContrato->n_contrato_empenhar        = 0;
    $oContrato->n_contrato_empenhado       = 0;
    $oCgm->n_contrato_valor               += 0;
    $oCgm->aContratos[$oLinha->ac16_sequencial] = $oContrato;
    $aContratos[]                          = $oLinha->ac16_sequencial;
  }

  $oLinha->i_contrato            = $oLinha->ac16_sequencial;
  $oLinha->n_contrato_valor      = $oLinha->n_contrato_valor;
  $oLinha->n_contrato_empenhar   = $oLinha->n_contrato_valor;
  $oLinha->n_contrato_empenhado  = 0;
  $oLinha->n_contrato_empenhar   = 0;
  $oLinha->s_empenho             = '--';
  $oLinha->dt_empenho_emissao    = '--';
  $oLinha->n_empenho_valor       = 0;
  $oLinha->n_liquidado_empenho   = 0;
  $oLinha->n_pago_empenho        = 0;
  $oLinha->n_anulado_empenho     = 0;
  $oLinha->n_liquidado_saldo     = 0;
  $oLinha->n_nao_liquidado_saldo = 0;
  $oLinha->n_geral_saldo         = 0;
  $oLinha->dt_contrato_inicio    = $oLinha->ac16_datainicio;
  $oLinha->dt_contrato_fim       = $oLinha->ac16_datafim;
  $oLinha->n_contrato_executado  = $oLinha->n_contrato_executado;

  $oContrato->aEmpenhos[] = $oLinha;
}


foreach($aCGM as $oCGM) {

  imprimirAgrupamentoCGM($oPdf, $iAlturaLinha, $oCGM);
  $oPdf->ln(2);
}

$oPdf->Output();
?>
