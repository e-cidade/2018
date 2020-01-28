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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_pcfornecertif_classe.php");
require_once("classes/db_pcfornecertifdoc_classe.php");
require_once("classes/db_pctipodoccertif_classe.php");
require_once("classes/db_pcdoccertif_classe.php");
require_once("classes/db_pcparam_classe.php");
require_once("classes/db_pctipocertif_classe.php");
require_once("libs/db_conecta.php");
require_once("libs/db_utils.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;

switch ($oParam->exec) {

  case 'verificaPorFornecedor':

    try {
      
    	$sDataSessao = date('Y-m-d', db_getsession("DB_datausu"));
    	$sWhere      = " pc74_pctipocertif = {$oParam->pctipocertif} AND pc74_pcforne = {$oParam->pcforne} 
    	                    AND ('{$sDataSessao}' BETWEEN pc74_data AND pc74_validade) ";
    	
    	$oForneCertif  = new cl_pcfornecertif();
    	$sSqlForne     = $oForneCertif->sql_query(null, "*", "", $sWhere);
      $rsForneCertif = $oForneCertif->sql_record($sSqlForne);
      
      if ($oForneCertif->numrows > 0) {
    	
        $oFC              = db_utils::fieldsMemory($rsForneCertif, 0);
        $oFC->aDocs       = array();
        
        $oPcTipoDocCertif    = new cl_pctipodoccertif();
        $sSqlPcTipoDocCertif = $oPcTipoDocCertif->sql_query(null, "*", "", "pc72_pctipocertif={$oFC->pc74_pctipocertif}");
        $rsPcTipoDocCertif   = $oPcTipoDocCertif->sql_record($sSqlPcTipoDocCertif);
        
        $aPcTipoCertif       = db_utils::getColectionByRecord($rsPcTipoDocCertif);    
        
        for ($i=0; $i<count($aPcTipoCertif); $i++) {
          $oForneCertifDoc  = new cl_pcfornecertifdoc();
          $sSqlCertifDoc    = $oForneCertifDoc->sql_query(null,"*","pc75_codigo desc",
                                                                    "pc75_pcdoccertif= {$aPcTipoCertif[$i]->pc72_pcdoccertif} and 
                                                                      pc74_pctipocertif = {$aPcTipoCertif[$i]->pc72_pctipocertif} and 
                                                                      pc74_pcforne= {$oFC->pc74_pcforne} ");
          $rsForneCertifDoc  = $oForneCertifDoc->sql_record($sSqlCertifDoc);
          $oFC->aDocs[]   = db_utils::fieldsMemory($rsForneCertifDoc,0);
        }
        
        $oRetorno->oForneCertif      = $oFC;
    	  $oRetorno->possuiCertificado = 1;
    	  
    	} else {
    		$oRetorno->possuiCertificado = 0;
      }
    	  
    }catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode(str_replace("\\n", "\n",$eErro->getMessage()));

    }
    
    break;  
}

echo $oJson->encode($oRetorno);   
?>