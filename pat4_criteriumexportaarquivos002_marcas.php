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

$iTipo = $oParametro->valor == 0 ? '22' : '23';
$iIdDaEmpresa = db_getsession("DB_instit");
$sSqlMarca  = "select '09'    as tipo_de_registro, ";
$sSqlMarca .= "       $iIdDaEmpresa as id_da_empresa, ";
$sSqlMarca .= "       t65_sequencial as id_da_marca, ";
$sSqlMarca .= "       t65_descricao as nome_da_marca";
$sSqlMarca .= "     from bensmarca";

$rsMarca   			= pg_query($sSqlMarca);
$iNumeroLinhas 	= pg_num_rows($rsMarca);

for ($i=0; $i<$iNumeroLinhas; $i++) {
	$oMarca     = db_utils::fieldsMemory($rsMarca,$i);
	$oLayoutTxt->setByLineOfDBUtils($oMarca,3,"09");
	db_atutermometro($i, $iNumeroLinhas, 'termometroitem', 1, "Processando Arquivo $arquivo");
}
?>