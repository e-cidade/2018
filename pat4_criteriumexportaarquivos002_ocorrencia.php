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

$iIdDaEmpresa 	 = db_getsession("DB_instit");
$sSqlOcorrencia  = "select '12'    as tipo_de_registro, ";
$sSqlOcorrencia .= "       $iIdDaEmpresa as id_da_empresa, ";
$sSqlOcorrencia .= "       t68_sequencial as id_da_ocorrencia, ";
$sSqlOcorrencia .= "       t68_descricao as nome_da_ocorrencia";
$sSqlOcorrencia .= "       from ocorrenciabens";

$rsOcorrencia   = pg_query($sSqlOcorrencia);
$iNumeroLinhas 	= pg_num_rows($rsOcorrencia);

for ($i=0; $i<$iNumeroLinhas; $i++) {
	$oOcorrencia   = db_utils::fieldsMemory($rsOcorrencia,$i);
	$oLayoutTxt->setByLineOfDBUtils($oOcorrencia,3,"12");
	db_atutermometro($i, $iNumeroLinhas, 'termometroitem', 1, "Processando Arquivo $arquivo");
}
?>