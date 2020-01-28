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

require_once("fpdf151/scpdf.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("model/regraEmissao.model.php");
require_once("std/db_stdClass.php");

$oPost  = db_utils::postMemory($_POST);
$oJson  = new services_json();
$lErro  = false;
$sMsg   = ""; 

   /*
    * Consulta de Unicas
    * 
    */
if (isset($_POST["json"])) {
	
	$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));
	$oRetorno          = new stdClass();
	$oRetorno->status  = 1;
	$oRetorno->message = 1;
	$lErro             = false;
	$sMensagem         = "";   
	
	$aDadosUnicas      = array();
	
  switch ($oParam->exec) {
    
    case 'VerUnicas' :
    	
      $iAnousu     = $oParam->iAnousu;
    	$sDatausu    = date('Y-m-d',db_getsession('DB_datausu'));
      
      $sSqlUnicas  =  " select distinct k00_dtvenc, k00_dtoper, k00_percdes "; 
      $sSqlUnicas .=  "   from recibounica ";
      $sSqlUnicas .=  "        inner join iptunump on j20_numpre = k00_numpre ";
      $sSqlUnicas .=  "                           and j20_anousu = {$iAnousu} ";
      $sSqlUnicas .=  "  where k00_tipoger = 'G' ";
      $sSqlUnicas .=  "    and k00_dtvenc > '{$sDatausu}' order by k00_dtvenc, k00_percdes ";    	
    	
	    $rsUnicas = db_query($sSqlUnicas);
	    $aUnicas  = db_utils::getColectionByRecord($rsUnicas, false, false, false);
    	
	    foreach ($aUnicas as $iIndUnicas => $oValorUnicas){
	    	
	    	$oDadosUnicas = new stdClass();
	    	$oDadosUnicas->id     = $oValorUnicas->k00_dtvenc . "=" . $oValorUnicas->k00_dtoper . "=" . $oValorUnicas->k00_percdes;
	    	$oDadosUnicas->unicas = utf8_encode("Vencimento: "      . db_formatar($oValorUnicas->k00_dtvenc, "d") . 
	    	                                    " - Lançamento: "   . db_formatar($oValorUnicas->k00_dtoper, "d") . 
	    	                                    " - Desconto: "     . $oValorUnicas->k00_percdes . " %");
	    	$aDadosUnicas[] = $oDadosUnicas;
	    	
	    }

    break;  
    
  }
  
  $oRetorno->dados      =   $aDadosUnicas;
  echo $oJson->encode($oRetorno);   
  
       /*
        *   Se nao for consulta de unicas segue o codigo como antes
        */
} else {
	
   if ( $oPost->tipo == "txt" or $oPost->tipo == "txtbsj") {
     $iCadTipoMod = 10;
   } else if ($oPost->tipo == "pdf") {
     $iCadTipoMod = 4;
   } else if ($oPost->tipo == "IGC702") {
     $iCadTipoMod = 11;
   } else {
     $lErro = true;
     $sMsg  = "Tipo de emissão não informada!";
   }

   if (!$lErro) {
      
     $sSqlBuscaTipo  = " select q92_tipo                                                               ";
     $sSqlBuscaTipo .= "   from cfiptu                                                                 ";
     $sSqlBuscaTipo .= "        inner join cadvencdesc on cadvencdesc.q92_codigo = cfiptu.j18_vencim   ";  
     $sSqlBuscaTipo .= "  where j18_anousu = {$oPost->anousu}                                          ";

     $rsBuscaTipo = pg_query($sSqlBuscaTipo);
     $iLinhasTipo = pg_numrows($rsBuscaTipo);
   
     if ( $iLinhasTipo > 0 ) {
     
        $oTipoDebito   = db_utils::fieldsMemory($rsBuscaTipo,0);
        
        $oRegraEmissao = new regraEmissao( $oTipoDebito->q92_tipo,
                         $iCadTipoMod, 
                         db_getsession('DB_instit'), 
                         date("Y-m-d", db_getsession("DB_datausu")),
                         db_getsession('DB_ip')); 

    if ($oRegraEmissao->isArrecadacao()) {
      $sMsg = "s";                           
    } else {
      $sMsg = "n";  
    }
    
     } else {
      $lErro     = true;
      $sMensagem = "Tipo de débito não configurado!";
     }
     
   }

   $aRetornaCampos = array("lErro"=>$lErro, "sMsg"=>urlencode($sMsg));
   
   echo $oJson->encode($aRetornaCampos);	
	
}
?>