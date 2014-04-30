<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

ini_set("error_reporting","E_ALL & E_NOTICE");
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
require_once("model/linhaColunaRelatorio.model.php");
require_once("model/linhaRelatorioContabil.model.php");
include("libs/JSON.php");

$oGet              = db_utils::postMemory($_GET);
$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass;
$oRetorno->status  = 1;
$oRetorno->message = "";
$oRetorno->itens   = array();

if ($oParam->exec == "save") {
  
  try {
    
    $iAnoUsu = null;
    
    if (!empty($oParam->iAnoUsu)) {
      $iAnoUsu = $oParam->iAnoUsu;
    }
    
    db_inicio_transacao();
    foreach ($oParam->cols as $oColuna) {
      
      $oLinhaColuna = new linhaColunaRelatorio($oColuna->iCodigo);
      $oRetorno->itens[] = $oLinhaColuna->save($oParam->iLinha, utf8_decode($oColuna->nValor), $oColuna->iPeriodo, 
                                               $oColuna->iSeq, $iAnoUsu); 
    }
    $oRetorno->linha = $oParam->iLinha; 
    db_fim_transacao(false);
  } catch (Exception $eErroColuna) {
    
    db_fim_transacao(true);
    $oRetorno->message = urlencode($eErroColuna->getMessage());
    $oRetorno->status  = 2;
    
  }
  
 
} else if ($oParam->exec == "getValoresColunas") {
  
  $oLinha   = new linhaRelatorioContabil($oParam->iCodRel, $oParam->iLinhaRel, db_getsession("DB_instit"));
  $oLinha->setPeriodo($oParam->iPeriodo);
  $oLinha->setEncode(true);
  $aValores = $oLinha->getValoresColunas(null, null, null, $oParam->iAnoPesquisa);
  $oRetorno->itens = $aValores; 
  
} else if ($oParam->exec == "excluirLinha") {
  
  $oLinha   = new linhaRelatorioContabil($oParam->iCodRel, $oParam->iLinhaRel);
  try {
    
    db_inicio_transacao();
    $oLinha->setPeriodo($oParam->iPeriodo);
    $oLinha->excluirLinha($oParam->iLinha, db_getsession("DB_instit"), db_getsession("DB_anousu"));
    db_fim_transacao(false);
    
  } catch (Exception $eErroLinha) {
    
    db_fim_transacao(true);
    $oRetorno->message = urlencode($eErroLinha->getMessage());
    $oRetorno->status  = 2;
  }
  
}
echo $oJson->encode($oRetorno);