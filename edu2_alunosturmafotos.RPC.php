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

require_once ("std/db_stdClass.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->dados    = new stdClass();
$oRetorno->status   = 1;

switch ($oParam->exec){

  case "PesquisaCalendario":

    $oDaoCalendario     = db_utils::getdao('calendario');
    
    $sCampos = "ed52_i_codigo,ed52_c_descr,ed52_i_ano";
    $sWhere  = "     ed38_i_escola = ".db_getsession("DB_coddepto");
    $sWhere .= " and ed52_c_passivo = 'N'";
    
	  $sSqlCalendario     = $oDaoCalendario->sql_query_calendariorelatorio("", $sCampos, "ed52_i_ano desc", $sWhere);                                                                       
	  $rsResultCalendario = $oDaoCalendario->sql_record($sSqlCalendario);    

    if ($oDaoCalendario->numrows > 0) {
      
      $oRetorno->iEscola  = db_getsession("DB_coddepto");
      $oRetorno->dados    = db_utils::getColectionByRecord($rsResultCalendario, false, false, true);   
    
    } else {
      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar o Calendário escolhido!");
    }

    break;
  case "PesquisaTurmaEscola": 
    
    $iEscola     = db_getsession("DB_coddepto");
    $oDaoTurma   = db_utils::getdao('serie');
    $sWhereTurma = " ed221_c_origem = 'S' ";
    if (isset($oParam->iCurso) && trim($oParam->iCurso) != "") {
      $sWhereTurma .= " and ed11_i_ensino= {$oParam->iCurso} ";
    }
    if (isset($oParam->iCalendario) && trim($oParam->iCalendario) != "") {
      $sWhereTurma .= " and ed52_i_codigo= {$oParam->iCalendario} ";
    }
    if (isset($oParam->iEscola) && trim($oParam->iEscola) != "") {
      $iEscola = $oParam->iEscola;
    }
    $sWhereTurma  .= " and ed57_i_escola = {$iEscola} ";
    
    $sCampos         = "distinct ed57_i_codigo as codigo_turma, ed57_c_descr as nome_turma";
    $sSqlTurma       = $oDaoTurma->sql_query_relatorio("", $sCampos, "2", $sWhereTurma);
    $rsResultTurma   = $oDaoTurma->sql_record($sSqlTurma);
    $oRetorno->dados = db_utils::getCollectionByRecord($rsResultTurma, false, false, true);
    break;
}
echo $oJson->encode($oRetorno);