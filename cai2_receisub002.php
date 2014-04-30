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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libsys.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']); 

# Include AgataAPI class

include_once 'dbagata/classes/core/AgataAPI.class';

ini_set("error_reporting","E_ALL & ~NOTICE");

# Instantiate AgataAPI

	if($ordenado == "r"){
		$clagata = new cl_dbagata("caixa/cai2_receisub002.agt");
	}else if($ordenado == "t") { 
		$clagata = new cl_dbagata("caixa/cai2_taxareceisub002.agt");
	}

  $sqlwhere = " k07_instit = ".db_getsession('DB_instit');
  $headDtCria = " Todos ";
	
	if ($dataCria == "c"){
		$sqlwhere  .= " and k07_data is not null";
		$headDtCria = " Com Data de Criaчуo";
	}else if ($dataCria == "s"){ 
		$sqlwhere .= " and k07_data is null";
		$headDtCria = " Sem Data de Criaчуo";
	} 
  


$api = $clagata->api;

$api->setParameter('$head1', "CADASTRO DE RECEITAS");
$api->setParameter('$head2', "DATA EMISSТO: ".date('d/m/Y',db_getsession('DB_datausu')));
$api->setParameter('$head3', "TIPO :".$headDtCria);
$api->setParameter('$head4', "");
$api->setParameter('$head5', "");
$api->setParameter('$head6', "");
$api->setParameter('$instit', db_getsession('DB_instit'));


//Modifica Order By
$xml = $api->getReport();
$xml["Report"]["DataSet"]["Query"]["Where"] = "$sqlwhere";
$api->setReport($xml);


$ok = $api->generateReport();

if (!$ok)
{
    echo $api->getError();
}
else
{ 
    db_redireciona($clagata->arquivo);
}
?>