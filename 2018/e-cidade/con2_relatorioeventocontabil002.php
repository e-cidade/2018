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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_libtxt.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/contabilidade/EventoContabil.model.php"));
require_once(modification("model/contabilidade/EventoContabilLancamento.model.php"));
require_once(modification("model/contabilidade/RegraLancamentoContabil.model.php"));

$oGet         = db_utils::postMemory($_GET);
$oGet->iAno   = db_getsession("DB_anousu");
$iInstituicao = db_getsession('DB_instit');

/**
 * Dados
 */
$oDaoContrans      = new cl_contrans;
if (!empty($oGet->iCodigoLancamento)) {
  $sWhereContrans[] = "c46_seqtranslan = {$oGet->iCodigoLancamento}";
}
if (!empty($oGet->iCodigosDocumentos)) {
  $sWhereContrans[] = "c45_coddoc in({$oGet->iCodigosDocumentos})";
}
$sWhereContrans[]  = "c45_anousu = {$oGet->iAno} and c45_instit = {$iInstituicao}";
$sCampos           = "c46_seqtranslan, c45_coddoc, c53_descr";
$sOrdem            = "c45_coddoc, c46_ordem asc";
$sSqlBuscaContrans = $oDaoContrans->sql_query_evento_contabil(null, $sCampos, $sOrdem, implode(' and ', $sWhereContrans));
$rsBuscaContrans   = $oDaoContrans->sql_record($sSqlBuscaContrans);

if ($oDaoContrans->numrows === 0) {

  $sMensagem = "Nenhum registro encontrado.";
  db_redireciona('db_erros.php?fechar=true&db_erro=' . urlencode($sMensagem));
  exit;
}
for ($iRowLancamento = 0; $iRowLancamento < $oDaoContrans->numrows; $iRowLancamento++) {

  $oDadosBusca          = db_utils::fieldsMemory($rsBuscaContrans, $iRowLancamento);
  $oLancamentoEvento    = EventoContabilLancamentoRepository::getEventoByCodigo($oDadosBusca->c46_seqtranslan);
  $oDados               = new stdClass;
  $oDados->oLancamento  = $oLancamentoEvento;
  $oDados->iDocumento   = $oDadosBusca->c45_coddoc;
  $oDados->sDocumento   = $oDadosBusca->c53_descr;
  $aLancamentosEvento[] = $oDados;
}

/**
 * Monta os dados para impressão
 */
$aDadosImprimir = array();
foreach ($aLancamentosEvento as $iIndiceLancamento => $oDados) {

  /*
   * Dados do Lancamento que serão impressos no relatório
   */
  $oStdClassLancamento 											 = new stdClass();
  $oStdClassLancamento->iCodigo              = $oDados->oLancamento->getSequencialLancamento();
  $oStdClassLancamento->iDocumento           = $oDados->iDocumento;
  $oStdClassLancamento->sDocumento           = $oDados->sDocumento;
  $oStdClassLancamento->iCodigoLancamento    = $oDados->oLancamento->getSequencialLancamento();
  $oStdClassLancamento->iOrdem               = $oDados->oLancamento->getOrdem();
  $oStdClassLancamento->sDescricaoLancamento = $oDados->oLancamento->getDescricao();
  $oStdClassLancamento->aRegrasLancamento    = array();
  $aRegrasLancamento                         = $oDados->oLancamento->getRegrasLancamento();
  foreach ($aRegrasLancamento as $iIndiceRegra => $oRegra) {

    /*
     * Utilizo a função para buscar os dados estruturais e descricao da conta reduzida
     */
    $oReduzidoDebito  = getInformacaoReduzidos($oRegra->getContaDebito());
    $oReduzidoCredito = getInformacaoReduzidos($oRegra->getContaCredito());
    /*
     *	Crio um objeto com os dados da regra que serão impressos no relatório
     */
    $iReduzidoDebito  = $oRegra->getContaDebito();
    $iReduzidoCredito = $oRegra->getContaCredito();
    $oStdClassRegra                           = new stdClass();
    $oStdClassRegra->iReduzidoDebito          = $iReduzidoDebito ? $iReduzidoDebito : '0';
    $oStdClassRegra->sEstruturalDebito        = $oReduzidoDebito ? $oReduzidoDebito->sEstrutural : '';
    $oStdClassRegra->sDescricaoDebito         = $oReduzidoDebito ? $oReduzidoDebito->sDescricao : '';
    $oStdClassRegra->iReduzidoCredito         = $iReduzidoCredito ? $iReduzidoCredito : '0';
    $oStdClassRegra->sEstruturalCredito       = $oReduzidoCredito ? $oReduzidoCredito->sEstrutural : '';
    $oStdClassRegra->sDescricaoCredito        = $oReduzidoCredito ? $oReduzidoCredito->sDescricao : '';
    $oStdClassLancamento->aRegrasLancamento[] = $oStdClassRegra;
  }

  $aDadosImprimir[] = $oStdClassLancamento;
}

/**
 * Emissão
 */
$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','',8);
$oPdf->SetAutoPageBreak(false);
$iAltura = 4;
$head2    = "Relatório de Eventos Contábeis";
$head3    = "Ano: {$oGet->iAno}";
$aDocumentos = explode(',', $oGet->iCodigosDocumentos);
if (!empty($oGet->iCodigosDocumentos) && count($aDocumentos) === 1) {

  $oDaoConhistdoc     = new cl_conhistdoc;
  $sSqlBuscaDescricao = $oDaoConhistdoc->sql_query_file($oGet->iCodigosDocumentos);
  $rsBuscaDescricao   = $oDaoConhistdoc->sql_record($sSqlBuscaDescricao);
  $oDadosDocumento    = db_utils::fieldsMemory($rsBuscaDescricao, 0);
  $head4              = "Documento: {$oDadosDocumento->c53_coddoc} - {$oDadosDocumento->c53_descr}";
}

$iLancamentoAnterior = null;
$iDocumentoAnterior = null;
foreach ($aDadosImprimir as $iIndice => $oEvento) {

  if ($iDocumentoAnterior != $oEvento->iDocumento) {

    showTituloRelatorio($oPdf, $iAltura, $oEvento->iDocumento, $oEvento->sDocumento);
    $iDocumentoAnterior = $oEvento->iDocumento;
  }

  if ($iLancamentoAnterior != $oEvento->iCodigoLancamento || $oPdf->GetY() > $oPdf->h - 30) {

    if ($iDocumentoAnterior == $oEvento->iDocumento && $oPdf->GetY() > $oPdf->h - 30) {
      showTituloRelatorio($oPdf, $iAltura, $oEvento->iDocumento, $oEvento->sDocumento);
    }

    showTituloLancamento($oPdf, $iAltura, $oEvento->iOrdem, $oEvento->sDescricaoLancamento, $oEvento->iCodigo);
    showHeaderDados($oPdf, $iAltura);

    $iLancamentoAnterior = $oEvento->iCodigoLancamento;
  }

  if (empty($oEvento->aRegrasLancamento)) {

    $oPdf->cell(140, $iAltura, "Não Cadastrado",  0, 0, "L", 0);
    $oPdf->cell(140, $iAltura, "Não Cadastrado",  0, 1, "L", 0);
  }

  foreach ($oEvento->aRegrasLancamento as $iIndiceRegra => $oRegra) {

    if ($oPdf->GetY() > $oPdf->h - 20) {

      showTituloRelatorio($oPdf, $iAltura, $oEvento->iDocumento, $oEvento->sDocumento);
      showTituloLancamento($oPdf, $iAltura, $oEvento->iOrdem, $oEvento->sDescricaoLancamento, $oEvento->iCodigo);
      showHeaderDados($oPdf, $iAltura);
    }

    $oPdf->cell(20, $iAltura, $oRegra->iReduzidoDebito,   0, 0, "C", 0);
    $oPdf->cell(30, $iAltura, $oRegra->sEstruturalDebito, 0, 0, "C", 0);
    $oPdf->cell(90, $iAltura, $oRegra->sDescricaoDebito,  0, 0, "L", 0);

    $oPdf->cell(20, $iAltura, $oRegra->iReduzidoCredito,   0, 0, "C", 0);
    $oPdf->cell(30, $iAltura, $oRegra->sEstruturalCredito, 0, 0, "C", 0);
    $oPdf->cell(90, $iAltura, $oRegra->sDescricaoCredito,  0, 1, "L", 0);
  }
}

function getInformacaoReduzidos($iCodigoReduzido) {

  $oDaoConplano    = db_utils::getDao('conplanoreduz');
  $sWhereReduzido  = "     c61_reduz  = {$iCodigoReduzido} ";
  $sWhereReduzido .= " and c61_anousu = ".db_getsession('DB_anousu');
  $sSqlBuscaDados  = $oDaoConplano->sql_query(null, null, 'c60_estrut, c60_descr', null, $sWhereReduzido);
  $rsBuscaReduzido = $oDaoConplano->sql_record($sSqlBuscaDados);
  if ($oDaoConplano->numrows == 0) {
    return false;
  }
  $oDadoReduzido   = db_utils::fieldsMemory($rsBuscaReduzido, 0);

  $oStdClassLancamento   					  = new stdClass();
  $oStdClassLancamento->sEstrutural = $oDadoReduzido->c60_estrut;
  $oStdClassLancamento->sDescricao  = $oDadoReduzido->c60_descr;
  return $oStdClassLancamento;
}

function showTituloRelatorio($oPdf, $iAltura, $iCodigoDocumento, $iDescricaoDocumento) {

  $oPdf->addPage("L");
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->MultiCell(280, $iAltura, "Documento: {$iCodigoDocumento} - $iDescricaoDocumento", 1, "L", 1);
}

function showTituloLancamento($oPdf, $iAltura, $iOrdem, $sDescricao, $iCodigo) {

  if ($oPdf->GetY() > $oPdf->h - 30) {
    $oPdf->addPage("L");
  }

  $oPdf->setfont('arial', 'b', 8);
  $oPdf->MultiCell(280, $iAltura, "Lançamento: {$iOrdem} - {$sDescricao} - Código: {$iCodigo}", 1, "L", 1);
}

function showHeaderDados($oPdf, $iAltura) {

  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(140, $iAltura, "Conta Débito" , 1, 0, "C", 1);
  $oPdf->cell(140, $iAltura, "Conta Crédito", 1, 1, "C", 1);

  $oPdf->cell(20, $iAltura, "Reduzido",   1, 0, "C", 1);
  $oPdf->cell(30, $iAltura, "Estrutural", 1, 0, "C", 1);
  $oPdf->cell(90, $iAltura, "Descrição",  1, 0, "C", 1);

  $oPdf->cell(20, $iAltura, "Reduzido",   1, 0, "C", 1);
  $oPdf->cell(30, $iAltura, "Estrutural", 1, 0, "C", 1);
  $oPdf->cell(90, $iAltura, "Descrição",  1, 1, "C", 1);
  $oPdf->setfont('arial', '', 8);
}

$oPdf->Output();
