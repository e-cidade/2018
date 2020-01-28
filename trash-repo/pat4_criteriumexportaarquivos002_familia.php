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

$iIdDaEmpresa = db_getsession("DB_instit");
/*
 select * from clabens where t64_analitica is false ;
*/

$sSqlFamilia 	= "select  '06'          	as tipo_de_registro, ";
$sSqlFamilia .= "         $iIdDaEmpresa	as id_da_empresa,";
$sSqlFamilia .= "					t64_codcla    as id_da_familia,";
$sSqlFamilia .= "         t64_descr     as nome_da_familia"; 
$sSqlFamilia .=	"		from clabens ";
$sSqlFamilia .= "			where t64_analitica is false order by t64_codcla";


//$sSqlFamilia .= " where t64_class ilike '%0000' AND ";
//$sSqlFamilia .= " t64_codcla not in (select t64_codcla from clabens where t64_class ilike '%000000')";
//$sSqlFamilia .= "	order by t64_class asc";

$rsFamilia     = pg_query($sSqlFamilia);
$iNumeroLinhas = pg_num_rows($rsFamilia);

for ($i=0; $i<$iNumeroLinhas; $i++) {
	$oFamilia = db_utils::fieldsMemory($rsFamilia,$i);
	$oLayoutTxt->setByLineOfDBUtils($oFamilia,3,"06");
	db_atutermometro($i,$iNumeroLinhas,'termometroitem', 1, "Procesando Arquivo $arquivo");
}
//$sSqlEmpresa  = "select '06'    as tipo_de_registro, ";
//$sSqlEmpresa .= "       '10101' as id_da_empresa, ";
//$sSqlEmpresa .= "       '10003' as id_da_familia, ";
//$sSqlEmpresa .= "       'TESTE FAMILIA EXPORTACAO CRITERIUM' as nome_da_familia";
//$rsEmpresa    = pg_query($sSqlEmpresa);
//$oEmpresa     = db_utils::fieldsMemory($rsEmpresa,0);

// var_dump($oEmpresa);
//$oLayoutTxt->setByLineOfDBUtils($oEmpresa,3,"06");
//$iCountItemSub = 5;
//for ($i=0; $i < 5; $i++) {
//	sleep(1);
//	db_atutermometro($i, $iCountItemSub, 'termometroitem', 1, "Processando Arquivo $arquivo");
//}
?>