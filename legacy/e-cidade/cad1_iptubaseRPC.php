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
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($HTTP_POST_VARS);

$objJSON           = new Services_JSON();


if(isset($oPost->j107_sequencial) && trim($oPost->j107_sequencial)!=""){
	
	$sQueryPredios  = "select j111_sequencial,j111_nome from condominio ";
	$sQueryPredios .= "						inner join predio on j107_sequencial = j111_condominio ";
	$sQueryPredios .= " 		where j107_sequencial = $oPost->j107_sequencial";
	
	$resQueryPredios = pg_query($sQueryPredios);
	if (pg_num_rows($resQueryPredios) > 0) {
		
		$iTotalPredios	= pg_num_rows($resQueryPredios);
		$aPredios 			= array();
		
		for	($i = 0; $i < $iTotalPredios; $i++)	{
			
			$oRow 			= db_utils::fieldsMemory($resQueryPredios, $i, false, false, true);
			$aPredios[] = $oRow;
					
		}
		echo $objJSON->encode($aPredios);	
	} else {
		echo $objJSON->encode("Vazio");
	}

} else {
		echo $objJSON->encode("Vazio");
	}


?>