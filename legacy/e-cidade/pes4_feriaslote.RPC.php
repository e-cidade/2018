<?php
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
require("libs/db_libpessoal.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");

$oJson       = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
switch ($oParam->exec) {
  
  case "getFeriasCadastradas" :
    
    $iMesUsu     = db_mesfolha();
    $iAnoUsu     = db_anofolha();
    $oRetorno->iMesUsu = $iMesUsu;
    $oRetorno->iAnoUsu = $iAnoUsu;
    $sSqlFerias  = "SELECT distinct rh93_regist," ;
    $sSqlFerias .= "       z01_nome, ";
    $sSqlFerias .= "       rh93_mesusu, ";
    $sSqlFerias .= "       rh93_anousu, "; 
    $sSqlFerias .= "       r30_ndias, ";  
    $sSqlFerias .= "       r30_per1i, ";  
    $sSqlFerias .= "       r30_per1f ";  
    $sSqlFerias .= "  From rhcadastroferiaslote ";
    $sSqlFerias .= "       left join cadferia on rh93_mesusu = r30_mesusu ";
    $sSqlFerias .= "                         and rh93_anousu = r30_anousu ";
    $sSqlFerias .= "                         and rh93_regist = r30_regist ";
    $sSqlFerias .= "                         and (r30_proc1 = '{$iAnoUsu}/{$iMesUsu}'"; 
    $sSqlFerias .= "                              or r30_proc2 = '{$iAnoUsu}/{$iMesUsu}') ";
    $sSqlFerias .= "       inner join rhpessoal on rh93_regist = rh01_regist ";
    $sSqlFerias .= "       inner join cgm       on rh01_numcgm = z01_numcgm ";
    $sSqlFerias .= " where rh93_mesusu = {$iMesUsu} ";
    $sSqlFerias .= "   and rh93_anousu = {$iAnoUsu} ";
    $sSqlFerias .= " order by rh93_regist";
    $rsFerias    = db_query($sSqlFerias);
    $oRetorno->itens =  db_utils::getColectionByRecord($rsFerias, false, false, true); 
    break;
}
echo $oJson->encode($oRetorno);

?>