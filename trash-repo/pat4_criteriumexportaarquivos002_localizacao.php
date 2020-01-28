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

$sSqlLocalizacao  = "select distinct on (coddepto)							 							";
$sSqlLocalizacao .=" 				'08'    			as tipo_de_registro, 								";
$sSqlLocalizacao .= "       $iIdDaEmpresa as id_da_empresa, 									";
$sSqlLocalizacao .= "       coddepto 			as id_da_localizacao, 							";
$sSqlLocalizacao .= "       descrdepto		as nome_da_localizacao							";
$sSqlLocalizacao .= " 	from db_depart 																				"; 
$sSqlLocalizacao .=	" where instit = $iIdDaEmpresa												";

$rsLocalizacao    = pg_query($sSqlLocalizacao);
$iNumeroLinhas    = pg_num_rows($rsLocalizacao);

for ($i=0; $i<$iNumeroLinhas; $i++) {
	$oLocalizacao = db_utils::fieldsMemory($rsLocalizacao,$i);
	$oLayoutTxt->setByLineOfDBUtils($oLocalizacao,3,"08");
	db_atutermometro($i,$iNumeroLinhas,'termometroitem', 1, "Procesando Arquivo $arquivo");
}

//$oLocalizacao     = db_utils::fieldsMemory($rsLocalizacao,0);
// var_dump($oEmpresa);
//$oLayoutTxt->setByLineOfDBUtils($oLocalizacao,3,"08");
//for ($i=0; $i < 5; $i++) {
//	sleep(1);
//	db_atutermometro($i, $iCountItemSub, 'termometroitem', 1, "Processando Arquivo $arquivo");
//}
?>