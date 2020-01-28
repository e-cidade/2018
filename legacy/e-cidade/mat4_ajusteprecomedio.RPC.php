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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("classes/materialestoque.model.php");
require_once("classes/db_matmaterprecomedio_classe.php");
require_once("classes/db_matestoqueini_classe.php");
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
$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro             = false;
$sMensagem         = "";

switch($oParam->exec) {

  case 'PrecoAtual' :

  	$icodMaterial    = $oParam->iCodMaterial;
	  $oValorMedio     = new materialEstoque($icodMaterial);
    $oRetorno->dados =  $oValorMedio->getPrecoMedio();

  break;


  case 'Ajusta' :

  	$sDtAjuste         = implode("-", array_reverse(explode("/",$oParam->sDtAjuste)));
  	$tHoraAjuste       = date("H:i:s");
  	$nValorPrecoMedio  = $oParam->nValorPrecoMedio;
  	$sMotivo           = $oParam->sMotivo;
  	$iCodMaterial      = $oParam->iCodMaterial;
  	db_inicio_transacao();
  	try {

	    $oNovoValorMedio = new materialEstoque($iCodMaterial);
	    $oNovoValorMedio->ajustaPrecoMedio($sDtAjuste, $tHoraAjuste, $nValorPrecoMedio, $sMotivo );
	    $oRetorno->message = "Processo efetuado com sucesso ! ";
	    db_fim_transacao(false);
  	} catch (Exception $eErro)  {

  		$oRetorno->message = urlencode($eErro->getMessage());
  		$oRetorno->status = 2;
  		db_fim_transacao(true);

    }

  break;

}

echo $oJson->encode($oRetorno);

?>