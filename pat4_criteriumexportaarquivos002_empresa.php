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

$iTipo = $oParametro->valor == 0 ? '02' : '24';
$iIdDaEmpresa = db_getsession("DB_instit");

$sSqlEmpresa  = "select '$iTipo'    as tipo_de_registro, ";
$sSqlEmpresa .= "       codigo as id_da_empresa, "; // codigo db_config
$sSqlEmpresa .= "       nomeinst as nome_da_empresa"; //nome instr db_config
$sSqlEmpresa .= "				from db_config ";
$sSqlEmpresa .=	"					where codigo = $iIdDaEmpresa ";

$iCountItemSub = 2;
db_atutermometro(0, $iCountItemSub, 'termometroitem', 1, "Processando Arquivo $arquivo");
$rsEmpresa    = pg_query($sSqlEmpresa);
$oEmpresa     = db_utils::fieldsMemory($rsEmpresa,0);
/*
$sSqlEmpresa  = "select '$iTipo'    as tipo_de_registro, ";
$sSqlEmpresa .= "       '10101' as id_da_empresa, "; // codigo db_config
$sSqlEmpresa .= "       'Nome da Empresa' as nome_da_empresa"; //nome instr db_config
$rsEmpresa    = pg_query($sSqlEmpresa);
$oEmpresa     = db_utils::fieldsMemory($rsEmpresa,0);
*/
// var_dump($oEmpresa);
$oLayoutTxt->setByLineOfDBUtils($oEmpresa,3,'02');
db_atutermometro(1, $iCountItemSub, 'termometroitem', 1, "Processando Arquivo $arquivo");
?>