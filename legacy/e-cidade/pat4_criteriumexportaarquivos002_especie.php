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


/*
select * from clabens where t64_analitica is true ;
*/

$iIdDaEmpresa = db_getsession("DB_instit");
$sSqlEspecie	 =	"select '04'    			as tipo_de_registro,";
$sSqlEspecie	.=	"				$iIdDaEmpresa	as id_da_empresa,";
$sSqlEspecie	.=	"				t64_codcla		as id_da_especie,";
$sSqlEspecie	.=	"       t64_descr   	as nome_da_especie";
$sSqlEspecie	.= 	" from clabens ";
$sSqlEspecie	.= 	" 	where t64_analitica is true order by t64_codcla"; //t64_class ilike '%000000' ORDER BY t64_class";  // where t64_analitica is true

$rsEspecie 		 =	pg_query($sSqlEspecie);
$iNumeroLinhas =	pg_num_rows($rsEspecie);

for ($i=0;$i<$iNumeroLinhas;$i++)	{
	
	$oEspecie    = db_utils::fieldsMemory($rsEspecie,$i);
	$oLayoutTxt->setByLineOfDBUtils($oEspecie,3,"04");
	db_atutermometro($i, $iNumeroLinhas, 'termometroitem', 1, "Processando Arquivo $arquivo");
}
/*
$sSqlEmpresa  = "select '04'    as tipo_de_registro, ";
$sSqlEmpresa .= "       '10101' as id_da_empresa, ";
$sSqlEmpresa .= "       '10001' as id_da_especie, ";
$sSqlEmpresa .= "       'TESTE ESPECIE EXPORTACAO CRITERIUM' as nome_da_especie";
*/

//$rsEmpresa    = pg_query($sSqlEmpresa);
// var_dump($oEmpresa);
//$oLayoutTxt->setByLineOfDBUtils($oEmpresa,3,"04");
//$iCountItemSub = 5;
//for ($i=0; $i < 5; $i++) {
//	sleep(1);
//	db_atutermometro($i, $iCountItemSub, 'termometroitem', 1, "Processando Arquivo $arquivo");
//}
?>