<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
require("classes/empenhoMigra.model.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oEmpenho = new empenhoMigra($oParam->iNumEmp);
$oEmpenho->setEncode(true);
if ($oParam->exec == "getDadosMigra") {
   
  $oEmpenho->getDados($oParam->iNumEmp);
  if ($oEmpenho->getOrdensPagamento()) {

    $oEmpenho->dadosEmpenho->aOrdensPagamento = $oEmpenho->aOrdensPagamento;
  }
  $rsItens = $oEmpenho->getItensSaldo();
  if ($rsItens) {
   
    for ($i = 0; $i < $oEmpenho->iNumRowsItens; $i++) {
      $oEmpenho->dadosEmpenho->aItens[] =     db_utils::fieldsMemory($rsItens, $i, false, false, true);
    }  
  }  
  echo $oJson->encode($oEmpenho->dadosEmpenho);

} else if ($oParam->exec == "gerarNota") {
 
   try {

      db_inicio_transacao();
      $oEmpenho->setMigrado($oParam->lMigrar);
      $oEmpenho->gerarNotasOrdem($oParam->iOrdem, $oParam->dtOrdem, $oParam->nTotalOrdem, $oParam->aItens) ;
      db_fim_transacao(false);
      $sMensagem = "Empenho Migrado com sucesso";
      $iStatus   = 1;
   }
   catch (Exception $e) {

     db_fim_transacao(true);
     $sMensagem = $e->getMessage();
     $iStatus   = 2;
   }
  echo  $oJson->encode(array("iStatus"=>$iStatus, "sMensagem"=>urlencode($sMensagem)));

} else if ($oParam->exec == "gerarItensNota") {
 
   try {

      db_inicio_transacao();
      $oEmpenho->setMigrado($oParam->lMigrar);
      $oEmpenho->gerarItensNota($oParam->iCodNota,$oParam->sTipo, $oParam->aItens, $oParam->dtOrdem) ;
      db_fim_transacao(false);
      $sMensagem = "Empenho Migrado com sucesso";
      $iStatus   = 1;
   }
   catch (Exception $e) {

     db_fim_transacao(true);
     $sMensagem = $e->getMessage();
     $iStatus   = 2;
   }
  echo  $oJson->encode(array("iStatus"=>$iStatus, "sMensagem"=>urlencode($sMensagem)));
}
?>