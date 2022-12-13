<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/ParameterException.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();
try {
  switch ($oParam->exec) {

    case "getDadosOrdem":

      $oOrdemCompra = new OrdemDeCompra($oParam->iOrdemCompra);
      $oDadosOrdem  = new stdClass();

      $oDadosOrdem->iCodigoOrdem  = $oOrdemCompra->getCodigoOrdem();
      $oDadosOrdem->dEmissao      = db_formatar($oOrdemCompra->getEmissao()->getDate(),'d');
      $oDadosOrdem->iDepto        = $oOrdemCompra->getDepartamento()->getCodigo();
      $oDadosOrdem->sDepto        = urlencode($oOrdemCompra->getDepartamento()->getNomeDepartamento());
      $oDadosOrdem->iCgm          = $oOrdemCompra->getFornecedor()->getCodigo();
      $oDadosOrdem->sCgm          = urlencode($oOrdemCompra->getFornecedor()->getNome());
      $oDadosOrdem->sObservacao   = urlencode($oOrdemCompra->getObservacao());
      $oDadosOrdem->dAnulacao     = '';
      $oDadosOrdem->iTipoCompra   = $oOrdemCompra->getTipoCompra();
      $oDadosOrdem->nTotalOrdem   = db_formatar($oOrdemCompra->getTotalOrdem(), 'f');
      $oDadosOrdem->nValorLancado = db_formatar($oOrdemCompra->getValorLancado(), 'f');
      $oDadosOrdem->nValorLancar  = db_formatar(($oOrdemCompra->getValorLancar()-$oOrdemCompra->getValorAnulado()), 'f');
      $oDadosOrdem->nValorAnulado = db_formatar($oOrdemCompra->getValorAnulado(), 'f');

      if ($oOrdemCompra->getAnulacao()){
        $oDadosOrdem->dAnulacao    = db_formatar($oOrdemCompra->getAnulacao()->getDate(),'d');
      }
      $oRetorno->oDadosOrdem = $oDadosOrdem;

    break;

    case "getItens" :

      $oOrdemCompra          = new OrdemDeCompra($oParam->iOrdemCompra);
      $aItensOrdem           = $oOrdemCompra->getItens();
      $oRetorno->aItensOrdem = array();

      foreach ($aItensOrdem as $oItemOrdemDeCompra) {

        $oEmpenhoItem       = $oItemOrdemDeCompra->getItemEmpenho();
        $oEmpenhoFinanceiro = $oEmpenhoItem->getEmpenhoFinanceiro();
        $iQuantidadeAnulada = $oItemOrdemDeCompra->getQuantidadeAnulada();

        if (empty($iQuantidadeAnulada)) {
          $iQuantidadeAnulada = "";
        }

        $oDadosItem = new stdClass();
        $oDadosItem->sNumeroEmpenho        = $oEmpenhoFinanceiro->getCodigo() . "/" . $oEmpenhoFinanceiro->getAnoUso();
        $oDadosItem->iCodigoEmpenho        = $oEmpenhoFinanceiro->getNumero();
        $oDadosItem->iCodigoMaterial       = $oEmpenhoItem->getItemMaterialCompras()->getMaterial();
        $oDadosItem->sDescricaoMaterial    = $oEmpenhoItem->getItemMaterialCompras()->getDescricao();
        $oDadosItem->iSequencia            = $oEmpenhoItem->getSequencialAutorizacaoItem();
        $oDadosItem->sDescricaoSolicitacao = urlencode($oEmpenhoItem->getDescricao());
        $oDadosItem->iQuantidade           = $oItemOrdemDeCompra->getQuantidade();
        $oDadosItem->nValorUnitario        = db_formatar($oItemOrdemDeCompra->getValorUnitario(), 'f');
        $oDadosItem->nValorTotal           = db_formatar($oItemOrdemDeCompra->getValor(), 'f');
        $oDadosItem->nQuantidadeAnulada    = $iQuantidadeAnulada;
        $oRetorno->aItensOrdem[] = $oDadosItem;
      }

    break;

    case "getEntradas" :

      $oOrdemCompra        = new OrdemDeCompra($oParam->iOrdemCompra);
      $aEntradas           = $oOrdemCompra->getEntradas();
      $aDadosEntrada       = array();

      foreach ($aEntradas as $iEntradas => $oEntradas) {

        $oDados = new stdClass();
        $oDados->iMaterial         = $oEntradas->getItem()->getCodigo();
        $oDados->sMaterial         = urlencode($oEntradas->getItem()->getNome());
        $oDados->iQuantidade       = $oEntradas->getQuantidade();
        $oDados->iQuantidadeEntrada= $oEntradas->getQuantidadeEntrada();
        $oDados->iValor            = db_formatar($oEntradas->getValor(), "f");
        $oDados->sAlmoxarifado     = $oEntradas->getAlmoxarifado()->getNomeDepartamento();
        $oDados->sTipoMovimentacao = urlencode($oEntradas->getTipoMovimentacao()->getDescricao());

        $aDadosEntrada[]           = $oDados;

      }

      $oRetorno->aEntradas = $aDadosEntrada;

    break;

    default:
      throw new ParameterException("Nenhuma Opção Definida");
    break;

  }

  $oRetorno->sMessage = urlencode($oRetorno->sMessage);

} catch (Exception $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());

}
echo $oJson->encode($oRetorno);
