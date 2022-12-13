<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_app.utils.php");
require_once("libs/exceptions/BusinessException.php");

db_app::import("configuracao.*");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->message  = array();
$oRetorno->status   = 1;

$iInstituicaoSessao = db_getsession("DB_instit");
$iAnoSessao         = db_getsession("DB_anousu");

switch ($oParam->exec) {

  case "processarAlteracaoConta":

    try {

      db_inicio_transacao();

      $oDaoDisArq            = db_utils::getDao('disarq');
      $oDaoDisArq->k00_conta = $oParam->iContaTesouraria;
      $oDaoDisArq->codret    = $oParam->iCodigoRetorno;
      $oDaoDisArq->alterar($oParam->iCodigoRetorno);

      if ($oDaoDisArq->erro_status == "0") {
        throw new BusinessException("Não foi possível alterar a conta da tesouraria para o retorno {$oParam->iCodigoRetorno}.");
      }
      unset($oDaoDisArq);

      $oDaoDisArqArrePaga = db_utils::getDao('disarq');
      $sSqlArrePaga       = $oDaoDisArqArrePaga->sql_query_baixa_banco($oParam->iCodigoRetorno, "arrepaga.*");
      $rsArrePaga         = $oDaoDisArqArrePaga->sql_record($sSqlArrePaga);

      if ($oDaoDisArqArrePaga->erro_status == "0" || $oDaoDisArqArrePaga->numrows == 0) {
        throw new BusinessException("Não foi possível localizar os débitos pagos do retorno {$oParam->iCodigoRetorno}.");
      }

      for ($iRowArrePaga = 0; $iRowArrePaga < $oDaoDisArqArrePaga->numrows; $iRowArrePaga++) {

        $oStdArrePaga            = db_utils::fieldsMemory($rsArrePaga, $iRowArrePaga);
        $oDaoArrePaga             = db_utils::getDao('arrepaga');
        $oDaoArrePaga->k00_conta  = $oParam->iContaTesouraria;
        $sWhereAlterar            = "     k00_numpre = {$oStdArrePaga->k00_numpre} ";
        $sWhereAlterar           .= " and k00_numpar = {$oStdArrePaga->k00_numpar} ";
        $oDaoArrePaga->alterar(null, $sWhereAlterar);

        if ($oDaoArrePaga->erro_status == "0") {
          throw new BusinessException("Não foi possível alterar a conta do pagamento. Contate o suporte.");
        }
        unset($oDaoArrePaga);
        unset($oStdArrePaga);
      }

      $oRetorno->message = urlencode("Alteração efetuada com sucesso.");
      db_fim_transacao(false);

    } catch (BusinessException $eException) {

      db_fim_transacao(true);
      $oRetorno->message = urlencode($eException->getMessage());
      $oRetorno->status  = 2;
    }

    break;

  case "getContasClassificacao":

    $sCamposClassificacao  = " codcla, disarq.codret, k15_codbco, k15_codage, k00_conta, k13_descr";
    $sWhereClassificacao   = "     dtaute is null";
    $sWhereClassificacao  .= " and discla.instit = {$iInstituicaoSessao}";
    $oDaoDisCla            = db_utils::getDao('discla');
    $sSqlClassificacao     = $oDaoDisCla->sql_query_classificacao(null,
                                                                  $sCamposClassificacao,
                                                                  "codcla",
                                                                  $sWhereClassificacao);
    $rsBuscaClassificacao  = $oDaoDisCla->sql_record($sSqlClassificacao);
    $aContasRetorno        = db_utils::getCollectionByRecord($rsBuscaClassificacao);

    $oRetorno->aContas = $aContasRetorno;
    break;
}

echo $oJson->encode($oRetorno);