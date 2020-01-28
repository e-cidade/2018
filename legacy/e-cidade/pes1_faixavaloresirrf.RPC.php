<?php
/**
 *           E-cidade Software Publico para Gestao Municipal
 *        Copyright (C) 2016  DBSeller Servicos de Informatica
 *                        www.dbseller.com.br
 *                     e-cidade@dbseller.com.br
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_libpessoal.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

define("M", "recursoshumanos.pessoal.pes1_faixavaloresirrf.");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->message  = '';
$db_opcao           = 1;
try{

  switch ($oParam->exec) {

    case "carregar":
      $oRetorno->dados = carregarDados($oParam->tabela);
      break;
    case "salvar_faixa" :
      $oRetorno->message = salvarFaixa($oParam->oItem);
      break;
    case "excluir_faixa" :
      $oRetorno->message = excluirFaixa($oParam->oItem);
      break;
    default:
      $oRetorno->erro     = true;
      $oRetorno->message  = "Método não existe.";
      break;
  }

} catch(\Exception $oException) {

  $oRetorno->erro    = true;
  $oRetorno->message = $oException->getMessage();
}

function salvarFaixa($oItem) {

  if (!empty($oItem->rh175_sequencial) && !DBNumber::isInteger($oItem->rh175_sequencial)) {
    throw new BusinessException("Código da Faixa de Valores do RH não é um número válido.");
  }

  if (!empty($oItem->db150_sequencial) && !DBNumber::isInteger($oItem->db150_sequencial)) {
    throw new BusinessException("Código da Faixa de Valores não é um número válido.");
  }

  if (!DBNumber::isFloat($oItem->db150_inicio)) {
    throw new BusinessException("Valor início da Faixa de Valores não é um valor válido.");
  }

  if (!DBNumber::isFloat($oItem->db150_final)) {
    throw new BusinessException("Valor final da Faixa de Valores não é um valor válido.");
  }

  if (!DBNumber::isFloat($oItem->rh175_percentual)) {
    throw new BusinessException("Valor Percentual da Faixa de Valores não é um valor válido.");
  }

  if (!DBNumber::isFloat($oItem->rh175_deducao)) {
    throw new BusinessException("Valor da Dedução da Faixa de Valores não é um valor válido.");
  }




  $oDaoFaixaValoresIrrf = new cl_faixavaloresirrf();
  $oDaoFaixaValores     = new cl_db_faixavalores();

  $oDaoFaixaValores->db150_db_tabelavalores = $oItem->db149_sequencial;
  $oDaoFaixaValores->db150_inicio           = $oItem->db150_inicio;
  $oDaoFaixaValores->db150_final            = $oItem->db150_final;

  if (empty($oItem->db150_sequencial)) {
    $oDaoFaixaValores->incluir(null);
  } else {
    $oDaoFaixaValores->db150_sequencial = $oItem->rh175_sequencial;
    $oDaoFaixaValores->alterar($oItem->rh175_sequencial);
  }

  if ($oDaoFaixaValores->erro_status == "0") {
    throw new DBException(_M(M.'erro_salvar_faixa'));
  }

  $oDaoFaixaValoresIrrf->rh175_db_faixavalores = $oDaoFaixaValores->db150_sequencial;
  $oDaoFaixaValoresIrrf->rh175_percentual      = $oItem->rh175_percentual;
  $oDaoFaixaValoresIrrf->rh175_deducao         = $oItem->rh175_deducao;

  if (empty($oItem->rh175_sequencial)) {
    $oDaoFaixaValoresIrrf->incluir(null);
  } else {
    $oDaoFaixaValoresIrrf->rh175_sequencial    = $oItem->rh175_sequencial;
    $oDaoFaixaValoresIrrf->alterar($oItem->rh175_sequencial);
  }

  if ($oDaoFaixaValoresIrrf->erro_status == "0") {
    throw new DBException(_M(M.'erro_salvar_faixa_rh'));
  }

  return _M(M."incluido_com_sucesso");
}

function excluirFaixa($oItem) {

  if (!DBNumber::isInteger($oItem->rh175_sequencial)) {
    throw new BusinessException("Código da Faixa de Valores do RH não é um número válido.");
  }

  if (!DBNumber::isInteger($oItem->db150_sequencial)) {
    throw new BusinessException("Código da Faixa de Valores não é um número válido.");
  }

  $oDaoFaixaValoresIrrf = new cl_faixavaloresirrf();
  $oDaoFaixaValores     = new cl_db_faixavalores();

  $oDaoFaixaValoresIrrf->excluir($oItem->rh175_sequencial);


  if ($oDaoFaixaValoresIrrf->erro_status == "0") {
    throw new DBException(_M(M.'erro_excluir_faixa_rh'));
  }

  $oDaoFaixaValores->excluir($oItem->db150_sequencial);

  if ($oDaoFaixaValores->erro_status == "0") {
    throw new DBException(_M(M.'erro_excluir_faixa'));
  }

  return _M(M.'excluido_com_sucesso');
}

function carregarDados($iCodigoTabela) {

  if (!DBNumber::isInteger($iCodigoTabela)) {
    throw new BusinessException("Código da Tabela de Valores não é um número válido.");
  }

  $oDaoFaixaValoresIrrf = new cl_faixavaloresirrf();
  $sSql    = $oDaoFaixaValoresIrrf->sql_query(null, "*", "db150_inicio", "db149_sequencial = {$iCodigoTabela}");
  $rsSql   = db_query($sSql);
  if(!$sSql) {
    throw new DBException(_M(M."erro_carregamento_dados"));
  }

  return db_utils::getCollectionByRecord($rsSql);

}
$oRetorno->message = urlencode($oRetorno->message);
echo $oJson->encode($oRetorno);
