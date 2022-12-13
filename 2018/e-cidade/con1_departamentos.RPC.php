<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_depusu_classe.php");
require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_db_depart_classe.php");
require_once("libs/JSON.php");

$cldb_depusu       = new cl_db_depusu();
$cldb_usuarios     = new cl_db_usuarios();
$cldb_depart       = new cl_db_depart();

$cldb_depart       = new cl_db_depart();
$oJson             = new services_json();
$oRetorno          = new stdClass(); 

$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno->erro           = 0;
$oRetorno->aItensDptos    = array();
$oRetorno->aItensDptosSel = array();

$dtDataUsu = date('Y-m-d',db_getsession("DB_datausu"));
$iInstit   = db_getsession("DB_instit");

switch ($oParam->exec) {

  case "listarDepartamentos":
  	
  	$sCampos        = " db_depart.coddepto,db_depart.descrdepto       ";
  	$sWhere         = " (limite is null or limite >= '{$dtDataUsu}')  "; 
    $sWhere        .= " and instit = {$iInstit}                       ";
  	
  	$sSqlDbDpart    = $cldb_depart->sql_query_deptousuario(null,$sCampos,"descrdepto",$sWhere,$oParam->codusuario); 
  	$rsDbDepart     = $cldb_depart->sql_record($sSqlDbDpart);
  	
  	$sCampos        = " db_depart.coddepto, db_depart.descrdepto ";
  	$sWhere         = " db_depart.instit  = {$iInstit} ";
  	$sWhere        .= " and (limite is null or limite >= '{$dtDataUsu}')  "; 
  	$sWhere        .= " and db_depusu.id_usuario = {$oParam->codusuario}  ";
  	
    $sSqlDeptoSel   =  $cldb_depusu->sql_query(null,null,$sCampos,"db_depusu.db17_ordem",$sWhere); 
    $rsSqlDeptoSel  = $cldb_depusu->sql_record($sSqlDeptoSel);  	
  	
  	$oRetorno->aItensDptos    = db_utils::getCollectionByRecord($rsDbDepart,false,false,true);
  	$oRetorno->aItensDptosSel = db_utils::getCollectionByRecord($rsSqlDeptoSel,false,false,true); 	
  	
    break;   
    
  case "atualizarDepartamentos":

	  $sqlerro = false;
	  
	  db_inicio_transacao();
	  
	  if ( $sqlerro == false ) {
	
	  	$sCampos             = " db_depart.coddepto, db_depusu.db17_ordem             ";
	  	$sWhere              = " db_depart.instit  = {$iInstit}                       ";
	  	$sWhere             .= " and db_depusu.id_usuario = {$oParam->codusuario}     ";
	  	
	    $sSqlDeptoInstitUsu  =  $cldb_depusu->sql_query(null,null,$sCampos,null,$sWhere);
                                   
	    $rsDeptoInstitUsu    = $cldb_depusu->sql_record($sSqlDeptoInstitUsu);

	    if ($cldb_depusu->numrows > 0) {                                         
	      
	      $iNumRowsDepto = $cldb_depusu->numrows;
	      for ($i = 0; $i < $iNumRowsDepto; $i++) {
	        
	        $oUsuarioDeptoInstit = db_utils::fieldsMemory($rsDeptoInstitUsu, $i);  
	        $cldb_depusu->excluir($oParam->codusuario, $oUsuarioDeptoInstit->coddepto);
          $erro_msg = $cldb_depusu->erro_msg;
	        if ( $cldb_depusu->erro_status == 0 ) {
	        	
	          $sqlerro        = true;
	          $oRetorno->erro = 1;
	        }
	      }
	    }
	  }
	  
    foreach ($oParam->aDptoSel as $oDptoSel) {

      if( $sqlerro == false ) {
        
      	$cldb_depusu->db17_ordem = $oDptoSel->iOrdem;
        $cldb_depusu->incluir($oParam->codusuario,$oDptoSel->iDptoSel);

        if ( $cldb_depusu->erro_status == 0 ) {
          
          $sqlerro        = true;
          $oRetorno->erro = 1;
          break;
        }  
      }    	
    } 

	  db_fim_transacao($sqlerro);

    /**
     * Limpa o cache do usuário que esta sendo alterado
     */
    DBMenu::limpaCache($oParam->codusuario);

    break;    
}

echo $oJson->encode($oRetorno);
?>