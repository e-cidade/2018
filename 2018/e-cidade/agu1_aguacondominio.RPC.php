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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson             = new services_json();

$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno          = new stdClass();

//$oDaoArreCad  = db_utils::getDao('arrecad');

$oRetorno->status  = 1;
$oRetorno->message = '';
$sMsg              = '';
$lErro             = false;
$oDaoClaAguaCondominioMat = db_utils::getDao('aguacondominiomat');
switch ($oParam->sExec) {
  
  /*
   *  1 - lista débitos da matricula e tipo selecionado
   *  2 - lista os históricos para lançar o desconto (histcalc)
   */
  
  case 'getMatriculasCondominio':
    
    unset($sSql,$aRegistros, $oResulDebitos, $oResulDebitosA, $aDebitosResult);
    
    
    $sSqlDaoClaAguaCondominioMat =
      $oDaoClaAguaCondominioMat->sql_query($oParam->iCodCondominio, null,
                                           "x40_codcondominio,x40_matric,z01_nome as x01_numcgm");
    
    $rsDaoClaAguaCondominioMat   = $oDaoClaAguaCondominioMat->sql_record($sSqlDaoClaAguaCondominioMat);
    
    if ($oDaoClaAguaCondominioMat->numrows > 0) {
      
      $aRegistros    = db_utils::getCollectionByRecord($rsDaoClaAguaCondominioMat, false, false, true);
     
      $oResul = array();
      
      foreach ($aRegistros as $oRegistro) {
        
        $oResulMatricula = new stdClass();
        $oResulMatricula->x40_codcondominio = $oRegistro->x40_codcondominio;
        $oResulMatricula->x40_matric        = $oRegistro->x40_matric;
        $oResulMatricula->x01_numcgm        = $oRegistro->x01_numcgm;
        $aResult[]                          = $oResulMatricula;
     
      }
      
      $oRetorno->aMatriculas = $aResult;
      
      $oRetorno->status  = 1;
      
    } else {
      
      $oRetorno->status  = 2;
      
      $oRetorno->message = 'Nenhum registro encontrado.';
      
    }
    
    break;
    
    
  case "excluirMatriculaCondominio":
     
    try {
      
      $oDaoClaAguaCondominioMat->excluir($oParam->iCodCondominio, $oParam->iMatricula);
      
      $oRetorno->status = 1;
      $oRetorno->message = "ok";
      
    } catch (Exception $eErro) {
      
      db_fim_transacao(true);
      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    
    break;

}

echo $oJson->encode($oRetorno);