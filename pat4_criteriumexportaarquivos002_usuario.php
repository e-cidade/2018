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


$sSqlUsuario  = "select '14'    as tipo_de_registro, ";
$sSqlUsuario .= "       t08_id_usuario as id_do_usuario, ";
$sSqlUsuario .= "       nome as nome_do_usuario,";
$sSqlUsuario .= "       login as login_do_usuario,";
$sSqlUsuario .= "       t08_senha as senha_do_usuario";
$sSqlUsuario .= "       from usuariocriterium inner join db_usuarios";
$sSqlUsuario .= "       on t08_id_usuario = id_usuario";

$rsUsuario    = pg_query($sSqlUsuario);
$iNumeroLinhas	= pg_num_rows($rsUsuario);

for ($i=0; $i<$iNumeroLinhas; $i++) {
	$oUsuario     = db_utils::fieldsMemory($rsUsuario,$i);
	$oLayoutTxt->setByLineOfDBUtils($oUsuario,3,'14');
	db_atutermometro($i, $iNumeroLinhas, 'termometroitem', 1, "Processando Arquivo $arquivo");	
}
//
//$oUsuario	    = db_utils::fieldsMemory($rsUsuario,0);
//$iCountItemSub = 3;
//// var_dump($oEmpresa);
//$oLayoutTxt->setByLineOfDBUtils($oUsuario,3,"14");
//for ($i=0; $i < 3; $i++) {
//	sleep(1);
//	db_atutermometro($i, $iCountItemSub, 'termometroitem', 1, "Processando Arquivo $arquivo");
//}
?>