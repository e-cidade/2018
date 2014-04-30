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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/JSON.php");
include("libs/db_usuariosonline.php");
include("classes/db_baseregimematdiv_classe.php");
include("classes/db_serieregimemat_classe.php");
include("dbforms/db_funcoes.php");
$clbaseregimematdiv = new cl_baseregimematdiv;
$clserieregimemat = new cl_serieregimemat;

$oPost = db_utils::postMemory($_POST);

if($oPost->sAction == 'PesquisaDivisao') {
 $result = $clbaseregimematdiv->sql_record($clbaseregimematdiv->sql_query("","ed219_i_codigo,ed219_c_nome","ed219_i_ordenacao","  ed224_i_base = {$oPost->base}"));
 $aResult = db_utils::getColectionByRecord($result, false, false, true);
 $oJson = new services_json();
 echo $oJson->encode($aResult);
}

if($oPost->sAction == 'PesquisaEtapaDivisao') {
 $result = $clserieregimemat->sql_record($clserieregimemat->sql_query_turma("","DISTINCT ed223_i_codigo,ed11_i_codigo,ed11_i_codcenso,ed11_c_descr,ed223_i_ordenacao,ed266_c_descr","ed223_i_ordenacao","  ed223_i_regimemat = {$oPost->codregime} AND ed11_i_ensino = {$oPost->codensino} AND ed223_i_regimematdiv = {$oPost->coddivisao}"));
 $aResult = db_utils::getColectionByRecord($result, false, false, true);
 $oJson = new services_json();
 echo $oJson->encode($aResult);
}

if($oPost->sAction == 'PesquisaEtapa') {
 $result = $clserieregimemat->sql_record($clserieregimemat->sql_query_turma("","DISTINCT ed223_i_codigo,ed11_i_codigo,ed11_i_codcenso,ed11_c_descr,ed223_i_ordenacao,ed266_c_descr","ed223_i_ordenacao","  ed223_i_regimemat = {$oPost->codregime} AND ed11_i_ensino = {$oPost->codensino} AND ed31_i_codigo = {$oPost->codbase}"));
 $aResult = db_utils::getColectionByRecord($result, false, false, true);
 $oJson = new services_json();
 echo $oJson->encode($aResult);
}
if($oPost->sAction == 'AtualizaAuto') {
 $result = pg_query("UPDATE turmaserieregimemat SET ed220_c_aprovauto = '{$oPost->valorauto}' WHERE ed220_i_codigo = {$oPost->codtsrmat}");
}
?>