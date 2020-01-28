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
$sSqlForncedor 	= "SELECT DISTINCT  '07'    			as tipo_de_registro,";
$sSqlForncedor .= "									$iIdDaEmpresa	as id_da_empresa,";
$sSqlForncedor .= "									C.z01_numcgm	as id_do_fornecedor,";
$sSqlForncedor .=	"									C.z01_nome 		as nome_do_fornecedor";
$sSqlForncedor .= "	FROM bens as B INNER JOIN cgm as C ON (B.t52_numcgm = C.z01_numcgm)";
$sSqlForncedor .= " WHERE B.t52_instit = $iIdDaEmpresa ORDER BY C.z01_numcgm ASC";

$rsFornecedor 	= pg_query($sSqlForncedor); 
$iNumeroLinhas 	= pg_num_rows($rsFornecedor);

for ($i=0; $i<$iNumeroLinhas; $i++) {
	$oFornecedor     = db_utils::fieldsMemory($rsFornecedor,$i);
	$oLayoutTxt->setByLineOfDBUtils($oFornecedor,3,"07");
	db_atutermometro($i, $iNumeroLinhas, 'termometroitem', 1, "Processando Arquivo $arquivo");
}

//$sSqlEmpresa  = "select '07'    as tipo_de_registro, ";
//$sSqlEmpresa .= "       '10101' as id_da_empresa, ";
//$sSqlEmpresa .= "       '10100' as id_do_fornecedor, ";
//$sSqlEmpresa .= "       'TESTE FORNECEDOR EXPORTACAO CRITERIUM' as nome_do_fornecedor";
//$rsEmpresa    = pg_query($sSqlEmpresa);
//$oEmpresa     = db_utils::fieldsMemory($rsEmpresa,0);
//// var_dump($oEmpresa);
//$oLayoutTxt->setByLineOfDBUtils($oEmpresa,3,"07");
//$iCountItemSub = 5;
//for ($i=0; $i < 5; $i++) {
//	sleep(1);
//	db_atutermometro($i, $iCountItemSub, 'termometroitem', 1, "Processando Arquivo $arquivo");
//}
?>