<?
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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_libsys.php");
include_once 'dbagata/classes/core/AgataAPI.class';

$oPost = db_utils::postMemory($_GET);
$sqlwhere = "1 = 1";
if(isset($oPost->z01_numcgm_inicial) && trim($oPost->z01_numcgm_inicial) != ""){
	$sqlwhere .= " and cgm.z01_numcgm >= $oPost->z01_numcgm_inicial";
}
if(isset($oPost->z01_numcgm_final) && trim($oPost->z01_numcgm_final) != ""){
	$sqlwhere .= " and cgm.z01_numcgm <= $oPost->z01_numcgm_final";
}
if(isset($oPost->z01_nome_inicial) && trim($oPost->z01_nome_inicial) != ""){
	$sqlwhere .= " and cgm.z01_nome >= '$oPost->z01_nome_inicial'";
}
if(isset($oPost->z01_nome_final) && trim($oPost->z01_nome_final) != ""){
	$sqlwhere .= " and (   cgm.z01_nome <= '{$oPost->z01_nome_final}'
										  or cgm.z01_nome ilike '$oPost->z01_nome_final%' )";
}
if(isset($oPost->listacidades) && trim($oPost->listacidades) != ""){
	$sqlwhere .= " and cgm.z01_munic in(select substring(cp05_localidades,1,40) from ceplocalidades where cp05_codlocalidades in($oPost->listacidades))";
}
if(isset($oPost->ordenacao) && trim($oPost->ordenacao) != ""){
	$sqlorder = "cgm.$oPost->ordenacao asc";
	switch ($oPost->ordenacao){
		case 'z01_numcgm':
			$ordenado = 'CGM';
			break;
		case 'z01_nome':
			$ordenado = 'Nome';
			break;
		case 'z01_ender':
			$ordenado = 'Endereço';
			break;
		case 'z01_munic':
			$ordenado = 'Município';
			break;
	}
}
//die($sqlwhere);
ini_set("error_reporting","E_ALL & ~NOTICE");

$clagata = new cl_dbagata("protocolo/pro1_relcgm002.agt");

$api = $clagata->api;

$api->setParameter('$head1', "Relatório: CGM");
$api->setParameter('$head2', "DATA EMISSÃO: ".date('d/m/Y',db_getsession('DB_datausu')));
$api->setParameter('$head3', "Ordenado pôr: $ordenado");
$api->setParameter('$head4', "");
$api->setParameter('$head5', "");
$api->setParameter('$head6', "");
//$api->setParameter('$cgminicial',$oPost->z01_numcgm_inicial);
//$api->setParameter('$cgmfinal'  ,$oPost->z01_numcgm_final);
//$api->setParameter('$restricao',$sqlwhere);

$xml = $api->getReport();
$xml["Report"]["DataSet"]["Query"]["Where"] = $sqlwhere;
$xml["Report"]["DataSet"]["Query"]["OrderBy"] = $sqlorder;
$api->setReport($xml);

ob_start();
$ok      = $api->generateReport();
$sBuffer = ob_get_contents();
ob_end_clean();

if ( $api->getRowNum() > 0 ) {
  if ($ok){  
	  echo $api->getError();
  }else{
  	db_redireciona($clagata->arquivo);
  }
  echo $sBuffer;
}else{
  header('Location: db_erros.php?fechar=true&db_erro=Não existem registros para os filtros selecionados.');
}


?>