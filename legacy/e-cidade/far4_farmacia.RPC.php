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

require_once('libs/db_stdlib.php');
require_once('libs/db_stdlibwebseller.php');
require_once('libs/db_utils.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/JSON.php');
require_once('dbforms/db_funcoes.php');
require_once("std/db_stdClass.php");
function formataData($dData, $iTipo = 1) {

  if (empty($dData)) {
    return '';
  }

  if ($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];

 return $dData;

}

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

if ($oParam->exec == 'getRetiradasCgs') {

  $oParametroFarmacia                 = db_stdClass::getParametro("far_parametros", array());
  $lImpressaotermica                  = $oParametroFarmacia[0]->fa02_utilizaimpressoratermica;
  $oRetorno->lUtilizaImpressaoTermica = $lImpressaotermica == 't'?true:false;  
  $oDaoFarRetirada                    = db_utils::getdao('far_retirada');
  $sCampos                            = 'far_retirada.*, descrdepto, fa07_i_matrequi';
  $sWhere                             = 'fa04_i_cgsund = '.$oParam->iCgs;
  $sSql                               = $oDaoFarRetirada->sql_query_retiradas(null, $sCampos, 'fa04_i_codigo desc', $sWhere);
  $rs                                 = $oDaoFarRetirada->sql_record($sSql);

  if ($oDaoFarRetirada->numrows > 0) {
    $oRetorno->aRetiradas = db_utils::getColectionByRecord($rs, false, false, true);
  } else {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = 'Nenhuma retirada encontrada para este CGS.';

  }

} elseif ($oParam->exec == 'getSaldoTotalMedicamento') {

  if (!isset($oParam->iCodMater)) { // Foi passado apenas o código do medicamento (fa01_i_matmater)

    $oDaoFarMaterSaude = db_utils::getdao('far_matersaude');
    $sSql              = $oDaoFarMaterSaude->sql_query_file($oParam->iMedicamento, 'fa01_i_codmater');
    $rs                = $oDaoFarMaterSaude->sql_record($sSql);
    if ($oDaoFarMaterSaude->numrows > 0) {
      $oParam->iCodMater = db_utils::fieldsmemory($rs, 0)->fa01_i_codmater;
    } else {

      $oRetorno->iStatus   = 0;
      $oRetorno->sMessage  = 'Medicamento informado não encontrado.';
      $oRetorno->m70_quant = 0;

    }

  }

  $oDaoMatEstoque = db_utils::getdao('matestoque');
  $sCampos        = ' sum(m70_quant) as m70_quant ';
  $sWhere         = 'm70_codmatmater = '.$oParam->iCodMater;
  $sSql           = $oDaoMatEstoque->sql_query_file(null, $sCampos, '', $sWhere);
  $rs             = $oDaoMatEstoque->sql_record($sSql);
  if ($oDaoMatEstoque->numrows > 0) {

    $oRetorno->m70_quant = db_utils::fieldsmemory($rs, 0)->m70_quant;
    if (empty($oRetorno->m70_quant)) {
      $oRetorno->m70_quant = 0;
    }

  } else {

    $oRetorno->iStatus   = 0;
    $oRetorno->sMessage  = 'Nenhum registro de estoque encontrado para este medicamento.';
    $oRetorno->m70_quant = 0;

  }

}

echo $oJson->encode($oRetorno);
?>