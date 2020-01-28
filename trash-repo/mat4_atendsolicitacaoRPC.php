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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("dbforms/db_funcoes.php");
require ("classes/db_matpedidotransf_classe.php");
include("classes/db_db_almox_classe.php");
include ("model/solicitacaoMaterial.model.php");
include ("classes/materialestoque.model.php");
include ("classes/db_matparam_classe.php");
include ("libs/JSON.php");
$clmatpedidotransf = new cl_matpedidotransf ( );
$cldb_dbalmox = new cl_db_almox();
$oJson = new services_json ( );
$oParam = $oJson->decode ( str_replace ( "\\", "", $_POST ["json"] ) );
require_once "libs/db_app.utils.php";
db_app::import("contabilidade.contacorrente.ContaCorrenteFactory");
db_app::import("Acordo");
db_app::import("AcordoComissao");
db_app::import("CgmFactory");
db_app::import("financeiro.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("contabilidade.planoconta.*");
db_app::import("contabilidade.contacorrente.*");
if ($oParam->exec == "getDados_solicitacao") {

  try {

     $oSolicitacao = new solicitacaoMaterial ( $oParam->params [0]->iCodSol );
     $oSolicitacao->setEncode ( true );
     if ($oSolicitacao->getDados ()) {

       $oRetorno = $oSolicitacao->getInfo();
       if($oRetorno->m91_depto!=""){

     	 $sql=$cldb_dbalmox->sql_record($cldb_dbalmox->sql_query("","descrdepto as descr","","m91_depto= ".$oRetorno->m91_depto));
      	 db_fieldsmemory($sql,0);
      	 $oRetorno->descr_depto= $descr;
        }

	$oRetorno->itens = $oSolicitacao->getItens ();
	$oRetorno->status = 1;
	$oRetorno->message = null;
	echo $oJson->encode ( $oRetorno );

     } else {

         echo $oJson->encode ( array ("status" => 2, "message" => urlencode ( "Nуo Foi possivel consultar itens." ) ) );
		}
	} catch ( Exception $eExeption ) {

		$sError = $eExeption->getMessage ();
		echo $oJson->encode ( array ("status" => 2, "message" => urlencode ( $sError ) ) );
	}
}

if ($oParam->exec == "getLotes") {

	try {

		$oMaterialEstoque = new materialEstoque ( $oParam->params [0]->iCodMater );
		$oItens = $oMaterialEstoque->ratearLotes ( $oParam->params [0]->nValor, null, $oParam->params [0]->iCodEstoque );
		if (count ( $oItens ) > 0) {

			$oRetorno->itens = $oItens;
			$oRetorno->status = 1;
			$oRetorno->message = null;

			echo $oJson->encode ( $oRetorno );
		} else {
			echo $oJson->encode ( array ("status" => 2, "message" => urlencode ( "Nуo Foi possivel consultar itens." ) ) );
		}
	} catch ( Exception $eException ) {

		$sError = $eException->getMessage ();
		echo $oJson->encode ( array ("status" => 2, "message" => urlencode ( $sError ) ) );

	}

}
if ($oParam->exec == "saveLote") {

	$oMaterialEstoque = new materialEstoque ( $oParam->params [0]->iCodMater );
	$oMaterialEstoque->saveLoteSession ( $oParam->params [0]->aItens );
	echo $oJson->encode ( array ("status" => 1, "message" => "" ) );

}
if ($oParam->exec == "cancelarLote") {

	$oMaterialEstoque = new materialEstoque ( $oParam->params [0]->iCodMater );
	$oMaterialEstoque->cancelarLoteSession ();
	echo $oJson->encode ( array ("status" => 1, "message" => "" ) );

}
if ($oParam->exec == "atendeSolicitacao") {

	$valores = null;
	$obs     = "Atendimento de Transferъncia";
	db_inicio_transacao ();
	try {

		foreach ( $oParam->params [0]->aItens as $oMaterial ) {

			$oMaterialEstoque = new materialEstoque ( $oMaterial->iCodMater );
			$oMaterialEstoque->transferirMaterial ( $oMaterial->nQtde, $oMaterial->iCodDepto, $oMaterial->iCodEstoque, $valores, $obs, $oMaterial->iMatPedidoItem);

			$valores = $oMaterialEstoque->getiCodMovimento ();
			$oMaterialEstoque->cancelarLoteSession ();
			$erro_msg = "usuсrio:\\n\\nInclusуo efetuada com sucesso\\n\\nValores:" . $oMaterialEstoque->getiCodMovimento ();
			$erro_msg .= "\\n\\nAdministrador:";
		}
		
  	db_fim_transacao ( false );

	}

	catch ( Exception $eErro ) {

		$sqlerro = true;
		$erro_msg = str_replace ( "\n", "\\n", $eErro->getMessage () );
		$oMaterialEstoque->cancelarLoteSession ();

	}
	echo $oJson->encode ( array ("status" => 1, "message" => "" ) );
}else if ($oParam->exec == "anulapedido") {  ///funчуo que faz a anulaчуo dos itens da requisiчуo

 	 db_inicio_transacao();
	 try {
	    foreach ($oParam->params[0]->aItens as $oMaterial) {
         $oMaterialEstoque = new materialEstoque($oMaterial->iCodMater);
         $oMaterialEstoque->anularPedido($oMaterial->nQtde,
                                               $oMaterial->sItemMotivo,
                                               $oMaterial->iCodMater,
                                               $oMaterial->iMatPedidoItem
                                               );
        }
        db_fim_transacao(false);
      }
	  catch (Exception  $eErro) {
	    $sqlerro = true;
	    $erro_msg = str_replace("\n", "\\n",$eErro->getMessage());

	  }
	  echo $oJson->encode(array("status" => 1, "message"=> "Inclusуo efetuada com Sucesso"));
}
?>