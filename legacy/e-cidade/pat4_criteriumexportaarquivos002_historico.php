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

//tem que alterar o campo da hora para o formato de 6 digitos
$iTipo = $oParametro->valor == 0 ? '22' : '23';
$iIdDaEmpresa = db_getsession("DB_instit");
$sSqlHistorico  = "select '$iTipo'    as tipo_de_registro, ";
$sSqlHistorico .= "       '$iIdDaEmpresa'	as id_da_empresa,";
$sSqlHistorico .= "       t69_codbem as id_do_bem,";
$sSqlHistorico .= "       t69_dthist as data_do_historico, ";
$sSqlHistorico .= "       t69_hora as hora_do_historico, ";
$sSqlHistorico .= "       t69_sequencial as id_da_ocorrencia, ";
$sSqlHistorico .= "       t69_obs as descricao_da_ocorrencia ";
$sSqlHistorico .= "			from histbensocorrencia";
//$sSqlHistorico .= "			from histbensocorrencia inner join"; 
//$sSqlHistorico .= "				ocorrenciabens on t69_ocorrenciasbens = t68_sequencial";

$rsHistorico    = pg_query($sSqlHistorico);
$iNumeroLinhas 	= pg_num_rows($rsHistorico);

for ($i=0; $i<$iNumeroLinhas; $i++) {
	$oHistorico     = db_utils::fieldsMemory($rsHistorico,$i);
	$oLayoutTxt->setByLineOfDBUtils($oHistorico,3,"22");
	db_atutermometro($i, $iNumeroLinhas, 'termometroitem', 1, "Processando Arquivo $arquivo");
}
//$oEmpresa     = db_utils::fieldsMemory($rsEmpresa,0);
//// var_dump($oEmpresa);
//$oLayoutTxt->setByLineOfDBUtils($oEmpresa,3,'22');
//for ($i=0; $i < 5; $i++) {
//$iCountItemSub = 5;
//	sleep(1);
//	db_atutermometro($i, $iCountItemSub, 'termometroitem', 1, "Processando Arquivo $arquivo");
//}
?>