<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/requisicaoMaterial.model.php");
require_once("classes/materialestoque.model.php");
require_once("classes/db_matparam_classe.php");
require_once("classes/db_db_almox_classe.php");
require_once("libs/JSON.php");
require_once "libs/db_app.utils.php";

require_once "std/DBDate.php";

require_once("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteBase.model.php");
require_once("model/financeiro/ContaBancaria.model.php");
require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once("model/contabilidade/planoconta/ContaCorrente.model.php");
require_once("model/contabilidade/planoconta/ContaOrcamento.model.php");
require_once("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");

db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");

db_app::import("contabilidade.contacorrente.*");

$cldb_dbalmox = new cl_db_almox;
$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
if ($oParam->exec == "getDados"){

  try {

    $oRequisicao  = new requisicaoMaterial($oParam->params[0]->iCodReq);
    $oRequisicao->setEncode(true);
    if ($oRequisicao->getDados()) {

      $oRetorno           = $oRequisicao->getInfo();
      $oRetorno->itens   =  $oRequisicao->getItens();
      $oRetorno->status  = 1;
      $oRetorno->message = null;

      echo $oJson->encode($oRetorno);

    } else {
      echo $oJson->encode(array("status" => 2, "message"=> urlencode("Nгo Foi possivel consultar itens.")));
    }
  }
  catch (Exception  $eExeption) {

    $sError = $eExeption->getMessage();
    echo $oJson->encode(array("status" => 2, "message"=>  urlencode($sError)));
  }
} else if ($oParam->exec == "getLotes") {

  try {

    $oMaterialEstoque = new materialEstoque($oParam->params[0]->iCodMater);
    $oItens           = $oMaterialEstoque->ratearLotes($oParam->params[0]->nValor, null,$oParam->params[0]->iCodEstoque);
    if (count($oItens) > 0) {

      $oRetorno->itens   = $oItens;
      $oRetorno->status  = 1;
      $oRetorno->message = null;

      echo $oJson->encode($oRetorno);
    } else {
      echo $oJson->encode(array("status" => 2, "message"=> urlencode("Nгo Foi possivel consultar itens.")));
    }
  }
  catch (Exception $eException) {

    $sError = $eException->getMessage();
    echo $oJson->encode(array("status" => 2, "message"=>  urlencode($sError)));

  }

} else if ($oParam->exec == "saveLote") {

  $oMaterialEstoque = new materialEstoque($oParam->params[0]->iCodMater);
  $oMaterialEstoque->saveLoteSession($oParam->params[0]->aItens);
  echo $oJson->encode(array("status" => 1, "message"=> ""));

}else if ($oParam->exec == "cancelarLote") {

  $oMaterialEstoque = new materialEstoque($oParam->params[0]->iCodMater);
  $oMaterialEstoque->cancelarLoteSession();
  echo $oJson->encode(array("status" => 1, "message"=> ""));

} else if ($oParam->exec == "atenderRequisicao") {
  try {

    db_inicio_transacao();

    $oRequisicao  = new requisicaoMaterial($oParam->params[0]->iCodReq);
    $oRequisicao->atenderRequisicao($oParam->params[0]->iTipo, $oParam->params[0]->aItens, $oParam->params[0]->iCodEstoque);

    db_fim_transacao(false);
    echo $oJson->encode(array("status" => 1, "message"=> urlencode("Atendimento Efetuado com Sucesso")));
  }
  catch (Exception $eErro) {

    db_fim_transacao(true);
    echo $oJson->encode(array("status" => 2, "message"=> urlencode(str_replace("\\n","\n", $eErro->getMessage()))));
  }

} else if ($oParam->exec == "saidaMaterial") {

  try {

    db_inicio_transacao();
    foreach ($oParam->params[0]->itens as $oMaterial) {

      $oMaterialEstoque = new materialEstoque($oMaterial->iCodMater);
      MaterialEstoque::bloqueioMovimentacaoItem($oMaterial->iCodMater, db_getsession("DB_coddepto"));
      if (isset($oMaterial->iCriterioCustoRateio)) {
        $oMaterialEstoque->setCriterioRateioCusto($oMaterial->iCriterioCustoRateio);
      }
      $oMaterialEstoque->saidaMaterial($oMaterial->nQuantidade, $oMaterial->sObs);
      db_fim_transacao(false);

    }
    echo $oJson->encode(array("status" => 1, "message"=> urlencode("Saнda Efetuada com Sucesso")));
  }
  catch (Exception $eErro) {

    $oMaterialEstoque->cancelarLoteSession();
    db_fim_transacao(true);
    echo $oJson->encode(array("status" => 2, "message"=> urlencode($eErro->getMessage())));
  }
} else if ($oParam->exec == "cancelarSaidaMaterial") {

  try {

    db_inicio_transacao();
    foreach ($oParam->params[0]->itens as $oMaterial) {


      $oMaterialEstoque = new materialEstoque($oMaterial->iCodMater);
      $oMaterialEstoque->cancelarSaidaMaterial($oMaterial->nQuantidade, $oMaterial->iCodEstoqueIni, $oMaterial->sObs);


    }
    db_fim_transacao(false);
    echo $oJson->encode(array("status" => 1, "message"=> urlencode("Cancelamento Efetuado com Sucesso")));
  }
  catch (Exception $eErro) {

    $oMaterialEstoque->cancelarLoteSession();
    db_fim_transacao(true);
    echo $oJson->encode(array("status" => 2, "message"=> urlencode($eErro->getMessage())));
  }
}else if ($oParam->exec == "getDadosPedidoRequisicao"){ ///traz os dados do atendimento requisicao para atende-la
  try {

    $oSolicitacao  = new requisicaoMaterial($oParam->params[0]->iCodReq);
    $oSolicitacao->setEncode(true);
    if ($oSolicitacao->getDadosPedidoRequisicao()) {

      $oRetorno           = $oSolicitacao->getInfo();
      unset($oRetorno->senha);
    if($oRetorno->m91_depto!=""){
      	 if($oRetorno->m91_depto!=""){
	       $sql=$cldb_dbalmox->sql_record($cldb_dbalmox->sql_query("","descrdepto as descr","","m91_depto= ".$oRetorno->m91_depto));
      	   db_fieldsmemory($sql,0);
      	   $oRetorno->descr_depto= $descr;
      }
      $oRetorno->itens   =  $oSolicitacao->getItensPedidoRequisicao();
      $oRetorno->status  = 1;
      $oRetorno->message = null;

      echo $oJson->encode($oRetorno);

    } else {
      echo $oJson->encode(array("status" => 2, "message"=> urlencode("Nгo Foi possivel consultar itens.")));
    }
    }
  } catch (Exception  $oExeption) {

    $sError = $oExeption->getMessage();
    echo $oJson->encode(array("status" => 2, "message"=>  urlencode($sError)));
  }
}else if ($oParam->exec == "anularRequisicao") {  ///funзгo que faz a anulaзгo dos itens da requisiзгo
 	 db_inicio_transacao();
	 try {
	    foreach ($oParam->params[0]->aItens as $oMaterial) {
         $oMaterialEstoque = new materialEstoque($oMaterial->iCodMater);

         $oMaterialEstoque->anularRequisicao($oMaterial->nQtde,
                                               db_stdClass::normalizeStringJsonEscapeString($oMaterial->sItemMotivo),
                                               $oMaterial->iCodMater,
                                               $oMaterial->iCodItemReq
                                               );

      }
      db_fim_transacao(false);
    } catch (Exception  $eErro) {
	    $sqlerro = true;
	    $erro_msg = str_replace("\n", "\\n",$eErro->getMessage());
	    db_fim_transacao(true);

	  }
	  echo $oJson->encode(array("status" => 1, "message"=> "Inclusгo efetuada com Sucesso"));
}
?>