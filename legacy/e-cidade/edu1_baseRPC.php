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
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_regimematdiv_classe.php");
include("classes/db_baseregimematdiv_classe.php");
include("classes/db_serieregimemat_classe.php");
include("classes/db_basemps_classe.php");
include("dbforms/db_funcoes.php");
$clregimematdiv = new cl_regimematdiv;
$clbaseregimematdiv = new cl_baseregimematdiv;
$clserieregimemat = new cl_serieregimemat;
$clbasemps = new cl_basemps;

$oPost = db_utils::postMemory($_POST);

if($oPost->sAction == 'PesquisaDivisao') {
	
  $result = $clregimematdiv->sql_record($clregimematdiv->sql_query("","ed219_i_codigo,ed219_c_nome","ed219_i_ordenacao"," ed219_i_regimemat = {$oPost->regime}"));
  $aResult = db_utils::getColectionByRecord($result, false, false, true);
  $oJson = new services_json();
  echo $oJson->encode($aResult);
  
}

if($oPost->sAction == 'PesquisaDivisaoCadastrada') {
	
  $result = $clbaseregimematdiv->sql_record($clbaseregimematdiv->sql_query("","ed219_i_codigo,ed219_c_nome","ed219_i_ordenacao","  ed224_i_base = {$oPost->base}"));
  $aResult = db_utils::getColectionByRecord($result, false, false, true);
  $oJson = new services_json();
  echo $oJson->encode($aResult);
  
}
if($oPost->sAction == 'PesquisaRegime') {

  $aRetorno = array();
  $sCodRegime = $oPost->codregime;
  $aCodRegime = explode("|",$sCodRegime);
  $aRetorno[] = $aCodRegime[1];   	
  $rsBasemps = $clbasemps->sql_record($clbasemps->sql_query(""," distinct ed34_i_serie,ed11_c_descr,ed11_i_sequencia","ed11_i_sequencia"," ed34_i_base = {$oPost->codbase}"));
  for ($t=0;$t<$clbasemps->numrows;$t++) {
  	
  	$oBasemps = db_utils::fieldsmemory($rsBasemps,$t);
  	$rsSerieregimemat = $clserieregimemat->sql_record($clserieregimemat->sql_query(""," ed223_i_codigo",""," ed223_i_serie = {$oBasemps->ed34_i_serie} AND ed223_i_regimemat = $aCodRegime[0]"));
  	if ($clserieregimemat->numrows==0) {
  		
  	  $aRetorno[] = $oBasemps->ed34_i_serie." - ".$oBasemps->ed11_c_descr; 	
  		
  	}
  	
  }   	
  $oJson = new services_json();
  echo $oJson->encode($aRetorno);
  
}
?>