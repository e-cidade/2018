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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");

require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/financeiro/ContaBancaria.model.php");

require_once("model/contabilidade/contacorrente/ContaCorrenteRepositoryFactory.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteRepositoryBase.model.php");
require_once("model/contabilidade/contacorrente/DisponibilidadeFinanceiraRepository.model.php");
require_once("model/contabilidade/contacorrente/DomicilioBancarioRepository.model.php");
require_once("model/contabilidade/contacorrente/CredorFornecedorDevedorRepository.model.php");
require_once("model/contabilidade/contacorrente/AdiantamentoConcessaoRepository.model.php");

require_once("model/contabilidade/contacorrente/AdiantamentoConcessao.model.php");
require_once("model/contabilidade/contacorrente/CredorFornecedorDevedor.model.php");
require_once("model/contabilidade/contacorrente/DisponibilidadeFinanceira.model.php");
require_once("model/contabilidade/contacorrente/DomicilioBancario.model.php");
require_once("model/contabilidade/planoconta/ContaCorrente.model.php");


$oGet = db_utils::postMemory($_GET);

$sWhere = (empty($oGet->aCC)) ? "" : " c17_sequencial = {$oGet->aCC}";

$oDaoContaCorrente = db_utils::getDao("contacorrente");
$sSqlBuscaContas   = $oDaoContaCorrente->sql_query_file(null, "*", null, $sWhere);
$rsBuscaContas     = $oDaoContaCorrente->sql_record($sSqlBuscaContas);

if ($oDaoContaCorrente->numrows == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhuma conta corrente encontrada");
  exit;
}

$aDtInicial = explode("/", $oGet->dtInicial);
$aDtFinal   = explode("/", $oGet->dtFinal);

$dtInicial = "{$aDtInicial[2]}-{$aDtInicial[1]}-{$aDtInicial[0]}";
$dtFinal   = "{$aDtFinal[2]}-{$aDtFinal[1]}-{$aDtFinal[0]}";

$aRepository = array();
for ($iConta = 0; $iConta < $oDaoContaCorrente->numrows; $iConta++) {

  $oStdContaCorrente = db_utils::fieldsMemory($rsBuscaContas, $iConta);

  $aRepository[$oStdContaCorrente->c17_sequencial] = new stdClass();
  $aRepository[$oStdContaCorrente->c17_sequencial]->oContaCorrente = ContaCorrenteRepositoryFactory::getInstance($oStdContaCorrente->c17_sequencial, $dtInicial, $dtFinal);
  $aRepository[$oStdContaCorrente->c17_sequencial]->sContaCorrente = $oStdContaCorrente->c17_descricao;
}

$head2 = "BALANCETE DE CONTA CORRENTE";
$head4 = "Período: de {$oGet->dtInicial} a {$oGet->dtFinal}";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);
$oPdf->addpage();
$oPdf->ln(2);
$iFonte = 6;
$iAlturaLinha = 4;
$iRegistros   = 0;




foreach ($aRepository as $oContaCorrente) {

  $aDados = null;

  if (is_object($oContaCorrente->oContaCorrente)) {
    $aDados = $oContaCorrente->oContaCorrente->getDados();
  }

  if (!is_array($aDados)) {
    continue;
  }

  $iRegistros++;

  $oPdf->setfont('arial','b',8);
  $oPdf->cell(25, $iAlturaLinha,"Conta Corrente: ", "" , 0, "" , 0);
  $oPdf->setfont('arial','',8);
  $oPdf->cell(150, $iAlturaLinha,"{$oContaCorrente->sContaCorrente} ", "" , 0, "" , 0);
  $oPdf->ln(4);



  foreach ($aDados as $oConta) {

    if ($oPdf->gety() > $oPdf->h-50) {
      $oPdf->addpage();
    }
    /**
     * Imprime os cabecalhos conforme conta corrente
     */
    $oPdf->setfont('arial','',$iFonte);
    foreach ($oConta->aCabecalho as $oCabecalho) {

      $oPdf->setfont('arial','b',$iFonte);
      $oPdf->cell(40, $iAlturaLinha, $oCabecalho->sIdentificador, "", 0, "", 0);
      $oPdf->setfont('arial','',$iFonte);
      $oPdf->cell(200, $iAlturaLinha, $oCabecalho->sValor, "" , 0, "" , 0);
      $oPdf->ln(3);
    }

    imprimeCabecalhoConta($oPdf, $iAlturaLinha);

    $fTotalDebito        = 0;
    $fTotalCredito       = 0;
    $fTotalSaldoAnterior = 0;
    $fTotalSaldoFinal    = 0;

    $aDigitosNegativo = array(2, 6, 8);
    $aDigitosPositivo = array(1, 5, 7);

    foreach ($oConta->aContas as $oStdMovimento) {

      $iDigitoEstrutural = substr($oStdMovimento->contaPCASP->c60_estrut, 0, 1);

      $oPdf->setfont('arial','',$iFonte);

      //Dados da conta
      $sDescricaoConta = substr($oStdMovimento->contaPCASP->c60_descr, 0, 28);
      $oPdf->cell(20, $iAlturaLinha, "{$oStdMovimento->contaPCASP->c60_estrut}", "" , 0, "L" , 0);
      $oPdf->cell(40, $iAlturaLinha, "{$sDescricaoConta}", "" , 0, "L" , 0);
      $oPdf->cell(10, $iAlturaLinha, "{$oStdMovimento->contaPCASP->c61_reduz}", "" , 0, "L" , 0);

      if (in_array($iDigitoEstrutural, $aDigitosNegativo)) {
        $fTotalSaldoAnterior -= $oStdMovimento->aMovimentacoes->fSaldoAnterior;
      } else {
        $fTotalSaldoAnterior += $oStdMovimento->aMovimentacoes->fSaldoAnterior;
      }

      //Saldo Anterior
      $oPdf->cell(30, $iAlturaLinha, "{$oStdMovimento->aMovimentacoes->sSaldoAnterior}", "" , 0, "R" , 0);

      //Debito
      $fDebito       = db_formatar($oStdMovimento->aMovimentacoes->fDebito, "f");
      $fTotalDebito += $oStdMovimento->aMovimentacoes->fDebito;
      $oPdf->cell(30, $iAlturaLinha, "{$fDebito}", "" , 0, "R" , 0);

      //Credito
      $fCredito       = db_formatar($oStdMovimento->aMovimentacoes->fCredito, "f");
      $fTotalCredito += $oStdMovimento->aMovimentacoes->fCredito;
      $oPdf->cell(30, $iAlturaLinha, "{$fCredito}", "" , 0, "R" , 0);

      //Saldo Final
      $oPdf->cell(30, $iAlturaLinha, "{$oStdMovimento->aMovimentacoes->sSaldoFinal}", "" , 0, "R" , 0);

      if (in_array($iDigitoEstrutural, $aDigitosNegativo)) {
        $fTotalSaldoFinal -= $oStdMovimento->aMovimentacoes->fSaldoFinal;
      } else {
        $fTotalSaldoFinal += $oStdMovimento->aMovimentacoes->fSaldoFinal;
      }

      $oPdf->ln(4);

    }

    $oPdf->ln(3);
    $oPdf->setfont('arial','b',$iFonte);
    $oPdf->cell(70, $iAlturaLinha,"TOTAIS:", "TB" , 0, "R" , 1);
    $oPdf->cell(30, $iAlturaLinha, db_formatar($fTotalSaldoAnterior, "f"), "TB" , 0, "R" , 1);
    $oPdf->cell(30, $iAlturaLinha, db_formatar($fTotalDebito, "f"), "TB" , 0, "R" , 1);
    $oPdf->cell(30, $iAlturaLinha, db_formatar($fTotalCredito, "f"), "TB" , 0, "R" , 1);
    $oPdf->cell(32, $iAlturaLinha, db_formatar($fTotalSaldoFinal, "f"), "TB", 0, "R", 1);
    $oPdf->ln(5);

  }

}

if ($iRegistros <= 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado para os filtros selecionados.");
}
$oPdf->Output();

function imprimeCabecalhoConta($oPdf, $iAlturaLinha) {

  $oPdf->ln(3);
  $oPdf->setfont('arial', 'b', 6);
  $oPdf->cell(73, $iAlturaLinha,"CONTA CONTÁBIL", "TB" , 0, "C" , 1);
  $oPdf->cell(27, $iAlturaLinha,"SALDO ANTERIOR", "TB" , 0, "L" , 1);
  $oPdf->cell(30, $iAlturaLinha,"DÉBITO", "TB" , 0, "C" , 1);
  $oPdf->cell(30, $iAlturaLinha,"CRÉDITO", "TB" , 0, "C" , 1);
  $oPdf->cell(32, $iAlturaLinha,"SALDO FINAL", "TB", 0, "C", 1);
  $oPdf->setfont('arial', '', 6);
  $oPdf->ln(5);
}
