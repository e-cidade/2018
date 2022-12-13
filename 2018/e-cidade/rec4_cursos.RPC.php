<?
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

/**
 * @version   $Revision: 1.1 $
 * @revision  $Author: dbrafael.nery $
 */
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");

require_once ("dbforms/db_funcoes.php");

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';

$aDadosRetorno          = array();

/**
 * Camada de Tentativas do RPC
 */
try {

  switch ($oParam->sExec) {

    /**
     * Reemissao do TXT
     */
    case "getDadosCursos":

      try {

        $oDaoCurric = db_utils::getDao('curric');
         
        $sSqlCurric = $oDaoCurric->sql_query_cursos($oParam->iCodigoPromocao);
         
        $rsCurric   = db_query($sSqlCurric);
         
        if (!$rsCurric) {

          throw new Exception( 'Erro ao resgatar dados dos cursos. \n' . pg_last_error() );
           
        }
        
        $oRetorno->aDados = array();
        
        if (pg_num_rows($rsCurric) > 0) {
          
        }
        $oRetorno->aDados = db_utils::getCollectionByRecord($rsCurric,true,false,true);
        
      } catch (Exception $eErroBanco) {
        throw new Exception( $eErroBanco->getMessage() );
      }
      break;
       
    default:
      throw new Exception("Nenhuma Opчуo Definida");
    break;
  }


  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);

} catch (Exception $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}

?>