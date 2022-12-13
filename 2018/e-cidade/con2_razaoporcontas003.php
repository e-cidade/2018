<?php
/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/DBDate.php"));

$oGet               = db_utils::postMemory($_GET);
$iAnoSessao         = db_getsession("DB_anousu");
$iInstituicaoSessao = db_getsession("DB_instit");

$oDataInicial = new DBDate($oGet->data1);
$oDataFinal   = new DBDate($oGet->data2);

//$oGet->contasemmov == "s" ? $oGet->contasemmov = true : $oGet->contasemmov = false;
$oGet->quebrapaginaporconta == "s" ? $oGet->quebrapaginaporconta = true : $oGet->quebrapaginaporconta = false;

if ($oGet->saldopordia == "s") {

  $oGet->saldopordia = true;
  $lImprimirData     = true;
} else {

  $oGet->saldopordia = false;
  $lImprimirData     = false;
}

$sSqlBuscaLancamentos  = "  select x.c61_reduz  as reduzido,";
$sSqlBuscaLancamentos .= "             coalesce(sum(case when tipo = 'C' then c70_valor end), 0) as credito,";
$sSqlBuscaLancamentos .= "             coalesce(sum(case when tipo = 'D' then c70_valor end), 0) as debito,";
if ($oGet->saldopordia) {
  $sSqlBuscaLancamentos .= "           x.c70_data as data,";
}
$sSqlBuscaLancamentos .= "             x.c60_descr  as descricao_conta,";
$sSqlBuscaLancamentos .= "             x.c60_estrut as estrutural,";
$sSqlBuscaLancamentos .= "             x.c53_coddoc as codigo_documento,";
$sSqlBuscaLancamentos .= "             x.c53_descr  as descricao_documento";
$sSqlBuscaLancamentos .= "        from (";
$sSqlBuscaLancamentos .= "      select c61_reduz,";
$sSqlBuscaLancamentos .= "             c69_debito,";
$sSqlBuscaLancamentos .= "             c69_credito,";
$sSqlBuscaLancamentos .= "             c69_codlan, ";
$sSqlBuscaLancamentos .= "             c70_data,";
$sSqlBuscaLancamentos .= "             c70_valor,";
$sSqlBuscaLancamentos .= "             c60_descr,";
$sSqlBuscaLancamentos .= "             c60_estrut,";
$sSqlBuscaLancamentos .= "             c53_coddoc,";
$sSqlBuscaLancamentos .= "             c53_descr,";
$sSqlBuscaLancamentos .= "             case when conlancamval.c69_credito = conplanoreduz.c61_reduz";
$sSqlBuscaLancamentos .= "               then 'C'";
$sSqlBuscaLancamentos .= "               else 'D'";
$sSqlBuscaLancamentos .= "             end as tipo";
$sSqlBuscaLancamentos .= "        from conplano";
$sSqlBuscaLancamentos .= "             inner join conplanoreduz  on conplanoreduz.c61_codcon = conplano.c60_codcon";
$sSqlBuscaLancamentos .= "                                      and conplanoreduz.c61_anousu = conplano.c60_anousu";
$sSqlBuscaLancamentos .= "             inner join conlancamval   on conlancamval.c69_credito = conplanoreduz.c61_reduz";
$sSqlBuscaLancamentos .= "                                       or conlancamval.c69_debito  = conplanoreduz.c61_reduz";
$sSqlBuscaLancamentos .= "             inner join conlancam      on conlancam.c70_codlan     = conlancamval.c69_codlan";
$sSqlBuscaLancamentos .= "             inner join conlancamdoc   on conlancamdoc.c71_codlan  = conlancamval.c69_codlan";
$sSqlBuscaLancamentos .= "             inner join conhistdoc     on conhistdoc.c53_coddoc    = conlancamdoc.c71_coddoc";
$sSqlBuscaLancamentos .= "       where conplanoreduz.c61_anousu = {$iAnoSessao}";
$sSqlBuscaLancamentos .= "         and conplano.c60_anousu      = {$iAnoSessao}";
$sSqlBuscaLancamentos .= "         and conlancamval.c69_data between '{$oDataInicial->getDate()}' and '{$oDataFinal->getDate()}'";
$sSqlBuscaLancamentos .= "         and c61_instit = {$iInstituicaoSessao} ";

if (trim($oGet->sDocumentos) != "") {
  $sSqlBuscaLancamentos .= "       and conhistdoc.c53_coddoc in ({$oGet->sDocumentos})";
}
if (trim($oGet->lista) != "") {
  $sSqlBuscaLancamentos .= "       and conplanoreduz.c61_reduz in ({$oGet->lista})";
}
if (trim($oGet->estrut_inicial) != "") {
  $sSqlBuscaLancamentos .= "       and conplano.c60_estrut ilike '{$oGet->estrut_inicial}%'";
}
$sSqlBuscaLancamentos .= "       order by c70_data, c61_reduz) as x";
$sSqlBuscaLancamentos .= "       group by x.c60_descr,";
$sSqlBuscaLancamentos .= "                x.c60_estrut,";
$sSqlBuscaLancamentos .= "                x.c53_coddoc,";
$sSqlBuscaLancamentos .= "                x.c53_descr,";
if ($oGet->saldopordia) {
  $sSqlBuscaLancamentos .= "               x.c70_data,";
}
$sSqlBuscaLancamentos .= "                x.c61_reduz";
$sSqlBuscaLancamentos .= "       order by x.c61_reduz";


//echo $sSqlBuscaLancamentos; die();

$rsBuscaLancamentos = db_query($sSqlBuscaLancamentos);
$iTotalRegistros    = pg_num_rows($rsBuscaLancamentos);
$iCodigoReduzido    = null;
$aDadosImprimir     = array();

/**
 * Percorremos os dados para imprimir estes dados posteriormente
 */
for ($iLinhaImprimir = 0; $iLinhaImprimir < $iTotalRegistros; $iLinhaImprimir++) {

  $oStdDadoImprimir = db_utils::fieldsMemory($rsBuscaLancamentos, $iLinhaImprimir);

  $oDataLancamento = null;
  if (isset($oStdDadoImprimir->data)) {
    $oDataLancamento = new DBDate($oStdDadoImprimir->data);
  }

  if (empty($iCodigoReduzido) || $iCodigoReduzido != $oStdDadoImprimir->reduzido) {

    $oStdDadosConta = new stdClass();
    $oStdDadosConta->sDescricaoConta    = $oStdDadoImprimir->descricao_conta;
    $oStdDadosConta->iCodigoReduzido    = $oStdDadoImprimir->reduzido;
    $oStdDadosConta->sEstrutural        = $oStdDadoImprimir->estrutural;
    $oStdDadosConta->nValorTotalDebito  = 0;
    $oStdDadosConta->nValorTotalCredito = 0;
    $oStdDadosConta->aLancamentos       = array();

    $oStdMovimento = new stdClass();
    $oStdMovimento->oDataLancamento     = $oDataLancamento;
    $oStdMovimento->nValorDebito        = $oStdDadoImprimir->debito;
    $oStdMovimento->nValorCredito       = $oStdDadoImprimir->credito;
    $oStdMovimento->iCodigoDocumento    = $oStdDadoImprimir->codigo_documento;
    $oStdMovimento->sDescricaoDocumento = $oStdDadoImprimir->descricao_documento;
    array_push($oStdDadosConta->aLancamentos, $oStdMovimento);

    $oStdDadosConta->nValorTotalDebito  += $oStdDadoImprimir->debito;
    $oStdDadosConta->nValorTotalCredito += $oStdDadoImprimir->credito;
    $aDadosImprimir[$oStdDadoImprimir->reduzido] = $oStdDadosConta;

  } else {

    $oStdMovimento = new stdClass();
    $oStdMovimento->oDataLancamento     = $oDataLancamento;
    $oStdMovimento->nValorDebito        = $oStdDadoImprimir->debito;
    $oStdMovimento->nValorCredito       = $oStdDadoImprimir->credito;
    $oStdMovimento->iCodigoDocumento    = $oStdDadoImprimir->codigo_documento;
    $oStdMovimento->sDescricaoDocumento = $oStdDadoImprimir->descricao_documento;
    array_push($oStdDadosConta->aLancamentos, $oStdMovimento);

    $oStdDadosConta->nValorTotalDebito  += $oStdDadoImprimir->debito;
    $oStdDadosConta->nValorTotalCredito += $oStdDadoImprimir->credito;
  }

  $iCodigoReduzido = $oStdDadoImprimir->reduzido;
}

$head1 = "Razão por Contas Agrupado por Evento Contábil";
$head3 = "Data: {$oDataInicial->getDate(DBDate::DATA_PTBR)} à {$oDataFinal->getDate(DBDate::DATA_PTBR)}";

$head4 = "Imprime saldo por dia: Sim";
if (!$oGet->saldopordia) {
  $head4 = "Imprime saldo por dia: Não";
}

$head5 = "Quebra de página por conta: Sim";
if (!$oGet->quebrapaginaporconta) {
  $head5 = "Quebra de página por conta: Não";
}
/* 
$head6 = "Imprime conta sem movimento: Sim";
if (!$oGet->contasemmov) {
  $head6 = "Imprime conta sem movimento: Não";
}
 */
$oPdf = new PDF;
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetFillColor(235);
$oPdf->SetFont('Arial', '', 8);
if (!$oGet->quebrapaginaporconta) {
  $oPdf->AddPage();
}
$iAltura = '4';

/**
 * Imprimimos os dados no PDF
 *
 *
 */
foreach ($aDadosImprimir as $iIndice => $oStdDadoRelatorio) {


  if (($oStdDadoRelatorio->nValorTotalDebito == 0 && $oStdDadoRelatorio->nValorTotalCredito == 0)) {
    continue;
  }

  if ($oGet->quebrapaginaporconta) {
    $oPdf->AddPage();
  }
  imprimirCabecalhoDaConta($oPdf, $iAltura, $oStdDadoRelatorio);

  foreach ($oStdDadoRelatorio->aLancamentos as $iIndice => $oStdLancamento) {

    if ($oPdf->gety() > $oPdf->h-50) {

      $oPdf->AddPage();
      imprimirCabecalhoDosLancamentos($oPdf, $iAltura, $lImprimirData);
    }
    if ($iIndice == 0) {
      imprimirCabecalhoDosLancamentos($oPdf, $iAltura, $lImprimirData);
    }
    /*
     * Inicia a impressão dos dados do relatório
     */
    $iColunaData = 15;
    if ($lImprimirData) {

      $oPdf->cell(20, $iAltura, $oStdLancamento->oDataLancamento->getDate(DBDate::DATA_PTBR) , 0, 0, "C", 0);
      $iColunaData = 0;
    }

    $sDescricaoDocumento = "{$oStdLancamento->iCodigoDocumento} - {$oStdLancamento->sDescricaoDocumento}";
    $oPdf->cell(94+$iColunaData, $iAltura, $sDescricaoDocumento                , 0, 0, "L", 0);
    $oPdf->cell(40, $iAltura, db_formatar($oStdLancamento->nValorDebito, "f")  , 0, 0, "R", 0);
    $oPdf->cell(40, $iAltura, db_formatar($oStdLancamento->nValorCredito, "f") , 0, 1, "R", 0);
  }
  imprimeTotalPorConta($oPdf, $iAltura, $oStdDadoRelatorio, $lImprimirData);
}
unset($aDadosImprimir);

$oPdf->output();



/**
 * Função que imprime o cabeçalho
 * @param $oPdf
 * @param $iAltura
 * @param $oStdDado
 */
function imprimirCabecalhoDaConta($oPdf, $iAltura, $oStdDado) {

  $oPdf->ln(3);
  $oPdf->SetFont('', 'B', 8);
  $oPdf->cell(30, $iAltura, "REDUZIDO:"                 , 0, 0, "L", 0);
  $oPdf->SetFont('', '', 8);
  $oPdf->cell(80, $iAltura, $oStdDado->iCodigoReduzido  , 0, 1, "L", 0);
  $oPdf->SetFont('', 'B', 8);
  $oPdf->cell(30, $iAltura, "ESTRUTURAL:"               , 0, 0, "L", 0);
  $oPdf->SetFont('', '', 8);
  $oPdf->cell(80, $iAltura, $oStdDado->sEstrutural      , 0, 1, "L", 0);
  $oPdf->SetFont('', 'B', 8);
  $oPdf->cell(30, $iAltura, "DESCRIÇÃO:"                , 0, 0, "L", 0);
  $oPdf->SetFont('', '', 8);
  $oPdf->cell(150, $iAltura, $oStdDado->sDescricaoConta , 0, 1, "L", 0);
}


/**
 * Imprime o cabeçalho das movimentações
 * @param $oPdf
 * @param $iAltura
 * @param $lImprimirData
 */
function imprimirCabecalhoDosLancamentos($oPdf, $iAltura, $lImprimirData) {

  $oPdf->SetFont('', 'B', 8);
  $iColunaData = 15;
  if ($lImprimirData) {

    $oPdf->cell(20, $iAltura, "DATA"     , 0, 0, "C", 1);
    $iColunaData = 0;
  }

  $oPdf->cell(94+$iColunaData, $iAltura, "DOCUMENTO", 0, 0, "C", 1);
  $oPdf->cell(40, $iAltura, "DÉBITO"   , 0, 0, "C", 1);
  $oPdf->cell(40, $iAltura, "CRÉDITO"  , 0, 1, "C", 1);
  $oPdf->SetFont('', '', 8);
}

/**
 * Imprime a linha com os totalizadores da conta
 * @param $oPdf
 * @param $iAltura
 * @param $oStdDadoRelatorio
 * @param $lImprimirData
 */
function imprimeTotalPorConta($oPdf, $iAltura, $oStdDadoRelatorio, $lImprimirData) {

  $iTamanhoAdicional = 0;
  if ($lImprimirData) {
    $iTamanhoAdicional = 5;
  }
  $oPdf->SetFont('', 'B', 8);
  $oPdf->cell(109+$iTamanhoAdicional, $iAltura, "TOTAL DO PERÍODO", 0, 0, "R", 1);
  $oPdf->cell(40, $iAltura, db_formatar($oStdDadoRelatorio->nValorTotalDebito, "f")  , 0, 0, "R", 1);
  $oPdf->cell(40, $iAltura, db_formatar($oStdDadoRelatorio->nValorTotalCredito, "f") , 0, 1, "R", 1);
  $oPdf->SetFont('', 'B', 8);
}