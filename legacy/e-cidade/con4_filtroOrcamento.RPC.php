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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
require_once("model/filtroOrcamento.model.php");
$oJson       = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
switch ($oParam->exec) {
  
  case "getDadosOrcamento":
    
    $oFiltroOrcamento    = new filtroOrcamento();
    $oRetorno->orgao     = $oFiltroOrcamento->getOrgaos(db_getsession("DB_anousu"));
    $oRetorno->unidade   = $oFiltroOrcamento->getUnidades(db_getsession("DB_anousu"));
    $oRetorno->funcao    = $oFiltroOrcamento->getFuncoes(db_getsession("DB_anousu"));
    $oRetorno->subfuncao = $oFiltroOrcamento->getSubFuncoes(db_getsession("DB_anousu"));
    $oRetorno->programa  = $oFiltroOrcamento->getProgramas(db_getsession("DB_anousu"));
    $oRetorno->projativ  = $oFiltroOrcamento->getProjAtiv(db_getsession("DB_anousu"));
    $oRetorno->elemento  = $oFiltroOrcamento->getElementos(db_getsession("DB_anousu"));
    $oRetorno->recurso   = $oFiltroOrcamento->getRecursos(db_getsession("DB_anousu"));
    break;
  
}
echo $oJson->encode($oRetorno);
?>